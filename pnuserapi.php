<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

/**
 * This function stores a PM into the DB
 *
 * @author Chasm
 * @param  $
 * @return
 */
function InterCom_userapi_store_message($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        return LogUtil::registerPermissionError();;
    }

    $res = DBUtil::insertObject($args, 'intercom', 'msg_id');
    if ($res == false) {
        return LogUtil::registerError(__('Error! Could not send message.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
    }
    pnModAPIFunc('InterCom', 'user', 'emailnotification',array('to_uid' => $args['to_userid'], 'from_uid' => $args['from_userid'], 'subject' => $args['msg_subject']));
    return true;
}

/**
 * Send a email notification to the user over pnMail-API
 * Code is taken from pnforum-module
 *
 * @author chaos
 * @version
 * @return
 */
function InterCom_userapi_emailnotification($args)
{
    // Extract expected variables
    $to_uid   = $args['to_uid'];
    $from_uid = $args['from_uid'];
    $subject  = $args['subject'];

    // First check if the Mailer module is avaible
    if(!pnModAvailable('Mailer')) {
        return true;
    }

    // Then check if admin allowed email notifications
    $allow_emailnotification = pnModGetVar('InterCom', 'messages_allow_emailnotification');
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

    $renderer = & pnRender::getInstance('InterCom', false);
    $renderer->assign('message_from',pnUserGetVar('uname', $from_uid));
    $renderer->assign('subject', $subject);
    $renderer->assign('viewinbox', pnModURL('InterCom', 'user', 'inbox'));
    $renderer->assign('prefs', pnModURL('InterCom', 'user', 'settings'));
    $renderer->assign('url', $url);
    $renderer->assign('baseURL', pnGetBaseURL());

    $message = $renderer->fetch("intercom_user_emailnotification.htm");

    $fromname = pnModGetVar('InterCom', 'messages_fromname');
    if ($fromname == '') {
        $fromname = pnConfigGetVar('sitename');
    }

    $fromaddress = pnModGetVar('InterCom', 'messages_from_email');
    if ($fromaddress == '') {
        $fromaddress = pnConfigGetVar('adminmail');
    }

    $modinfo = pnModGetInfo(pnModGetIDFromName('InterCom'));
    $args = array( 'fromname'    => $fromname,
                   'fromaddress' => $fromaddress,
                   'toname'      => pnUserGetVar('uname', $to_uid),
                   'toaddress'   => pnUserGetVar('email', $to_uid),
                   'subject'     => pnModGetVar('InterCom', 'messages_mailsubject'),
                   'body'        => $message,
                   'headers'     => array('X-Mailer: ' . $modinfo['name'] . ' ' . $modinfo['version']));
    pnModAPIFunc('Mailer', 'user', 'sendmessage', $args);
    return true;
}

/**
 * Send an autoreply to the sender if recipient wants that
 *
 * @author chaos
 * @version
 * @return
 */
function InterCom_userapi_autoreply($args)
{
    // Extract expected variables
    $to_uid = $args['to_uid'];
    $from_uid = $args['from_uid'];
    $subject = $args['subject'];

    // First check if admin allowed autoreply
    $allow_autoreply = pnModGetVar('InterCom', 'messages_allow_autoreply');
    if ($allow_autoreply != 1) {
        return true;
    }

    // and read the user data incl. the attributes
    $user = DBUtil::selectObjectByID('users', $to_uid, 'uid', null, null, null, false);

    if ($user['__ATTRIBUTES__']['ic_ar'] != 1) {
        return true;
    }

    // Get the needed variables for the autoreply
    $time = date("Y-m-d H:i:s");

    pnModAPIFunc('InterCom', 'user', 'store_message', array(
                 'from_uid' => $to_uid,
                 'to_uid' => $from_uid,
                 'subject' => __('Re', $dom) . ': ' . $subject,
                 'time' => $time,
                 'message' => $user['__ATTRIBUTES__']['ic_art'],
                 'inbox' => '1',
                 'outbox' => '1',
                 'stored' => '0'
                 ));
}

/**
 * Update the user preferences
 *
 * @author chaos
 * @version
 * @return
 */
function InterCom_userapi_updateprefs()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        return false;
    }

    // Confirm authorisation code
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('InterCom', 'user', 'main'));
    }

    $uid = pnUserGetVar('uid');
    $user = DBUtil::selectObjectByID('users', $uid, 'uid', null, null, null, false);

    // Get parameters from environment
    // ic_note: email notifiaction yes/no
    // ic_ar  : autoreply yes/no
    // ic_art  : autoreply text
    $user['__ATTRIBUTES__']['ic_note'] = FormUtil::getPassedValue('intercom_email_notification');
    $user['__ATTRIBUTES__']['ic_ar']   = FormUtil::getPassedValue('intercom_autoreply');
    $user['__ATTRIBUTES__']['ic_art']  = FormUtil::getPassedValue('intercom_autoreply_text');

    // store attributes
    DBUtil::updateObject($user, 'users', '', 'uid');

    // delete entry in the old intercom_userprefs table if the table exists
    $tbls = DBUtil::metaTables();
    // if old intercom_userprefs table exists, try to delete the values for user $uid
    if (in_array('intercom_userprefs', $tbls)) {
        DBUtil::deleteObjectByID('intercom_userprefs', $uid, 'user_id');
    }

    // report configuration updated
    LogUtil::registerStatus(__('Done! Saved your settings changes.', $dom));
    return true;
}

