<?php
/**
* $Id$
*
* InterCom - an advanced private messaging solution for Zikula
*
* License
* -------
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License (GPL)
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* @author InterCom development team
* @link http://code.zikula.org/intercom/ Support and documentation
* @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
*
*/

/**
* mark a message as read
*
*/
function InterCom_ajax_markmsgread()
{
    $msg_id = (int)FormUtil::getPassedValue('msgid', 0, 'POST');
    pnModAPIFunc('InterCom', 'user', 'mark_read', array('msg_id' => $msg_id));
    return true;
}

/**
* mark a message as answered
*
*/
function InterCom_ajax_markmsganswered()
{
    $msg_id = (int)FormUtil::getPassedValue('msgid', 0, 'POST');
    pnModAPIFunc('InterCom', 'user', 'mark_replied', array('msg_id' => $msg_id));
    return true;
}

/**
* reply to a message from the inbox
*
*@params msgid int the id of the message to reply to
*/
function InterCom_ajax_replyfrominbox()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $msg_id = (int)FormUtil::getPassedValue('msgid', 0, 'POST');

    if ($msg_id <= 0) {
        AjaxUtil::error(__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.', $dom));
    }

    $message = pnModAPIFunc('InterCom', 'user', 'getmessages',
                            array('boxtype'  => 'msg_inbox',
                                  'msg_id'   => $msg_id));

    if($message['from_user'] == __('*Deleted user*', $dom)) {
        AjaxUtil::error('unknownuser', 404);
    }

    // Create output object
    $pnRender = & pnRender::getInstance('InterCom', false, null, true);

    $bbsmile = pnModIsHooked('bbsmile', 'InterCom');
    $bbcode  = pnModIsHooked('bbcode',  'InterCom');
    $message['reply_text'] = DataUtil::formatForDisplay($message['msg_text']);

    // replace [addsig] with users signature
    $signature = pnUserGetVar('_SIGNATURE', $message['from_userid']);
    if (!empty($signature)){
        $message['reply_text'] = eregi_replace("\[addsig]$", "\n\n" . $signature , $message['reply_text']);
    } else {
        $message['reply_text'] = eregi_replace("\[addsig]$", '', $message['reply_text']);
    }
    if ($bbcode == true) {
        $message['reply_text'] = '[quote=' . pnUserGetVar('uname', $message['from_userid']) . ']' . $message['reply_text'] . '[/quote]';
    }

    $pnRender->assign('message', $message);
    $pnRender->assign('allowsmilies', $bbsmile);
    $pnRender->assign('allowbbcode',  $bbcode);

    // no output in xjsonheader as this might be too long for prototype!
    AjaxUtil::output(array('data' => $pnRender->fetch('intercom_ajax_reply.htm')));
}

