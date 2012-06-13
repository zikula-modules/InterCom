<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

class InterCom_Api_User extends Zikula_AbstractApi
{
    /**
     * This function stores a PM into the DB
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function store_message($args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();;
        }

	$res = DBUtil::insertObject($args, 'intercom', 'msg_id');
        if ($res == false) {
            return LogUtil::registerError($this->__('Error! Could not send message.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
        }
        ModUtil::apiFunc('InterCom', 'user', 'emailnotification',array('to_uid' => $args['to_userid'], 'from_uid' => $args['from_userid'], 'subject' => $args['msg_subject']));
        return true;
    }

    /**
     * Send a email notification to the user over System::mail-API
     * Code is taken from pnforum-module
     *
     * @author chaos
     * @version
     * @return
     */
    public function emailnotification($args)
    {
        // Extract expected variables
        $to_uid   = $args['to_uid'];
        $from_uid = $args['from_uid'];
        $subject  = $args['subject'];

        // First check if the Mailer module is avaible
        if(!ModUtil::available('Mailer')) {
            return true;
        }

        // Then check if admin allowed email notifications
        $allow_emailnotification = ModUtil::getVar('InterCom', 'messages_allow_emailnotification');
        if ($allow_emailnotification != 1) {
            return true;
        }

        // check the user attributes for userprefs
        $user = DBUtil::selectObjectByID('users', $to_uid, 'uid', null, null, null, false);
        if (!is_array($user)){
            // WTF, no user data?
            return true;
        }

        if (!isset($user['__ATTRIBUTES__']) || (!isset($user['__ATTRIBUTES__']['ic_note'])
                        && !isset($user['__ATTRIBUTES__']['ic_ar'])
                        && !isset($user['__ATTRIBUTES__']['ic_art']))) {
            // ic_note: email notifiaction yes/no
            // ic_ar  : autoreply yes/no
            // ic_art : autoreply text
            // load values from userprefs tables and store them in attributes
            // get all tables from the database, tbls is a non-assoc array
            $tbls = DBUtil::metaTables();
            // if old intercom_userprefs table exists, try to read the values for user $to_uid
            $olduserprefs = in_array('intercom_userprefs', $tbls);
            if ($olduserprefs == true) {
                $userprefs = DBUtil::selectObjectByID('intercom_userprefs', $to_uid, 'user_id');
            }
            if (is_null($userprefs)) {
                // userprefs table does not exist or userprefs for this user do not exist, create them with defaults
                $user['__ATTRIBUTES__']['ic_note'] = 0;
                $user['__ATTRIBUTES__']['ic_ar']   = 0;
                $user['__ATTRIBUTES__']['ic_art']  = '';
            } else {
                $user['__ATTRIBUTES__']['ic_note'] = $userprefs['email_notification'];
                $user['__ATTRIBUTES__']['ic_ar']   = $userprefs['autoreply'];
                $user['__ATTRIBUTES__']['ic_art']  = $userprefs['autoreply_text'];
            }
            // store attributes
            DBUtil::updateObject($user, 'users', '', 'uid');
            // delete entry in userprefs table
            if ($olduserprefs == true) {
                DBUtil::deleteObjectByID('intercom_userprefs', $to_uid, 'user_id');
            }
        }

        if ($user['__ATTRIBUTES__']['ic_note'] != 1) {
            return true;
        }

        // Get the needed variables for the mail

        $renderer = Zikula_View::getInstance('InterCom', false);
        $renderer->assign('message_from',UserUtil::getVar('uname', $from_uid));
        $renderer->assign('subject', $subject);
        $renderer->assign('viewinbox', ModUtil::url('InterCom', 'user', 'inbox'));
        $renderer->assign('prefs', ModUtil::url('InterCom', 'user', 'settings'));
        $renderer->assign('url', $url);
        $renderer->assign('baseURL', System::getBaseUrl());

        $message = $renderer->fetch("mail/emailnotification.tpl");

        $fromname = ModUtil::getVar('InterCom', 'messages_fromname');
        if ($fromname == '') {
            $fromname = System::getVar('sitename');
        }

        $fromaddress = ModUtil::getVar('InterCom', 'messages_from_email');
        if ($fromaddress == '') {
            $fromaddress = System::getVar('adminmail');
        }

        $modinfo = ModUtil::getInfo(ModUtil::getIdFromName('InterCom'));
        $args = array( 'fromname'    => $fromname,
                'fromaddress' => $fromaddress,
                'toname'      => UserUtil::getVar('uname', $to_uid),
                'toaddress'   => UserUtil::getVar('email', $to_uid),
                'subject'     => ModUtil::getVar('InterCom', 'messages_mailsubject'),
                'body'        => $message,
                'headers'     => array('X-Mailer: ' . $modinfo['name'] . ' ' . $modinfo['version']));
        ModUtil::apiFunc('Mailer', 'user', 'sendmessage', $args);
        return true;
    }