/**
 * delete a private message
 *
 * @author Chasm
 * @param  $
 * @return
 */
function InterCom_userapi_delete($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        return LogUtil::registerPermissionError();;
    }

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if ((!isset($args['msg_id']) || !is_numeric($args['msg_id'])) ||
    (!in_array($args['msg_type'], array('msg_inbox', 'msg_outbox', 'msg_stored')))) {
        return LogUtil::registerArgsError;
    }

    $obj['msg_id'] = $args['msg_id'];
    $obj[$args['msg_type']] = 0;

    $pntable = pnDBGetTables();
    $msgcolumn = $pntable['intercom_column'];

    $where = 'WHERE ' . $msgcolumn['msg_id'] . ' =\'' . $args['msg_id'] . '\' AND ';
    if ($args['msg_type'] == 'msg_inbox' || $args['msg_type'] == 'msg_stored') {
        $where .= $msgcolumn['to_userid'] . '=' . pnUserGetVar('uid');
    } else {
        $where .= $msgcolumn['from_userid'] . '=' . pnUserGetVar('uid');
    }

    $res = DBUtil::updateObject($obj, 'intercom', $where, 'msg_id');
    if ($res === false) {
        return LogUtil::registerError(__('Error! Could not delete message.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
    }

    return pnModAPIFunc('InterCom', 'user', 'optimize_db');
}

/**
 * This function stores a private message from the inbox within the archive
 *
 * @author Chasm
 * @param  $
 * @return
 */
function InterCom_userapi_store($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
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

    $pntable = pnDBGetTables();
    $msgcolumn = $pntable['intercom_column'];
    $where = 'WHERE ' . $msgcolumn['msg_id'] .'=' . $msg_id .
              ' AND ' . $msgcolumn['to_userid'] .'=' . pnUserGetVar('uid');

    $res = DBUtil::updateObject($obj, 'intercom', $where, 'msg_id');
    if ($res === false) {
        return LogUtil::registerError(__('Error! Could not save message.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
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
function InterCom_userapi_getmessagecount()
{
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();;
    }

    // Get DB
    $pntable = pnDBGetTables();
    $messagestable  = $pntable['intercom'];
    $messagescolumn = $pntable['intercom_column'];

    $uid = pnUserGetVar('uid');
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

    $res1 = DBUtil::executeSQL($sql_1);
    list ($totalarchive, $totalin, $read, $msg_popup) = $res1->fields;;
    $res2 = DBUtil::executeSQL($sql_2);
    $totalout = $res2->fields[0];
    $unread = $totalin - $read;
    $popup = $totalin - $msg_popup;

    // prepare return variables
    $limitin = pnModGetVar('InterCom', 'messages_limitinbox');
    $limitout = pnModGetVar('InterCom', 'messages_limitoutbox');
    $limitarchive = pnModGetVar('InterCom', 'messages_limitarchive');

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

    $limitindivider = 100 / $limitin;
    $indicatorbarin = $totalin * $limitindivider;
    $indicatorbarin = round($indicatorbarin, 0);

    $limitoutdivider = 100 / $limitout;
    $indicatorbarout = $totalout * $limitoutdivider;
    $indicatorbarout = round($indicatorbarout, 0);

    $limitarchivedivider = 100 / $limitarchive;
    $indicatorbararchive = $totalarchive * $limitarchivedivider;
    $indicatorbararchive = round($indicatorbararchive, 0);
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
function InterCom_userapi_getmessages($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        return LogUtil::registerPermissionError();;
    }

    // Get DB
    $pntable = pnDBGetTables();
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
                AND ' .$messagescolumn[$usertype] .'=' . DataUtil::formatforStore(pnUserGetVar('uid'));
    if (isset($args['msg_id'])) {
        // if msg_id is set, read a single message only
        $where .= ' AND ' .$messagescolumn['msg_id'] .'='. DataUtil::formatForStore($args['msg_id']);
    }

    $objarray = DBUtil::selectObjectArray('intercom', $where, $orderby, $startnum, $perpage);

    // add msg_unixtime to the arrays
    if (is_array($objarray)) {
        $keys = array_keys($objarray);
        foreach($keys as $key) {
            $objarray[$key]['msg_unixtime'] = GetUserTime(strtotime($objarray[$key]['msg_time']));
            $objarray[$key]['from_user']    = pnUserGetVar('uname', $objarray[$key]['from_userid'], __('*Deleted user*', $dom));
            $objarray[$key]['to_user']      = pnUserGetVar('uname', $objarray[$key]['to_userid'], __('*Deleted user*', $dom));
            $objarray[$key]['signature']    = pnUserGetVar('_SIGNATURE', $objarray[$key]['from_userid'], '');
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
function InterCom_userapi_optimize_db()
{
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        return LogUtil::registerPermissionError();;
    }
    // Get DB
    $pntable = pnDBGetTables();
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
function InterCom_userapi_mark_read($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
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
function InterCom_userapi_mark_replied($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
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
function InterCom_userapi_mark_popup($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();;
    }

    if (!isset($args['to_userid']) || !is_numeric($args['to_userid'])) {
        return LogUtil::registerArgsError;
    }

    $pntable = pnDBGetTables();
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
function InterCom_userapi_prepmessage_for_display($args)
{
    $msg_text = nl2br($args['msg_text']);
    $msg_text = DataUtil::formatforDisplayHTML(stripslashes($msg_text));

    list($msg_text) = pnModCallHooks('item', 'transform', '', array($msg_text));
    return $msg_text;
}

/**
 * This function prepares a message for the textarea form
 *
 * @author Chasm
 * @param  $
 * @return
 */
function InterCom_userapi_prepmessage_for_form($args)
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
function InterCom_userapi_prepurl_for_display($args)
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
 * create hook
 *
 *
 */
function InterCom_userapi_createhook($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Argument check
    if ((!isset($args['objectid'])) ||
    (!isset($args['extrainfo']))) {
        return LogUtil::registerArgsError;
    }

    // see if we have to turn on the e-mail notification
    $forceemailnotification = pnModGetVar('InterCom', 'messages_force_emailnotification', false);
    $user = DBUtil::selectObjectByID('users', $args['objectid'], 'uid', null, null, null, false);
    if ($forceemailnotification == true) {
        $user['__ATTRIBUTES__']['ic_note'] = 1;
        // store attributes
        DBUtil::updateObject($user, 'users', '', 'uid');
    }

    $welcomemessage        = pnModGetVar('InterCom', 'messages_welcomemessage');
    $welcomemessagesubject = pnModGetVar('InterCom', 'messages_welcomemessagesubject');
    $savewelcomemessage    = pnModGetVar('InterCom', 'messages_savewelcomemessage');

    // create the welcome message, uid = objectid
    Loader::loadClass('StringUtil');
    if (StringUtil::left($welcomemessage, 1) == '_') {
        $welcomemessage = constant($welcomemessage);
    }
    if (StringUtil::left($welcomemessagesubject, 1) == '_') {
        $welcomemessagesubject = constant($welcomemessagesubject);
    }

    // replace placeholders
    $welcomemessage = str_replace('%username%', $user['uname'], $welcomemessage);
    $welcomemessage = str_replace('%realname%', $user['_UREALNAME'], $welcomemessage);
    $welcomemessage = str_replace('%sitename%', pnConfigGetVar('sitename'), $welcomemessage);
    $welcomemessagesubject = str_replace('%username%', $user['uname'], $welcomemessagesubject);
    $welcomemessagesubject = str_replace('%realname%', $user['_UREALNAME'], $welcomemessagesubject);
    $welcomemessagesubject = str_replace('%sitename%', pnConfigGetVar('sitename'), $welcomemessagesubject);

    $time = date("Y-m-d H:i:s");

    // store message
    $obj =  array ('from_userid' => pnUserGetIDFromName(pnModGetVar('InterCom', 'messages_welcomemessagesender')),
                   'to_userid' => $user['uid'],
                   'msg_subject' => $welcomemessagesubject,
                   'msg_time' => $time,
                   'msg_text' => $welcomemessage,
                   'msg_inbox' => '1',
                   'msg_outbox' => ($savewelcomemessage==true) ? '1' : '0',
                   'msg_stored' => '0');

    $res = DBUtil::insertObject($obj, 'intercom', 'msg_id');

    return true;
}

/**
 * getposterdata
 * reads the posters data and fakes them if the poster has been deleted in the meantime
 *
 *@params $uid   int the user id
 *
 */
function InterCom_userapi_getposterdata($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    if (!isset($args['uid']) || empty($args['uid']) || !is_numeric($args['uid'])) {
        return LogUtil::registerArgsError;
    }

    $posterdata = pnUserGetVars($args['uid']);
    if ($posterdata == false || empty($posterdata)) {
        $posterdata = pnUserGetVars(1);
        $posterdata['uname']    = __('*Deleted user*', $dom);
        $posterdata['pn_uname'] = __('*Deleted user*', $dom);
    }
    return $posterdata;
}