/**
* send a reply
*
*@params
*@params
*/
function InterCom_ajax_sendreply()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $replyto = FormUtil::getPassedValue('replyto', 0, 'POST');
    $subject = FormUtil::getPassedValue('subject', '', 'POST');
    $message = FormUtil::getPassedValue('message', '', 'POST');

    $boxtype = FormUtil::getPassedValue('boxtype', 'inbox', 'POST');
    $btarray = array('inbox' => 'msg_inbox', 'outbox' => 'msg_outbox', 'archive' => 'msg_stored');
    $boxtype = $btarray[$boxtype];

    if (empty($subject)) {
        AjaxUtil::error(__('Error! Could not find the subject line for the message. Please check your input and try again.', $dom));
    }

    if (pnModGetVar('InterCom', 'messages_allowhtml') == 0) {
        $message = strip_tags($message);
    }
	
	if (pnModGetVar('InterCom', 'messages_allowsmilies') == 0) {
        $message = strip_tags($message);
    }
	
    if (empty($message)) {
        AjaxUtil::error(__('Error! Could not find the message text. Please check your input and try again.', $dom));
    }

    $replytomessage = pnModAPIFunc('InterCom', 'user', 'getmessages',
                                   array('boxtype'  => $boxtype,
                                         'msg_id'   => $replyto));
    //$message .= "[addsig]";
    $time = date("Y-m-d H:i:s");

    $from_uid = pnUserGetVar('uid');
    if ($replyto <> 0) {
      pnModAPIFunc('InterCom', 'user', 'mark_replied',
                   array ('msg_id' => $replyto));
    }

    pnModAPIFunc('InterCom', 'user', 'store_message',
                 array('from_userid' => $from_uid,
                       'to_userid' => $replytomessage['from_userid'],
                       'msg_subject' => $subject,
                       'msg_time' => $time,
                       'msg_text' => $message,
                       'msg_inbox' => '1',
                       'msg_outbox' => '1',
                       'msg_stored' => '0'));
    pnModAPIFunc('InterCom', 'user', 'autoreply',
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
function InterCom_ajax_sendforward()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $forwardto = FormUtil::getPassedValue('forwardto', '', 'POST');
    $subject   = FormUtil::getPassedValue('subject', '', 'POST');
    $message   = FormUtil::getPassedValue('message', '', 'POST');

    if (empty($subject)) {
        AjaxUtil::error(__('Error! Could not find the subject line for the message. Please check your input and try again.', $dom));
    }

    if (pnModGetVar('InterCom', 'messages_allowhtml') == 0) {
        $message = strip_tags($message);
    }
    if (empty($message)) {
        AjaxUtil::error(__('Error! Could not find the message text. Please check your input and try again.', $dom));
    }

    //$message .= "[addsig]";
    $time = date("Y-m-d H:i:s");

    $from_uid = pnUserGetVar('uid');
    $forwardto_uid = pnUserGetIDFromName($forwardto);

    pnModAPIFunc('InterCom', 'user', 'store_message',
                 array('from_userid' => $from_uid,
                       'to_userid' => $forwardto_uid,
                       'msg_subject' => $subject,
                       'msg_time' => $time,
                       'msg_text' => $message,
                       'msg_inbox' => '1',
                       'msg_outbox' => '1',
                       'msg_stored' => '0'));
    pnModAPIFunc('InterCom', 'user', 'autoreply',
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
function InterCom_ajax_forwardfrominbox()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $msg_id = (int)FormUtil::getPassedValue('msgid', 0, 'POST');

    if ($msg_id <= 0) {
        AjaxUtil::error(__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.', $dom));
    }

    $message = pnModAPIFunc('InterCom', 'user', 'getmessages',
                            array('boxtype'  => 'msg_inbox',
                                  'msg_id'   => $msg_id));

    // Create output object
    $pnRender = & pnRender::getInstance('InterCom', false);

    $bbcode = pnModIsHooked('bbcode', 'InterCom');
    $message['forward_text'] = DataUtil::formatForDisplay($message['msg_text']);
    if ($bbcode == true) {
        $message['forward_text'] = '[quote=' . pnUserGetVar('uname', $message['from_userid']) . ']' . $message['forward_text'] . '[/quote]';
    }

    $pnRender->assign('message', $message);
    $pnRender->assign('allowsmilies', pnModIsHooked('bbsmile', 'InterCom'));
    $pnRender->assign('allowbbcode', $bbcode);
    $pnRender->assign('allowhtml', pnModGetVar('InterCom', 'messages_allowhtml'));

    // no output in xjsonheader as this might be too long for prototype!
    AjaxUtil::output(array('data' => $pnRender->fetch('intercom_ajax_forward.htm')));
}

/**
* delete a message from the inbox
*
*@params msgid int the id of the message to delete
*/
function InterCom_ajax_deletefrominbox()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $msg_id = FormUtil::getPassedValue('msgid', 0, 'POST');
    InterCom_ajax_deletepm('msg_inbox', $msg_id);

    // Get the amount of messages within each box
    $totalarray = pnModAPIFunc('InterCom', 'user', 'getmessagecount', '');

    AjaxUtil::output($totalarray, false, true);
}

/**
* delete a message from the outbox
*
*@params msgid int the id of the message to delete
*/
function InterCom_ajax_deletefromoutbox()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $msg_id   = FormUtil::getPassedValue('msgid', 0, 'POST');
    InterCom_ajax_deletepm('msg_outbox', $msg_id);

    // Get the amount of messages within each box
    $totalarray = pnModAPIFunc('InterCom', 'user', 'getmessagecount', '');

    AjaxUtil::output($totalarray, false, true);
}

/**
* delete a message from the archive
*
*@params msgid int the id of the message to delete
*/
function InterCom_ajax_deletefromarchive()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $msg_id   = FormUtil::getPassedValue('msgid', 0, 'POST');
    InterCom_ajax_deletepm('msg_stored', $msg_id);

    // Get the amount of messages within each box
    $totalarray = pnModAPIFunc('InterCom', 'user', 'getmessagecount', '');

    AjaxUtil::output($totalarray, false, true);
}

function InterCom_ajax_deletepm($boxtype='', $msg_id=0)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    if (($msg_id == 0) || empty($boxtype) || !in_array($boxtype, array('msg_inbox', 'msg_outbox', 'msg_stored'))) {
        AjaxUtil::error(__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.', $dom));
    }

    $obj['msg_id'] = $msg_id;
    $obj[$boxtype] = 0;

    $pntable = pnDBGetTables();
    $msgcolumn = $pntable['intercom_column'];

    $where = 'WHERE ' . $msgcolumn['msg_id'] . ' =\'' . $msg_id . '\' AND ';
    if ($boxtype == 'msg_inbox' || $boxtype == 'msg_stored') {
        $where .= $msgcolumn['to_userid'] . '=' . pnUserGetVar('uid');
    } else {
        $where .= $msgcolumn['from_userid'] . '=' . pnUserGetVar('uid');
    }

    $res = DBUtil::updateObject($obj, 'intercom', $where, 'msg_id');
    return;
}