    /**
     * Send an autoreply to the sender if recipient wants that
     *
     * @author chaos
     * @version
     * @return
     */
    public function autoreply($args)
    {
        // Extract expected variables
        $to_uid = $args['to_uid'];
        $from_uid = $args['from_uid'];
        $subject = $args['subject'];

        // First check if admin allowed autoreply
        $allow_autoreply = ModUtil::getVar('InterCom', 'messages_allow_autoreply');
        if ($allow_autoreply != 1) {
            return true;
        }

        // and read the user data incl. the attributes
        $user = UserUtil::getVars($to_uid);

        if ($user['__ATTRIBUTES__']['ic_ar'] != 1) {
            return true;
        }

        // Get the needed variables for the autoreply
        $time = date("Y-m-d H:i:s");
        $this->store_message( array(
                'from_userid' => $to_uid,
                'to_userid' => $from_uid,
                'msg_subject' => $this->__('Re') . ': ' . $subject,
                'msg_time' => $time,
                'msg_text' => $user['__ATTRIBUTES__']['ic_art'],
                'msg_inbox' => '1',
                'msg_outbox' => '1',
                'msg_stored' => '0'
        ));
    }

    /**
     * Update the user preferences
     *
     * @author chaos
     * @version
     * @return
     */
    public function updateprefs()
    {
        // Security check - important to do this as early on as possible to
        // avoid potential security holes or just too much wasted processing
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            return false;
        }

