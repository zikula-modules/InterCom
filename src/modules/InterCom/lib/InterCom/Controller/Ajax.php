<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

class InterCom_Controller_Ajax extends Zikula_AbstractController
{
    /**
     * mark a message as read
     *
     */
    public function markmsgread()
    {
        $msg_id = (int)FormUtil::getPassedValue('msgid', 0, 'POST');
        ModUtil::apiFunc('InterCom', 'user', 'mark_read', array('msg_id' => $msg_id));
        return true;
    }

    /**
     * mark a message as answered
     *
     */
    public function markmsganswered()
    {
        $msg_id = (int)FormUtil::getPassedValue('msgid', 0, 'POST');
        ModUtil::apiFunc('InterCom', 'user', 'mark_replied', array('msg_id' => $msg_id));
        return true;
    }

    /**
     * reply to a message from the inbox
     *
     *@params msgid int the id of the message to reply to
     */
    public function replyfrominbox()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $msg_id = (int)FormUtil::getPassedValue('msgid', 0, 'POST');

        if ($msg_id <= 0) {
            AjaxUtil::error($this->__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.'));
        }

        $message = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => 'msg_inbox',
                'msg_id'   => $msg_id));

        if($message['from_user'] == $this->__('*Deleted user*')) {
            AjaxUtil::error('unknownuser', 404);
        }

        // Create output object
        $renderer = Zikula_View::getInstance('InterCom', false, null, true);

        $bbsmile = ModUtil::isHooked('BBSmile', 'InterCom');
        $bbcode  = ModUtil::isHooked('BBCode',  'InterCom');
        $message['reply_text'] = DataUtil::formatForDisplay($message['msg_text']);

        // replace [addsig] with users signature
        $signature = UserUtil::getVar('_SIGNATURE', $message['from_userid']);
        if (!empty($signature)){
            $message['reply_text'] = eregi_replace("\[addsig]$", "\n\n" . $signature , $message['reply_text']);
        } else {
            $message['reply_text'] = eregi_replace("\[addsig]$", '', $message['reply_text']);
        }
        if ($bbcode == true) {
            $message['reply_text'] = '[quote=' . UserUtil::getVar('uname', $message['from_userid']) . ']' . $message['reply_text'] . '[/quote]';
        }

        $renderer->assign('message', $message);
        $renderer->assign('allowsmilies', $bbsmile);
        $renderer->assign('allowbbcode',  $bbcode);

        // no output in xjsonheader as this might be too long for prototype!
        AjaxUtil::output(array('data' => $renderer->fetch('ajax/reply.tpl')));
    }

    /**
     * send a reply
     *
     *@params
     *@params
     */
    public function sendreply()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $replyto = FormUtil::getPassedValue('replyto', 0, 'POST');
        $subject = FormUtil::getPassedValue('subject', '', 'POST');
        $message = FormUtil::getPassedValue('message', '', 'POST');

        $boxtype = FormUtil::getPassedValue('boxtype', 'inbox', 'POST');
        $btarray = array('inbox' => 'msg_inbox', 'outbox' => 'msg_outbox', 'archive' => 'msg_stored');
        $boxtype = $btarray[$boxtype];

        if (empty($subject)) {
            AjaxUtil::error($this->__('Error! Could not find the subject line for the message. Please check your input and try again.'));
        }

        if (ModUtil::getVar('InterCom', 'messages_allowhtml') == 0) {
            $message = strip_tags($message);
        }

        if (ModUtil::getVar('InterCom', 'messages_allowsmilies') == 0) {
            $message = strip_tags($message);
        }

        if (empty($message)) {
            AjaxUtil::error($this->__('Error! Could not find the message text. Please check your input and try again.'));
        }

        $replytomessage = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => $boxtype,
                'msg_id'   => $replyto));
        //$message .= "[addsig]";
        $time = date("Y-m-d H:i:s");

        $from_uid = UserUtil::getVar('uid');
        if ($replyto <> 0) {
            ModUtil::apiFunc('InterCom', 'user', 'mark_replied',
                    array ('msg_id' => $replyto));
        }

        ModUtil::apiFunc('InterCom', 'user', 'store_message',
                array('from_userid' => $from_uid,
                'to_userid' => $replytomessage['from_userid'],
                'msg_subject' => $subject,
                'msg_time' => $time,
                'msg_text' => $message,
                'msg_inbox' => '1',
                'msg_outbox' => '1',
                'msg_stored' => '0'));
        ModUtil::apiFunc('InterCom', 'user', 'autoreply',
                array ('to_uid' => $replytomessage['from_userid'],
                'from_uid' => $from_uid,
                'subject' => $subject));

        AjaxUtil::output(_SENT, false, true);

    }

    /**
     * forward a message
     *
     *@params
     *@params
     */
    public function sendforward()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $forwardto = FormUtil::getPassedValue('forwardto', '', 'POST');
        $subject   = FormUtil::getPassedValue('subject', '', 'POST');
        $message   = FormUtil::getPassedValue('message', '', 'POST');

        if (empty($subject)) {
            AjaxUtil::error($this->__('Error! Could not find the subject line for the message. Please check your input and try again.'));
        }

        if (ModUtil::getVar('InterCom', 'messages_allowhtml') == 0) {
            $message = strip_tags($message);
        }
        if (empty($message)) {
            AjaxUtil::error($this->__('Error! Could not find the message text. Please check your input and try again.'));
        }

        //$message .= "[addsig]";
        $time = date("Y-m-d H:i:s");

        $from_uid = UserUtil::getVar('uid');
        $forwardto_uid = UserUtil::getIdFromName($forwardto);

        ModUtil::apiFunc('InterCom', 'user', 'store_message',
                array('from_userid' => $from_uid,
                'to_userid' => $forwardto_uid,
                'msg_subject' => $subject,
                'msg_time' => $time,
                'msg_text' => $message,
                'msg_inbox' => '1',
                'msg_outbox' => '1',
                'msg_stored' => '0'));
        ModUtil::apiFunc('InterCom', 'user', 'autoreply',
                array ('to_uid' => $forwardto_uid,
                'from_uid' => $from_uid,
                'subject' => $subject));

        AjaxUtil::output(_SENT, false, true);

    }

    /**
     * forward a message from the inbox
     *
     *@params msgid int the id of the message to forward
     */
    public function forwardfrominbox()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $msg_id = (int)FormUtil::getPassedValue('msgid', 0, 'POST');

        if ($msg_id <= 0) {
            AjaxUtil::error($this->__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.'));
        }

        $message = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => 'msg_inbox',
                'msg_id'   => $msg_id));

        // Create output object
        $renderer = Zikula_View::getInstance('InterCom', false);

        $bbcode = ModUtil::isHooked('BBCode', 'InterCom');
        $message['forward_text'] = DataUtil::formatForDisplay($message['msg_text']);
        if ($bbcode == true) {
            $message['forward_text'] = '[quote=' . UserUtil::getVar('uname', $message['from_userid']) . ']' . $message['forward_text'] . '[/quote]';
        }

        $renderer->assign('message', $message);
        $renderer->assign('allowsmilies', ModUtil::isHooked('BBSmile', 'InterCom'));
        $renderer->assign('allowbbcode', $bbcode);
        $renderer->assign('allowhtml', ModUtil::getVar('InterCom', 'messages_allowhtml'));

        // no output in xjsonheader as this might be too long for prototype!
        AjaxUtil::output(array('data' => $renderer->fetch('ajax/forward.tpl')));
    }

    /**
     * delete a message from the inbox
     *
     *@params msgid int the id of the message to delete
     */
    public function deletefrominbox()
    {
        $dom = ZLanguage::getModuleDomain('InterCom');
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $msg_id = FormUtil::getPassedValue('msgid', 0, 'POST');
        InterCom_ajax_deletepm('msg_inbox', $msg_id);

        // Get the amount of messages within each box
        $totalarray = ModUtil::apiFunc('InterCom', 'user', 'getmessagecount', '');

        AjaxUtil::output($totalarray, false, true);
    }

    /**
     * delete a message from the outbox
     *
     *@params msgid int the id of the message to delete
     */
    public function deletefromoutbox()
    {
        $dom = ZLanguage::getModuleDomain('InterCom');
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $msg_id   = FormUtil::getPassedValue('msgid', 0, 'POST');
        InterCom_ajax_deletepm('msg_outbox', $msg_id);

        // Get the amount of messages within each box
        $totalarray = ModUtil::apiFunc('InterCom', 'user', 'getmessagecount', '');

        AjaxUtil::output($totalarray, false, true);
    }

    /**
     * delete a message from the archive
     *
     *@params msgid int the id of the message to delete
     */
    public function deletefromarchive()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $msg_id   = FormUtil::getPassedValue('msgid', 0, 'POST');
        $this->deletepm('msg_stored', $msg_id);

        // Get the amount of messages within each box
        $totalarray = ModUtil::apiFunc('InterCom', 'user', 'getmessagecount', '');

        AjaxUtil::output($totalarray, false, true);
    }

    public function deletepm($boxtype='', $msg_id=0)
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        if (($msg_id == 0) || empty($boxtype) || !in_array($boxtype, array('msg_inbox', 'msg_outbox', 'msg_stored'))) {
            AjaxUtil::error($this->__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.'));
        }

        $obj['msg_id'] = $msg_id;
        $obj[$boxtype] = 0;

        $pntable = DBUtil::getTables();
        $msgcolumn = $pntable['intercom_column'];

        $where = 'WHERE ' . $msgcolumn['msg_id'] . ' =\'' . $msg_id . '\' AND ';
        if ($boxtype == 'msg_inbox' || $boxtype == 'msg_stored') {
            $where .= $msgcolumn['to_userid'] . '=' . UserUtil::getVar('uid');
        } else {
            $where .= $msgcolumn['from_userid'] . '=' . UserUtil::getVar('uid');
        }

        $res = DBUtil::updateObject($obj, 'intercom', $where, 'msg_id');
        return;
    }

    /**
     * toggle a messages status (read/unread)
     *
     *@params msgid int the id of the message to mark
     */
    public function togglestatus()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $msg_id   = FormUtil::getPassedValue('msgid', 0, 'POST');
        $boxtype  = FormUtil::getPassedValue('boxtype', '', 'POST');

        if (($msg_id == 0) || empty($boxtype)) {
            AjaxUtil::error($this->__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.'));
        }

        $message = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype' => $boxtype,
                'msg_id'  => $msg_id));
        // remove unneeded fields
        unset($message['from_user']);
        unset($message['to_user']);
        unset($message['msg_unixtime']);

        if ($message['msg_read'] == 0) {
            $message['msg_read'] = 1;
        } else {
            $message['msg_read'] = 0;
        }

        DBUtil::updateObject($message, 'intercom', '', 'msg_id');

        // return new image
        if ($message['msg_read'] == 0) {
            // unread
        } else {
            if ($message['msg_replied'] ==  1) {
            } else {
            }
        }
        return true;
    }

    /**
     * save a message to the archive
     *
     *@params msgid int the id of the message to save
     */
    public function store()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $msg_id   = FormUtil::getPassedValue('msgid', 0, 'POST');
        if ($msg_id == 0) {
            AjaxUtil::error($this->__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.'));
        }

        ModUtil::apiFunc('InterCom', 'user', 'store', array('msg_id' => $msg_id));

        // Get the amount of messages within each box
        $totalarray = ModUtil::apiFunc('InterCom', 'user', 'getmessagecount', '');

        AjaxUtil::output($totalarray, false, true);
    }

    /**
     * getusers
     * performs a user search based on the keyword entered so far
     *
     * @author Frank Schummertz
     * @param keyword string the fragment of the username entered
     * @return void nothing, direct ouptut using echo!
     */
    public function getusers()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $keyword = FormUtil::getPassedValue('keyword', '', 'POST');
        if (empty($keyword)) {
            AjaxUtil::error($this->__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.'));
        }

        $pntable     = DBUtil::getTables();
        $userscolumn = $pntable['users_column'];

        $where = 'WHERE ' . $userscolumn['uname'] . ' LIKE \'' . DataUtil::formatForStore($keyword) . '%\' AND '.$userscolumn['uname'].' NOT LIKE \'Anonymous\'';
        $orderby = 'ORDER BY ' . $userscolumn['uname'] . ' ASC';

        $countusers = DBUtil::selectObjectCount('users', $where);
        if ($countusers < 11) {
            $users = DBUtil::selectObjectArray('users', $where, $orderby);
        } else {
            return;
        }

        if ($users === false) {
            return AjaxUtil::registerError ($this->__('Error! Could not load data.'));
        }

        $return = array();
        foreach ($users as $user) {
            $return[] = array('caption' => $user['uname'],
                    'value'   => $user['uname']);
        }

        $output = json_encode($return);

        header('HTTP/1.0 200 OK');
        echo $output;
        System::shutdown();
    }


    /**
     * getgroups
     * performs a group search based on the keyword entered so far
     *
     * @author Frank Schummertz
     * @param keyword string the fragment of the username entered
     * @return void nothing, direct ouptut using echo!
     */
    public function getgroups()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', 'MsgToGroups::', ACCESS_COMMENT)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $keyword = FormUtil::getPassedValue('keyword', '', 'POST');

        if (empty($keyword)) {
            AjaxUtil::error($this->__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.'));
        }

        $pntable = DBUtil::getTables();
        $groupscolumn = $pntable['groups_column'];

        $where = 'WHERE ' . $groupscolumn['name'] . ' REGEXP \'' . DataUtil::formatforStore($keyword) . '\'';
        if (ModUtil::getVar('Groups', 'hideclosed')) {
            $where .= " AND $groupscolumn[state] > '0'";
        }
        $orderby = 'ORDER BY ' . $groupscolumn['name'] . ' ASC';
        $groups = DBUtil::selectObjectArray('groups', $where, $orderby);

        if ($groups === false) {
            return AjaxUtil::registerError ($this->__('Error! Could not load data.'));
        }

        $return = array();
        foreach ($groups as $group) {
            $return[] = array('caption' => $group['name'],
                    'value'   => $group['name']);
        }

        // next lines taken from AjaxUtil.class.php:

        $output = json_encode($return);
        header('HTTP/1.0 200 OK');
        echo $output;
        System::shutdown();
    }

    /**
     * getmessages
     * update the message-block
          */
    public function getmessages()
    {
        $renderer = Zikula_View::getInstance('InterCom', false);
        if(System::getVar('shorturls')) {
            include_once 'lib/view/plugins/outputfilter.shorturls.php';
            $renderer->register_outputfilter('smarty_outputfilter_shorturls');
        }
        $renderer->display('ajax/getmessages.tpl');
        System::shutdown();
    }
}