/**
* toggle a messages status (read/unread)
*
*@params msgid int the id of the message to mark
*/
function InterCom_ajax_togglestatus()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $msg_id   = FormUtil::getPassedValue('msgid', 0, 'POST');
    $boxtype  = FormUtil::getPassedValue('boxtype', '', 'POST');

    if (($msg_id == 0) || empty($boxtype)) {
        AjaxUtil::error(__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.', $dom));
    }

    $message = pnModAPIFunc('InterCom', 'user', 'getmessages',
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
function InterCom_ajax_store()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $msg_id   = FormUtil::getPassedValue('msgid', 0, 'POST');
    if ($msg_id == 0) {
        AjaxUtil::error(__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.', $dom));
    }

    pnModAPIFunc('InterCom', 'user', 'store', array('msg_id' => $msg_id));

    // Get the amount of messages within each box
    $totalarray = pnModAPIFunc('InterCom', 'user', 'getmessagecount', '');

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
function InterCom_ajax_getusers()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $keyword = FormUtil::getPassedValue('keyword', '', 'POST');
    if (empty($keyword)) {
        AjaxUtil::error(__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.', $dom));
    }

    $pntable     = pnDBGetTables();
    $userscolumn = $pntable['users_column'];

    $where = 'WHERE ' . $userscolumn['uname'] . ' REGEXP \'(' . DataUtil::formatForStore($keyword) . ')\' AND '.$userscolumn['uname'].' NOT LIKE \'Anonymous\'';
    $orderby = 'ORDER BY ' . $userscolumn['uname'] . ' ASC';

    $countusers = DBUtil::selectObjectCount('users', $where);
    if ($countusers < 11) {
        $users = DBUtil::selectObjectArray('users', $where, $orderby);
    } else {
        return;
    }

    if ($users === false) {
        return AjaxUtil::registerError (__('Error! Could not load data.', $dom));
    }

    $return = array();
    foreach ($users as $user) {
        $return[] = array('caption' => $user['uname'],
                          'value'   => $user['uname']);
    }

    // next lines taken from AjaxUtil.class.php:

    // correct, but wrong: check PHP version and use internal json_encode if >=5.2.0
    // better in order to satisfy some weird webhosters (like goneo - forget them) who think they know PHP better
    // than the PHP guys and install >=5.2.0 without JSON-support: check if json_encode() exists
    if (function_exists('json_encode')) {
        // found - use built-in json encoding
        $output = json_encode($return);
    } else {
        // not found - use external JSON library
        Loader::requireOnce('includes/classes/JSON/JSON.php');
        $json = new Services_JSON();
        $output = $json->encode($return);
    }

    header('HTTP/1.0 200 OK');
    echo $output;
    pnShutDown();
}


/**
* getgroups
* performs a group search based on the keyword entered so far
*
* @author Frank Schummertz
* @param keyword string the fragment of the username entered
* @return void nothing, direct ouptut using echo!
*/
function InterCom_ajax_getgroups()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', 'MsgToGroups::', ACCESS_COMMENT)) {
        AjaxUtil::error(__('Sorry! You do not have authorisation for this module.', $dom));
    }

    $keyword = FormUtil::getPassedValue('keyword', '', 'POST');

    if (empty($keyword)) {
        AjaxUtil::error(__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.', $dom));
    }

    $pntable = pnDBGetTables();
    $groupscolumn = $pntable['groups_column'];

    $where = 'WHERE ' . $groupscolumn['name'] . ' REGEXP \'' . DataUtil::formatforStore($keyword) . '\'';
    if (pnModGetVar('Groups', 'hideclosed')) {
        $where .= " AND $groupscolumn[state] > '0'";
    }
    $orderby = 'ORDER BY ' . $groupscolumn['name'] . ' ASC';
    $groups = DBUtil::selectObjectArray('groups', $where, $orderby);

    if ($groups === false) {
        return AjaxUtil::registerError (__('Error! Could not load data.', $dom));
    }

    $return = array();
    foreach ($groups as $group) {
        $return[] = array('caption' => $group['name'],
                          'value'   => $group['name']);
    }

    // next lines taken from AjaxUtil.class.php:

    // correct, but wrong: check PHP version and use internal json_encode if >=5.2.0
    // better in order to satisfy some weird webhosters (like goneo - forget them) who think they know PHP better
    // than the PHP guys and install >=5.2.0 without JSON-support: check if json_encode() exists
    if (function_exists('json_encode')) {
        // found - use built-in json encoding
        $output = json_encode($return);
    } else {
        // not found - use external JSON library
        Loader::requireOnce('includes/classes/JSON/JSON.php');
        $json = new Services_JSON();
        $output = $json->encode($return);
    }

    header('HTTP/1.0 200 OK');
    echo $output;
    pnShutDown();
}

/**
 * getmessages
 * update the message-block
 *
 */
function InterCom_ajax_getmessages ()
{
    $pnRender = & pnRender::getInstance('InterCom', false);
    if(pnConfigGetVar('shorturls')) {
        Loader::includeOnce('system/Theme/plugins/outputfilter.shorturls.php');
        $pnRender->register_outputfilter('smarty_outputfilter_shorturls');
    }
    $pnRender->display('intercom_ajax_getmessages.htm');
    pnShutDown();
}