        // Confirm authorisation code
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('InterCom', 'user', 'main'));
        }

        $uid = UserUtil::getVar('uid');

        // Get parameters from environment
        // ic_note: email notifiaction yes/no
        // ic_ar  : autoreply yes/no
        // ic_art  : autoreply text
        // store attributes
        UserUtil::setVar('ic_note', FormUtil::getPassedValue('intercom_email_notification'), $uid);
        UserUtil::setVar('ic_ar', FormUtil::getPassedValue('intercom_autoreply'), $uid);
        UserUtil::setVar('ic_art', FormUtil::getPassedValue('intercom_autoreply_text'), $uid);

        // delete entry in the old intercom_userprefs table if the table exists
        $tbls = DBUtil::metaTables();
        // if old intercom_userprefs table exists, try to delete the values for user $uid
        if (in_array('intercom_userprefs', $tbls)) {
            DBUtil::deleteObjectByID('intercom_userprefs', $uid, 'user_id');
        }

        // report configuration updated
        LogUtil::registerStatus($this->__('Done! Saved your settings changes.'));
        return true;
    }

    /**
     * delete a private message
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function delete($args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();;
        }

        // Argument check - make sure that all required arguments are present, if
        // not then set an appropriate error message and return
        if ((!isset($args['msg_id']) || !is_numeric($args['msg_id'])) ||
                (!in_array($args['msg_type'], array('msg_inbox', 'msg_outbox', 'msg_stored')))) {
            return LogUtil::registerArgsError();
        }

        $obj['msg_id'] = $args['msg_id'];
        $obj[$args['msg_type']] = 0;

        $pntable = DBUtil::getTables();
        $msgcolumn = $pntable['intercom_column'];

        $where = 'WHERE ' . $msgcolumn['msg_id'] . ' =\'' . $args['msg_id'] . '\' AND ';
        if ($args['msg_type'] == 'msg_inbox' || $args['msg_type'] == 'msg_stored') {
            $where .= $msgcolumn['to_userid'] . '=' . UserUtil::getVar('uid');
        } else {
            $where .= $msgcolumn['from_userid'] . '=' . UserUtil::getVar('uid');
        }

        $res = DBUtil::updateObject($obj, 'intercom', $where, 'msg_id');
        if ($res === false) {
            return LogUtil::registerError($this->__('Error! Could not delete message.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
        }

        return ModUtil::apiFunc('InterCom', 'user', 'optimize_db');
    }

    /**
     * This function stores a private message from the inbox within the archive
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function store($args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();;
        }

        // Extract expected variables
        $msg_id = $args['msg_id'];

        // Argument check - make sure that all required arguments are present, if
        // not then set an appropriate error message and return
        if (!isset($msg_id) || !is_numeric($msg_id)) {
            return LogUtil::registerArgsError;
        }

        $obj['msg_id'] = $msg_id;
        $obj['msg_stored'] = 1;
        $obj['msg_inbox'] = 0;

        $pntable = DBUtil::getTables();
        $msgcolumn = $pntable['intercom_column'];
        $where = 'WHERE ' . $msgcolumn['msg_id'] .'=' . $msg_id .
                ' AND ' . $msgcolumn['to_userid'] .'=' . UserUtil::getVar('uid');

        $res = DBUtil::updateObject($obj, 'intercom', $where, 'msg_id');
        if ($res === false) {
            return LogUtil::registerError($this->__('Error! Could not save message.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
        }

        return true;
    }

    /**
     * This function returns the amount of Messages within the inbox, outbox, and the archives
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function getmessagecount()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();;
        }

        // Get DB
        $pntable = DBUtil::getTables();
        $messagestable  = $pntable['intercom'];
        $messagescolumn = $pntable['intercom_column'];

        $uid = UserUtil::getVar('uid');
        // Get items - prepare queries
        $sql_1 = "SELECT sum( $messagescolumn[msg_stored] ) msg_stored,
                     sum( $messagescolumn[msg_inbox] ) msg_inbox,
                     sum(CASE WHEN $messagescolumn[msg_inbox] = 1 THEN $messagescolumn[msg_read] ELSE 0 END) read_msg,
                     sum(CASE WHEN $messagescolumn[msg_inbox] = 1 THEN $messagescolumn[msg_popup] ELSE 0 END) msg_popup
              FROM $messagestable
              WHERE $messagescolumn[to_userid] = $uid";

        $sql_2 = "SELECT count($messagescolumn[msg_id])
              FROM   $messagestable
              WHERE  $messagescolumn[msg_outbox]  = '1'
              AND    $messagescolumn[from_userid] = $uid";

        $res1 = DBUtil::marshallObjects(DBUtil::executeSQL($sql_1),
                array('totalarchive', 'totalin', 'read', 'msg_popup') );
        if (is_array($res1[0])) extract($res1[0]);

        $res2 = DBUtil::marshallObjects(DBUtil::executeSQL($sql_2),
                array('totalout'));
        $totalout = $res2[0]['totalout'];
        $unread = $totalin - $read;
        $popup = $totalin - $msg_popup;

        // prepare return variables
        $limitin = ModUtil::getVar('InterCom', 'messages_limitinbox');
        $limitout = ModUtil::getVar('InterCom', 'messages_limitoutbox');
        $limitarchive = ModUtil::getVar('InterCom', 'messages_limitarchive');

        if (empty($totalin)) {
            $totalin = 0;
        }
        if (empty($totalout)) {
            $totalout = 0;
        }
        if (empty($totalarchive)) {
            $totalarchive = 0;
        }

        if ($totalin >= $limitin) {
            $inboxlimitreached = true;
            $inboxlimitclass = 'ic-limitreached';
        } else {
            $inboxlimitreached = false;
            $inboxlimitclass = 'ic-limitnotreached';
        }
        if ($totalout >= $limitout) {
            $outboxlimitreached = true;
            $outboxlimitclass = 'ic-limitreached';
        } else {
            $outboxlimitreached = false;
            $outboxlimitclass = 'ic-limitnotreached';
        }
        if ($totalarchive >= $limitarchive) {
            $archivelimitreached = true;
            $archivelimitclass = 'ic-limitreached';
        } else {
            $archivelimitreached = false;
            $archivelimitclass = 'ic-limitnotreached';
        }

        $indicatorbarin = round(($totalin / $limitin) * 100);
        $indicatorbarout = round(($totalout / $limitout) * 100);
        $indicatorbararchive = round(($totalarchive / $limitarchive) * 100);
        // form a variable to return
        $ReturnArray = array();

        $ReturnArray['unread'] = $unread;
        $ReturnArray['popup'] = $popup;
        $ReturnArray['totalin'] = $totalin;
        $ReturnArray['totalout'] = $totalout;
        $ReturnArray['totalarchive'] = $totalarchive;

        $ReturnArray['limitin'] = $limitin;
        $ReturnArray['limitout'] = $limitout;
        $ReturnArray['limitarchive'] = $limitarchive;

        $ReturnArray['inboxlimitreached'] = $inboxlimitreached;
        $ReturnArray['outboxlimitreached'] = $outboxlimitreached;
        $ReturnArray['archivelimitreached'] = $archivelimitreached;

        $ReturnArray['inboxlimitclass'] = $inboxlimitclass;
        $ReturnArray['outboxlimitclass'] = $outboxlimitclass;
        $ReturnArray['archivelimitclass'] = $archivelimitclass;

        $ReturnArray['indicatorbarin'] = $indicatorbarin;
        $ReturnArray['indicatorbarout'] = $indicatorbarout;
        $ReturnArray['indicatorbararchive'] = $indicatorbararchive;

        // Return the variable
        return $ReturnArray;
    }


    /**
     * This function returns all details for a specific PM or an array of PMs
     *
     * @author Landseer
     * @param  $
     * @return
     */
    public function getmessages($args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();;
        }

        // Get DB
        $pntable = DBUtil::getTables();
        // define tables and columns
        $messagescolumn = $pntable['intercom_column'];

        // Extract expected variables
        $boxtype = $args['boxtype'];  // msg_inbox, msg_outbox or msg_stored
        switch ($boxtype) {
            case 'msg_inbox':
            case 'msg_stored':
                $usertype     = 'to_userid';
                $sortusertype = 'from_userid';
                break;
            case 'msg_outbox':
                $usertype     = 'from_userid';
                $sortusertype = 'to_userid';
                break;
            default:
                return LogUtil::registerArgsError;
        }

        // 1 = username ASC, 2=username DESC, 3=date ASC, 4=date DESC
        if (isset($args['orderby'])) {
            switch ((int)$args['orderby']) {
                case 1:
                    $orderby = 'ORDER BY ' . $messagescolumn[$sortusertype] .' DESC';
                    break;
                case 2:
                    $orderby = 'ORDER BY ' . $messagescolumn[$sortusertype] .' ASC';
                    break;
                case 3:
                    $orderby = 'ORDER BY ' . $messagescolumn['msg_time'] .' DESC';
                    break;
                case 4:
                    $orderby = 'ORDER BY ' . $messagescolumn['msg_time'] .' ASC';
                    break;
                case 5:
                    $orderby = 'ORDER BY ' . $messagescolumn['msg_subject'] .' DESC';
                    break;
                case 6:
                    $orderby = 'ORDER BY ' . $messagescolumn['msg_subject'] .' ASC';
                    break;
                default:
                    $orderby = '';
            }
        } else {
            $orderby = '';
        }

        $startnum = isset($args['startnum']) && is_numeric($args['startnum']) ? $args['startnum'] : -1;
        $perpage  = isset($args['perpage']) && is_numeric($args['perpage']) ? $args['perpage'] : -1;

        $where = 'WHERE ' .$messagescolumn[$boxtype] .'=1
                AND ' .$messagescolumn[$usertype] .'=' . DataUtil::formatforStore(UserUtil::getVar('uid'));
        if (isset($args['msg_id'])) {
            // if msg_id is set, read a single message only
            $where .= ' AND ' .$messagescolumn['msg_id'] .'='. DataUtil::formatForStore($args['msg_id']);
        }

        $objarray = DBUtil::selectObjectArray('intercom', $where, $orderby, $startnum, $perpage);

        // add msg_unixtime to the arrays
        if (is_array($objarray)) {
            $keys = array_keys($objarray);
            foreach($keys as $key) {
                $objarray[$key]['msg_unixtime'] = getusertime*DEPRECATED*(strtotime($objarray[$key]['msg_time']));
                $objarray[$key]['from_user']    = UserUtil::getVar('uname', $objarray[$key]['from_userid'], $this->__('*Deleted user*'));
                $objarray[$key]['to_user']      = UserUtil::getVar('uname', $objarray[$key]['to_userid'], $this->__('*Deleted user*'));
                $objarray[$key]['signature']    = UserUtil::getVar('_SIGNATURE', $objarray[$key]['from_userid'], '');
            }
        }
        if (isset($args['msg_id'])) {
            if(is_array($objarray)) {
                return $objarray[0];
            }
            return false;
        }
        return $objarray;
    }

    /**
     * This function deletes all messages wich are not stored in any boxes
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function optimize_db()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();;
        }
        // Get DB
        $pntable = DBUtil::getTables();
        $messagescolumn = $pntable['intercom_column'];
        $where = "WHERE   $messagescolumn[msg_inbox]  = '0'
                AND   $messagescolumn[msg_outbox] = '0'
                AND   $messagescolumn[msg_stored] = '0'";
        DBUtil::deleteWhere('intercom', $where);
        return true;
    }

    /**
     * This function marks a message as read
     *
     * @author Chasm
     * @param  $msg_id int the messge id
     * @return
     */
    public function mark_read($args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();;
        }

        if (!isset($args['msg_id']) || !is_numeric($args['msg_id'])) {
            return LogUtil::registerArgsError;
        }

        $args['msg_read'] = 1;
        DBUtil::updateObject($args, 'intercom', '', 'msg_id');
        return true;
    }

    /**
     * This function marks a message as replied
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function mark_replied($args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();;
        }

        if (!isset($args['msg_id']) || !is_numeric($args['msg_id'])) {
            return LogUtil::registerArgsError;
        }

        $args['msg_replied'] = 1;
        DBUtil::updateObject($args, 'intercom', '', 'msg_id');
        return true;
    }

    /**
     * This function marks a message as already popped up
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function mark_popup($args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();;
        }

        if (!isset($args['to_userid']) || !is_numeric($args['to_userid'])) {
            return LogUtil::registerArgsError();
        }

        $pntable = DBUtil::getTables();
        $messagescolumn = $pntable['intercom_column'];

        $args['msg_popup'] = 1;
        $where = 'WHERE '.$messagescolumn['to_userid']."=".DataUtil::formatForStore($args['to_userid']);
        DBUtil::updateObject($args, 'intercom', $where, 'msg_id');
        return true;
    }

    /**
     * This function prepares a message for display
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function prepmessage_for_display($args)
    {
        $msg_text = nl2br($args['msg_text']);
        $msg_text = DataUtil::formatforDisplayHTML(stripslashes($msg_text));

     // TODO  list($msg_text) = ModUtil::callHooks('item', 'transform', '', array($msg_text));
        return $msg_text;
    }

    /**
     * This function prepares a message for the textarea form
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function prepmessage_for_form($args)
    {
        $msg_text = stripslashes(str_replace(array('<br />', '<br>', '<BR>'), "\n", $args['msg_text']));
        return $msg_text;
    }

    /**
     * This function prepares a url for display
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function prepurl_for_display($args)
    {
        $url = $args['url'];
        if ($url != '') {
            if ($url == 'http://' || $url == 'http://http://' || $url == 'http:///') {
                $url = '';
            } elseif (strstr("http://", $url)) {
                $url = "http://" . $url;
            }
        }
        return $url;
    }

    /**
     * getposterdata
     * reads the posters data and fakes them if the poster has been deleted in the meantime
     *
     *@params $uid   int the user id
     *
     */
    public function getposterdata($args)
    {
        if (!isset($args['uid']) || empty($args['uid']) || !is_numeric($args['uid'])) {
            return LogUtil::registerArgsError();
        }

        $posterdata = UserUtil::getVars($args['uid']);
        if ($posterdata == false || empty($posterdata)) {
            $posterdata = UserUtil::getVars(1);
            $posterdata['uname']    = $this->__('*Deleted user*');
        }
        return $posterdata;
    }
}