<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

/**
 * The main user function -
 * This function redirects to the inbox function.
 *
 * @author Chasm
 * @version 1.0
 * @return
 */
function InterCom_user_main()
{
    // This is a user only module - redirect everyone else
    // Check this before Security check - maybe after login user has enough rights
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'main')));
    }
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }
    return pnRedirect(pnModURL('InterCom', 'user', 'inbox'));
}

/**
 * Function to modify the user preferences
 *
 * @author chaos
 * @version 1.0
 * @return
 */
function InterCom_user_settings()
{
    // This is a user only module - redirect everyone else
    // Check this before Security check - maybe after login user has enough rights
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'settings')));
    }
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    // Get the uid of the user
    $uid = pnUserGetVar('uid');

    // and read the user data incl. the attributes
    $attr = pnUserGetVar('__ATTRIBUTES__', $uid);

    // Get the admin preferences
    $allow_emailnotification = pnModGetVar('InterCom', 'messages_allow_emailnotification');
    $allow_autoreply = pnModGetVar('InterCom', 'messages_allow_autoreply');

    // Create output object
    $renderer = & pnRender::getInstance('InterCom', false, null, true);
    $renderer->assign('email_notification', $attr['ic_note']);
    $renderer->assign('autoreply',          $attr['ic_ar']);
    $renderer->assign('autoreply_text',     $attr['ic_art']);
    return $renderer->fetch('intercom_user_prefs.htm');
}

/**
 * Update the user preferences
 *
 * @author chaos
 * @version 1.0
 * @param
 */
function InterCom_user_modifyprefs()
{
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    pnModAPIFunc('InterCom', 'user', 'updateprefs');
    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('InterCom', 'user', 'settings'));
}

/**
 * View inbox -
 * This function shows the inbox.
 *
 * @author Chasm
 * @version 1.0
 * @param  int $sort
 * @return
 */
function InterCom_user_inbox()
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everyone else
    // Check this before Security check - maybe after login user has enough rights
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'inbox')));
    }
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }
    // Maintenance message
    if (pnModGetVar('InterCom', 'messages_active') == 0 && !SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        $renderer = & pnRender::getInstance('InterCom', false);
        return $renderer->fetch('intercom_user_maintenance.htm');
    }

    // Get variables for autoreply
    $autoreply = 0;
    if (pnModGetVar('intercom', 'messages_allow_autoreply') == 1) {
        // and read the user data incl. the attributes
        $attr = pnUserGetVar('__ATTRIBUTES__'); DBUtil::selectObjectByID('users', pnUserGetVar('uid'), 'uid', null, null, null, false);
        $autoreply = $attr['ic_ar'];
    }

    // Get startnum and perpage parameter for pager
    $startnum = (int)FormUtil::getPassedValue('startnum', 0, 'GETPOST');
    $messagesperpage = pnModGetVar('intercom', 'messages_perpage', 25);

    // Get parameter for inboxlimit
    $inboxlimit = pnModGetVar('InterCom', 'messages_limitinbox');

    // Get parameters from whatever input we need.
    $sort = (int)FormUtil::getPassedValue('sort', 3, 'GETPOST');

    // Get the amount of messages within each box
    $totalarray = pnModAPIFunc('InterCom', 'user', 'getmessagecount', '');

    $messagearray = pnModAPIFunc('InterCom', 'user', 'getmessages',
    array('boxtype'  => 'msg_inbox',
    'orderby'  => $sort,
    'startnum' => $startnum,
    'perpage'  => $messagesperpage));

    // inline js for language defines
    InterCom_addinlinejs();

    if(pnModIsHooked('bbsmile', 'InterCom')) {
        PageUtil::addVar('javascript', 'javascript/ajax/prototype.js');
        PageUtil::addVar('javascript', 'modules/bbsmile/pnjavascript/dosmilie.js');
        PageUtil::addVar('javascript', 'modules/bbsmile/pnjavascript/control_modal.js');
        PageUtil::addVar('stylesheet', ThemeUtil::getModuleStylesheet('bbsmile'));
    }
    if(pnModIsHooked('bbcode', 'InterCom')) {
        PageUtil::addVar('javascript', 'javascript/ajax/prototype.js');
        PageUtil::addVar('javascript', 'modules/bbcode/pnjavascript/bbcode.js');
        PageUtil::addVar('stylesheet', ThemeUtil::getModuleStylesheet('bbcode'));
    }

    // Create output object
    $renderer = & pnRender::getInstance('InterCom', false, null, true);
    $renderer->assign('boxtype',          'inbox');
    $renderer->assign('currentuid',       pnUserGetVar('uid'));
    $renderer->assign('messagearray',     $messagearray);
    $renderer->assign('sortarray',        $sortarray);
    $renderer->assign('getmessagecount',  $totalarray);
    $renderer->assign('sortbar_target',   'inbox');
    $renderer->assign('messagesperpage',  $messagesperpage);
    $renderer->assign('autoreply',        $autoreply);
    $renderer->assign('sort',             $sort);
    $renderer->assign('ictitle',          DataUtil::formatForDisplay(__('Inbox', $dom)));
    // Return output object
    return $renderer->fetch('intercom_user_view.htm');
}

/**
 * View outbox -
 * This function shows the outbox.
 *
 * @author Chasm
 * @version 1.0
 * @param $sort
 * @return
 */
function InterCom_user_outbox()
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everyone else
    // Check this before Security check - maybe after login user has enough rights
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'outbox')));
    }
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }
    // Maintenance message
    if (pnModGetVar('InterCom', 'messages_active') == 0 && !SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        $renderer = & pnRender::getInstance('InterCom', false);
        return $renderer->fetch('intercom_user_maintenance.htm');
    }

    // Get startnum and perpage parameter for pager
    $startnum = (int)FormUtil::getPassedValue('startnum', 0, 'GETPOST');

    $messagesperpage = pnModGetVar('intercom', 'messages_perpage');
    if (empty ($messagesperpage) || !is_numeric($messagesperpage)) {
        $messagesperpage = 25;
    }

    // Get parameter for inboxlimit
    $outboxlimit = pnModGetVar('InterCom', 'messages_limitoutbox');

    // Get parameters from whatever input we need.
    $sort = (int)FormUtil::getPassedValue('sort', 3, 'GETPOST');

    if (!is_numeric($sort)) {
        return LogUtil::registerArgsError;
    }

    // Get the amount of messages within each box
    $totalarray = pnModAPIFunc('InterCom', 'user', 'getmessagecount', '');

    $messagearray = pnModAPIFunc('InterCom', 'user', 'getmessages',
    array('boxtype'  => 'msg_outbox',
                                       'orderby'  => $sort,
                                       'startnum' => $startnum,
                                       'perpage'  => $messagesperpage));


    for ($i = 1; $i <= $totalarray['totalout']; $i++) {
        $message = $messagearray[$i -1]['msg_id'] - 1;
        if ($messagearray[$i -1]['msg_read'] == '1' && $messagearray[$i -1]['msg_inbox'] == '1') {
            $messagearray[$i -1]['checkit_img'] = '1';
        }
        if ($messagearray[$i -1]['msg_read'] == '0' && $messagearray[$i -1]['msg_inbox'] == '1') {
            $messagearray[$i -1]['checkit_img'] = '2';
        }
        if ($messagearray[$i -1]['msg_inbox'] == '0') {
            $messagearray[$i -1]['checkit_img'] = '3';
        }
    }

    // inline js for language defines
    InterCom_addinlinejs();

    // Create output object
    $renderer = & pnRender::getInstance('InterCom', false, null, true);
    $renderer->assign('boxtype',         'outbox');
    $renderer->assign('currentuid',      pnUserGetVar('uid'));
    $renderer->assign('messagearray',    $messagearray);
    $renderer->assign('sortarray',       $sortarray);
    $renderer->assign('getmessagecount', $totalarray);
    $renderer->assign('sortbar_target',  'outbox');
    $renderer->assign('messagesperpage', $messagesperpage);
    $renderer->assign('sort',            $sort);
    $renderer->assign('ictitle',         DataUtil::formatForDisplay(__('Outbox', $dom)));
    // Return output object
    return $renderer->fetch('intercom_user_view.htm');
}

/**
 * View archive -
 * This function shows the archive.
 *
 * @author Chasm
 * @version 1.0
 * @param int $sort
 * @return
 */
function InterCom_user_archive()
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everyone else
    // Check this before Security check - maybe after login user has enough rights
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'archive')));
    }
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }
    // Maintenance message
    if (pnModGetVar('InterCom', 'messages_active') == 0 && !SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        $renderer = & pnRender::getInstance('InterCom', false);
        return $renderer->fetch('intercom_user_maintenance.htm');
    }

    // Get startnum and perpage parameter for pager
    $startnum = (int)FormUtil::getPassedValue('startnum', 0, 'GETPOST');

    $messagesperpage = pnModGetVar('intercom', 'messages_perpage');
    if (empty ($messagesperpage) || !is_numeric($messagesperpage)) {
        $messagesperpage = 25;
    }

    // Get parameters from whatever input we need.
    $sort = (int) FormUtil::getPassedValue('sort', 3, 'GETPOST');

    if (!is_numeric($sort)) {
        return LogUtil::registerArgsError;
    }

    // Get the amount of messages within each box
    $totalarray = pnModAPIFunc('InterCom', 'user', 'getmessagecount', '');

    $messagearray = pnModAPIFunc('InterCom', 'user', 'getmessages',
    array('boxtype'  => 'msg_stored',
                                       'orderby'  => $sort,
                                       'startnum' => $startnum,
                                       'perpage'  => $messagesperpage));

    // inline js for language defines
    InterCom_addinlinejs();

    // Create output object
    $renderer = & pnRender::getInstance('InterCom', false, null, true);
    $renderer->assign('boxtype',         'archive');
    $renderer->assign('currentuid',      pnUserGetVar('uid'));
    $renderer->assign('messagearray',    $messagearray);
    $renderer->assign('sortarray',       $sortarray);
    $renderer->assign('getmessagecount', $totalarray);
    $renderer->assign('sortbar_target',  'archive');
    $renderer->assign('messagesperpage', $messagesperpage);
    $renderer->assign('sort',            $sort);
    $renderer->assign('ictitle',          DataUtil::formatForDisplay(__('Archive', $dom)));
    // Return output object
    return $renderer->fetch('intercom_user_view.htm');
}

/**
 * Read inbox -
 * This function shows a inbox message.
 *
 * @author Chasm
 * @version 1.0
 * @param  int $messageid
 * @return
 */
function InterCom_user_readinbox()
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everyone else
    // Check this before Security check - maybe after login user has enough rights
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'inbox')));
    }
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    // Get parameters from whatever input we need.
    $messageid = (int)FormUtil::getPassedValue('messageid', 0, 'GETPOST');
    if ($messageid == 0) {
        return LogUtil::registerArgsError;
    }

    $message = pnModAPIFunc('InterCom', 'user', 'getmessages',
    array('boxtype'  => 'msg_inbox',
                                  'msg_id'   => $messageid));

    // Check if a message exists
    if ($message == false) {
        return LogUtil::registerError(__('Error! Could not find message text. Please check and try again.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
    } else {
        // Extract the info we need, unset the rest
        // Get additional informations about the poster of this message
        // Merge arrays
        $message = array_merge($message, pnModAPIFunc('InterCom', 'user', 'getposterdata', array('uid' => $message['from_userid'])));

        // Mark current message as read
        pnModAPIFunc('InterCom', 'user', 'mark_read', array ('msg_id' => $message['msg_id']));

        // Prepare text of message for display
        $message['msg_text'] = pnModAPIFunc('InterCom', 'user', 'prepmessage_for_display',
        array('msg_text' => $message['msg_text']));
        // URL - the db may contain false urls, try to clean them
        $message['url'] = pnModAPIFunc('InterCom', 'user', 'prepurl_for_display',
        array('url' => $message['url']));

        // Create output object
        $renderer = & pnRender::getInstance('InterCom', false, null, true);
        $renderer->assign('currentuid', pnUserGetVar('uid'));
        $renderer->assign('boxtype', 'inbox');
        $renderer->assign('message',  $message);
        return $renderer->fetch('intercom_user_readpm.htm');
    }
}

/**
 * read outbox -
 * This function shows a outbox message.
 *
 * @author Chasm
 * @version 1.0
 * @param  int $messageid
 * @return
 */
function InterCom_user_readoutbox()
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everyone else
    // Check this before Security check - maybe after login user has enough rights
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'outbox')));
    }
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    // Get parameters from whatever input we need.
    $messageid = (int)FormUtil::getPassedValue('messageid', 0, 'GETPOST');

    if ($messageid == 0) {
        return LogUtil::registerArgsError;
    }

    $message = pnModAPIFunc('InterCom', 'user', 'getmessages',
    array('boxtype'  => 'msg_outbox',
                                  'msg_id'   => $messageid));

    // no message? display error
    if ($message == false) {
        return LogUtil::registerError(__('Error! Could not find message text. Please check and try again.', $dom), null, pnModURL('InterCom', 'user', 'outbox'));
        // message exits? continue
    } else {
        // get additional informations about the poster of this message
        // merge arrays
        $message = array_merge($message, pnModAPIFunc('InterCom', 'user', 'getposterdata', array('uid' => $message['to_userid'])));

        // Prepare text of mesage for display
        $message['msg_text'] = pnModAPIFunc('InterCom', 'user', 'prepmessage_for_display',
        array ('msg_text' => $message['msg_text']));

        // URL - the db may contain false urls, try to clean them
        $message['url'] = pnModAPIFunc('InterCom', 'user', 'prepurl_for_display',
        array ('url' => $message['url']));

        // Create output object
        $renderer = & pnRender::getInstance('InterCom', false, null, true);
        $renderer->assign('currentuid', pnUserGetVar('uid'));
        $renderer->assign('boxtype', 'outbox');
        $renderer->assign('message',  $message);
        return $renderer->fetch('intercom_user_readpm.htm');
    }
}

/**
 * read archive -
 * This function shows an archive message.
 *
 * @author Chasm
 * @version 1.0
 * @param  int $messageid
 * @return
 */
function InterCom_user_readarchive()
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everyone else
    // Check this before Security check - maybe after login user has enough rights
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'archive')));
    }
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    // Get parameters from whatever input we need.
    $messageid = (int) FormUtil::getPassedValue('messageid', 0, 'GETPOST');

    if ($messageid == 0) {
        return LogUtil::registerArgsError;
    }

    $message = pnModAPIFunc('InterCom', 'user', 'getmessages',
    array('boxtype'  => 'msg_stored',
                                  'msg_id'   => $messageid));

    // no message? display error
    if ($message == false) {
        return LogUtil::registerError(__('Error! Could not find message text. Please check and try again.', $dom), null, pnModURL('InterCom', 'user', 'archive'));
        // message exits? continue
    } else {
        // get additional informations about the poster of this message
        // merge arrays
        $message = array_merge($message, pnModAPIFunc('InterCom', 'user', 'getposterdata', array('uid' => $message['from_userid'])));

        // Prepare text of mesage for display
        $message['msg_text'] = pnModAPIFunc('InterCom', 'user', 'prepmessage_for_display',
        array('msg_text' => $message['msg_text']));
        // URL - the db may contain false urls, try to clean them
        $message['url'] = pnModAPIFunc('InterCom', 'user', 'prepurl_for_display',
        array('url' => $message['url']));

        // Create output object
        $renderer = & pnRender::getInstance('InterCom', false, null, true);
        $renderer->assign('currentuid', pnUserGetVar('uid'));
        $renderer->assign('boxtype', 'archive');
        $renderer->assign('message',  $message);
        return $renderer->fetch('intercom_user_readpm.htm');
    }
}

/**
 * reply inbox -
 * This function shows the reply form for a inbox message.
 *
 * @author Chasm
 * @version 1.0
 * @return
 */
function InterCom_user_replyinbox()
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everyone else
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'inbox')));
    }
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    // Get parameters from whatever input we need.
    $messageid = (int)FormUtil::getPassedValue('messageid', 0, 'GETPOST');
    if ($messageid == 0) {
        return LogUtil::registerArgsError;
    }

    $message = pnModAPIFunc('InterCom', 'user', 'getmessages',
    array('boxtype'  => 'msg_inbox',
                                  'msg_id'   => $messageid));

    // Chasm: display errormessage if no message is returned
    if ($message == false) {
        return LogUtil::registerError(__('Error! Could not find message text. Please check and try again.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
        // Chasm: message exits, go on
    } else {
        // Chasm: get details...
        $fromuserdata = pnModAPIFunc('InterCom', 'user', 'getposterdata', array('uid' => $message['from_userid']));
        $touserdata = pnModAPIFunc('InterCom', 'user', 'getposterdata', array('uid' => $message['to_userid']));
        // Chasm: only the person who recived the PM may reply...
        if (pnUserGetVar('uid') != $touserdata['uid']) {
            return LogUtil::registerError(__('Error! Message has no recipient. Do not try this again!', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
        }

        // Prepare text of mesage for display
        $message['msg_text'] = pnModAPIFunc('InterCom', 'user', 'prepmessage_for_form',
        array('msg_text' => $message['msg_text']));
        // Create output object
        $renderer = & pnRender::getInstance('InterCom', false, null, true);
        $renderer->assign('pmtype',       'reply');
        $renderer->assign('currentuid',   pnUserGetVar('uid'));
        $renderer->assign('message',      $message);
        $renderer->assign('allowsmilies', pnModIsHooked('bbsmile', 'InterCom'));
        $renderer->assign('allowbbcode',  pnModIsHooked('bbcode', 'InterCom'));
        $renderer->assign('allowhtml',    pnModGetVar('InterCom', 'messages_allowhtml'));
        $renderer->assign('allowsmilies', pnModGetVar('InterCom', 'messages_smilies'));
        $renderer->assign('msgtogroups',  SecurityUtil::checkPermission('InterCom::', 'MsgToGroups::', ACCESS_COMMENT));
        $renderer->assign('to_user',      DataUtil::formatforDisplay($fromuserdata['uname']));
        $renderer->assign('to_user_string', DataUtil::formatforDisplay($fromuserdata['uname']));
        $renderer->assign('from_uname',   $touserdata['uname']);
        $renderer->assign('from_uid',     pnUserGetVar('uid'));
        $renderer->assign('ictitle',      DataUtil::formatForDisplay(__('Send reply', $dom)));

        return $renderer->fetch('intercom_user_pm.htm');
    }
}

/**
 * new pm -
 * This function shows the form for a new message.
 *
 * @author Chasm
 * @version 1.0
 * @return
 */
function InterCom_user_newpm($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everyone else
    // Check this before Security check - maybe after login user has enough rights
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'newpm')));
    }
    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    // Check if outboxlimit is reached
    if (!SecurityUtil::checkPermission("InterCom::", "::", ACCESS_ADMIN)) {
        $totalarray = pnModAPIFunc('InterCom', 'user', 'getmessagecount', '');
        if ($totalarray['totalout'] >= pnModGetVar('InterCom', 'messages_limitoutbox')) {
            return LogUtil::registerError(__('Sorry! There are too many messages in your outbox. Please delete some messages in the outbox, so that you can send further messages.', $dom), null, pnModURL('InterCom', 'user', 'outbox'));
        }
    }

    // Extract expected variables
    $uid = (int)FormUtil::getPassedValue('uid', 0, 'GETPOST');
    // if uname and uid are passed, uid always overrides uname, but uid must be > 1 (guest)
    if ($uid > 1) {
        $uname = pnUserGetVar('uname', $uid);
    } else {
        $uname = FormUtil::getPassedValue('uname', '', 'GETPOST');
        $uid = pnUserGetIDFromName($uname);
    }
    // Check argument

    if ((!empty($uname) && $uid==false) || $uid == 1) {
        LogUtil::registerError(__('Error! Unknown user.', $dom) . DataUtil::formatForDisplay($uname));
        unset($uname);
        unset($uid);
    }

    // if to_user is not empty, we have been here before, this overrides the uid/uname we may have received
    // format: user1,user2,user3
    $to_user      = FormUtil::getPassedValue('to_user',     isset($args['to_user'])     ? $args['to_user']     : '', 'GETPOST');
    $to_group     = FormUtil::getPassedValue('to_group',    isset($args['to_group'])    ? $args['to_group']    : '', 'GETPOST');
    $msg_subject  = FormUtil::getPassedValue('subject',     isset($args['subject'])     ? $args['subject']     : '', 'GETPOST');
    $msg_text     = FormUtil::getPassedValue('message',        isset($args['message'])     ? $args['message']     : '', 'GETPOST');
    $msg_preview  = FormUtil::getPassedValue('msg_preview', isset($args['msg_preview']) ? $args['msg_preview'] :  0, 'GETPOST');
    $html         = (int) FormUtil::getPassedValue('html',  isset($args['html'])        ? $args['html']         : 0, 'GETPOST');

    // remove HTML if it isn't allowed or user doesn't want it
    if (pnModGetVar('InterCom', 'messages_allowhtml', 0) == 0 || $html == 1) {
        $msg_text = strip_tags($msg_text);
    }

    // Clean some variables
    //$msg_preview = DataUtil::formatforDisplay($msg_preview);
    $msg_subject = DataUtil::formatforDisplayHTML($msg_subject);
    $msg_text    = DataUtil::formatforDisplayHTML($msg_text);

    // Compose an array out of it
    $message = compact('msg_subject', 'msg_text', 'html');
    if (!$to_user == "") {
        $uname .= $to_user;
    }

    // in the template we need an array fo the facebook style user selection
    $to_user_array = array();
    if (!empty($uname)) {
        $to_user_array = explode(",", $uname);
    }
    // same for the group
    $to_group_array = array();
    if (!empty($to_group)) {
        $to_group_array =explode(",", $to_group);
    }

    // get current users id
    $currentuid = pnUserGetVar('uid');

    //check for contacts
    $cl_buddies = array();
    if (pnModAvailable('ContactList')) {
        $cl_buddies = pnModAPIFunc('ContactList', 'user', 'getall',
        array ('bid'   => $currentuid,
                                          'state' => '1'));
    }

    InterCom_addinlinejs();

    // Create output object
    $renderer = & pnRender::getInstance('InterCom', false, null, true);
    $renderer->assign('pmtype',       'new');
    $renderer->assign('currentuid',   $currentuid);
    $renderer->assign('allowsmilies', pnModIsHooked('bbsmile', 'InterCom'));
    $renderer->assign('allowbbcode',  pnModIsHooked('bbcode', 'InterCom'));
    $renderer->assign('allowhtml',    pnModGetVar('InterCom', 'messages_allowhtml'));
    $renderer->assign('msgtogroups',  SecurityUtil::checkPermission('InterCom::', 'MsgToGroups::', ACCESS_COMMENT));
    $renderer->assign('msg_preview',  $msg_preview);
    $renderer->assign('to_user',      $to_user_array);
    $renderer->assign('to_user_string',$to_user);
    $renderer->assign('to_group',     $to_group_array);
    $renderer->assign('cl_buddies',   $cl_buddies);
    $renderer->assign('message',      $message);
    $renderer->assign('ictitle',      DataUtil::formatForDisplay(__('New message', $dom)));

    // Return output object
    return $renderer->fetch('intercom_user_pm.htm');
}

/**
 * Submit private message -
 * This function stores a private message into the db.
 *
 * @author Chasm
 * @version 1.0
 * @return
 */
function InterCom_user_submitpm()
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everone else
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'main')));
    }

    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    $from_uid = (int)FormUtil::getPassedValue('from_uid');
    $to_user  = FormUtil::getPassedValue('to_user', '', 'GETPOST');
    $to_group = FormUtil::getPassedValue('to_group', '', 'GETPOST');
    $subject  = FormUtil::getPassedValue('subject', '', 'GETPOST');
    $message  = FormUtil::getPassedValue('message', '', 'GETPOST');
    $msg_id   = FormUtil::getPassedValue('msg_id', 0, 'GETPOST');
    $html     = FormUtil::getPassedValue('html', 0, 'GETPOST');

    if($from_uid <> pnUserGetVar('uid')) {
        return LogUtil::registerArgsError;
    }
    // Security check for messages to entire groups
    //if (SecurityUtil::checkPermission('InterCom::', 'MsgToGroups::', ACCESS_MODERATE)) {
    //    $to_group = FormUtil::getPassedValue('to_group');
    //} else {
    //    $to_group = '';
    //}

    if (FormUtil::getPassedValue('mail_prev_x', null, 'POST')) {
        return pnModFunc('InterCom', 'user', 'newpm',
        array ('msg_preview' => '1',
                                  'to_user'     => $to_user,
                                  'to_group'    => $to_group,
                                  'msg_subject' => $subject,
                                  'msg_text'    => $message,
                                  'html'        => $html));
    }

    // Check the arguments
    if ($to_user == '' && $to_group == '') {
        LogUtil::registerError(__('Error! You did not enter a recipient. Please enter an e-mail address for the recipient and try again.', $dom), null);
        return InterCom_user_newpm();
    }
    if ($subject == '') {
        return LogUtil::registerError(__('Error! Could not find the subject line. Please enter a subject line and try again.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
    }
    if ($message == '') {
        return LogUtil::registerError(__('Error! Could not find the message text. Please enter message text and and try again.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
    }
    if (pnModGetVar('InterCom', 'messages_allowhtml') == 0 || (isset ($html))) {
        $message = strip_tags($message);
    }

    $time = date("Y-m-d H:i:s");

    // Get variables for spam protection
    $protection_on     = pnModGetVar('InterCom', 'messages_protection_on');
    $protection_time   = pnModGetVar('InterCom', 'messages_protection_time');
    $protection_amount = pnModGetVar('InterCom', 'messages_protection_amount');
    $protection_mail   = pnModGetVar('InterCom', 'messages_protection_mail');

    array_splice($recipients, 1);

    // Initalize spam protection
    $antispam_count = 0;

    // Split $to_user in an array to allow more than one recipient
    $recipients = explode(",", $to_user);

    // count the messages that have been posted, it's better to say 'x messages posted" than sowing this x times
    $post_message_count = 0;

    // Send message to user(s)
    if (!empty ($to_user)) {
        foreach ($recipients as $recipient) {
            $to_uid = pnUserGetIDFromName($recipient);
            //allow uid in recipient
            if ($to_uid == "" and is_numeric($recipient)) {
                $to_uid = pnUserGetVar('uid', $recipient);
            }
            if ($to_uid == "") {
                LogUtil::registerError(__('Error! Unknown user.', $dom) . DataUtil::formatForDisplay($uname));
            }
            if ($protection_on == 1) {
                $antispam_count++;
                $antispam_arr = SessionUtil::getVar('antispam');
                if ($antispam_arr == false) {
                    $antispam_arr = array ();
                }

                foreach ($antispam_arr as $key => $value) {
                    //delete entries, older then XX minutes
                    if ($key < (mktime() - (60 * $protection_time)))
                    unset ($antispam_arr[$key]);
                }
                //don't count messages to the same users more than once
                if (count(array_count_values($antispam_arr)) > $protection_amount) {
                    $from_uname = pnUserGetVar('uname', $from_uid);
                    if ($protection_mail == 1) {
                        $email_from = pnModGetVar('InterCom', 'messages_fromname');
                        $email_fromname = pnModGetVar('sitename');
                        $email_to = pnConfigGetVar('adminmail');
                        $email_subject = pnModGetVar('sitename') . '' . __('Spam alert', $dom);
                        $message = __('The user', $dom) . ' ' . $from_uname . ' (#' . $from_uid . ') ' . __('tried to send too many private messages in too short a time. The private messaging system\'s spam prevention feature stopped the messages from being sent.', $dom);

                        $args = array (
                        'fromname'    => $email_fromname,
                        'fromaddress' => $email_from,
                        'toname'      => $email_address,
                        'toaddress'   => $email_to,
                        'subject'     => $email_subject,
                        'body'        => $message,
                        'headers'     => array (
                        'X-Mailer: ' . $modinfo['name'] . ' ' . $modinfo['version']
                        )
                        );
                        pnModAPIFunc('Mailer', 'user', 'sendmessage', $args);
                    }
                    SessionUtil::setVar('antispam', $antispam_arr);
                    return LogUtil::registerError(__('Sorry! You have triggered the private messaging system\'s spam prevention feature by trying to send messages to too many users in too short a time.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
                }
                $antispam_arr[mktime() + $antispam_count] = $to_uid;
                SessionUtil::setVar('antispam', $antispam_arr);
            }
            //check if blocked in postBuddy
            if (pnModAvailable('postBuddy')) {
                $buddy = pnModAPIFunc('postBuddy', 'user', 'get',
                array('uid' => $to_uid,
                                            'bid' => $from_uid));
                if ($buddy['state'] == 'DENIED') {
                    LogUtil::registerStatus(__('Sorry! The recipient you entered has blocked the reception of messages from you. Unfortunately, your message will not be sent.', $dom));
                }
            }
            //check if blocked in ContactList
            if (pnModAvailable('ContactList')) {
                $isIgnored = pnModAPIFunc('ContactList', 'user', 'isIgnored',
                array('uid'  => $to_uid,
                                                'iuid' => $from_uid));
                if ($isIgnored) {
                    LogUtil::registerStatus(__('Sorry! The recipient you entered has blocked the reception of messages from you. Unfortunately, your message will not be sent.', $dom));
                }
            }
            $from_uid = pnUserGetVar('uid');
            if (isset ($msg_id)) {
                pnModAPIFunc('InterCom', 'user', 'mark_replied',
                array ('msg_id'=> $msg_id));
            }
            pnModAPIFunc('InterCom', 'user', 'store_message',
            array ('from_userid' => $from_uid,
                                'to_userid'   => $to_uid,
                                'msg_subject' => $subject,
                                'msg_time'    => $time,
                                'msg_text'    => $message,
                                'msg_inbox'   => '1',
                                'msg_outbox'  => '1',
                                'msg_stored'  => '0'));
            pnModAPIFunc('InterCom', 'user', 'autoreply',
            array ('to_uid'      => $to_uid,
                                'from_uid'    => $from_uid,
                                'subject'     => $subject));
            $post_message_count++;
            LogUtil::registerStatus(__('Done! Message sent.', $dom));
        }
    }

    // Split $to_group in an array to allow more than one group
    $grouprecipients = explode(",", $to_group);

    // Send message to group(s)
    if (!empty ($to_group)) {
        foreach ($grouprecipients as $grouprecipient) {
            $to_groupid = pnModAPIFunc('Groups', 'admin', 'getgidbyname', array ('name' => $grouprecipient));
            if ($to_groupid == false) {
                LogUtil::registerError(__('Error! Unknown group.', $dom) . DataUtil::formatForDisplay($grouprecipient));
            }
            $groupinfo = pnModAPIFunc('Groups', 'user', 'get', array('gid' => $to_groupid));
            foreach ($groupinfo['members'] as $to_uid => $dummy) {
                $from_uid = pnUserGetVar('uid');
                pnModAPIFunc('InterCom', 'user', 'store_message',
                array ('from_userid'  => $from_uid,
                                    'to_userid'    => $to_uid,
                                    'msg_subject'  => $subject,
                                    'msg_time'     => $time,
                                    'msg_text'     => $message,
                                    'msg_inbox'    => '1',
                                    'msg_outbox'   => '1',
                                    'msg_stored'   => '0'));
                //pnModAPIFunc('InterCom', 'user', 'autoreply', array('to_uid' => $to_uid, 'from_uid' => $from_uid, 'image' => $image, 'subject' => $subject));
                $post_message_count++;
            }
        }
    }

    if($post_message_count > 0) {
        LogUtil::registerStatus(_fn('Done! Sent %s messages.', $post_message_count, $domain));
    }

    return pnRedirect(pnModURL('InterCom', 'user', 'inbox'));
}

/**
 * Switchaction -
 * Redirects form input to the specific function
 *
 * @author Chasm
 * @version 1.0
 * @return
 */
function InterCom_user_switchaction()
{
    // This is a user only module - redirect everone else
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'main')));
    }

    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    // Get parameters from whatever input we need.
    // Chasm: messageid my be an array! no typecasting!
    $msg_id     = FormUtil::getPassedValue('messageid');
    $save       = FormUtil::getPassedValue('save', '', 'GETPOST');
    $delete     = FormUtil::getPassedValue('delete', '', 'GETPOST');

    if ($save != '') {
        return pnRedirect(pnModURL('InterCom', 'user', 'storepm',
        array('messageid' => $msg_id)));
    }
    if ($delete != '') {
        return pnRedirect(pnModURL('InterCom', 'user', 'deletefrominbox',
        array('messageid' => $msg_id)));
    }

    return pnRedirect(pnModURL('InterCom', 'user', 'inbox'));
}

/**
 * delete a message from the inbox
 *
 * @author Landseer
 * @version 2.0
 * @return
 */
function InterCom_user_deletefrominbox()
{
    return InterCom_deletepm('msg_inbox', 'inbox');
}

/**
 * delete a message from the archive
 *
 * @author Landseer
 * @version 2.0
 * @return
 */
function InterCom_user_deletefromarchive()
{
    return InterCom_deletepm('msg_stored', 'archive');
}

/**
 * delete a message from the outbox
 *
 * @author Landseer
 * @version 2.0
 * @return
 */
function InterCom_user_deletefromoutbox()
{
    return InterCom_deletepm('msg_outbox', 'outbox');
}

/**
 * Delete private message -
 * Marks a private message as deleted
 *
 * @author Chasm
 * @version 1.0
 * @return
 */
function InterCom_deletepm($msg_type, $forwardfunc)
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everone else
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'main')));
    }

    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    // Get parameters from whatever input we need.
    // Chasm: messageid my be an array! no typecasting!
    $msg_id = FormUtil::getPassedValue('messageid');

    if (!is_array($msg_id)) {
        // create a fake array
        $msg_id = array($msg_id);
    }

    $status = false;
    foreach ($msg_id as $single_msg_id) {
        $status = pnModAPIFunc('InterCom', 'user', 'delete',
        array('msg_id'   => $single_msg_id,
                                     'msg_type' => $msg_type));
        if (!$status) {
            return LogUtil::registerError(__('Error! Could not delete the message. Please check the reason for this, resolve the problem and try again.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
        }
    }
    LogUtil::registerStatus(__('Done! Message deleted.', $dom));
    return pnRedirect(pnModURL('InterCom', 'user', $forwardfunc));
}

/**
 * Store as pm in the archive
 *
 * @author Chasm
 * @version 1.0
 * @return
 */
function InterCom_user_storepm()
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // This is a user only module - redirect everone else
    if (!pnUserLoggedIn()) {
        return pnRedirect(pnModURL('InterCom', 'user', 'loginscreen', array('page' => 'main')));
    }

    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    // Check if archivelimit is reached
    if (!SecurityUtil::checkPermission("InterCom::", "::", ACCESS_ADMIN)) {
        $totalarray = pnModAPIFunc('InterCom', 'user', 'getmessagecount', '');
        if ($totalarray['totalarchive'] >= pnModGetVar('InterCom', 'messages_limitarchive')) {
            return LogUtil::registerError( __('Sorry! There are too many messages in the archive. Please delete some messages from the archive, to enable archiving of further messages.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
        }
    }

    // Get parameters from whatever input we need.
    // Chasm: messageid may be an array! no typecasting!
    $msg_id = FormUtil::getPassedValue('messageid');
    if (!is_array($msg_id)) {
        $msg_id = array($msg_id);
    }
    $status = false;
    foreach ($msg_id as $single_msg_id) {
        $status = pnModAPIFunc('InterCom', 'user', 'store',
        array('msg_id' => $single_msg_id));
        if (!$status) {
            return LogUtil::registerError(__('Error! Could not save your message. Please check the reason, resolve the problem and try again.', $dom), null, pnModURL('InterCom', 'user', 'inbox'));
        }
    }
    LogUtil::registerStatus(__('Done! Message archived.', $dom));
    return pnRedirect(pnModURL('InterCom', 'user', 'inbox'));
}

/**
 * Login for the user with redirect
 *
 * @author chaos
 * @version 1.0
 * @return
 */
function InterCom_user_login()
{
    $uname      = FormUtil::getPassedValue('uname', '', 'POST');
    $email      = FormUtil::getPassedValue('email', '', 'POST');
    $pass       = FormUtil::getPassedValue('pass', '', 'POST');
    $url        = FormUtil::getPassedValue('url', pnModURL('InterCom', 'user', 'inbox'), 'POST');
    $rememberme = FormUtil::getPassedValue('rememberme', '', 'POST');

    $loginoption = pnModGetVar('Users', 'loginviaoption', 0);

    // Do the login
    if (pnUserLogIn(($loginoption==1) ? $email : $uname, $pass, $rememberme)) {
        return pnRedirect($url);
    } else {
        LogUtil::registerError(__('Error! Could not log in.', $dom));
        $renderer = & pnRender::getInstance('InterCom');
        return $renderer->fetch('intercom_user_login.htm');
    }
}

/**
 * messageinfo
 * used in plugin function.messageinfo.php
 * displays a ajax-window (Control.Modal) if a new message has arrived
 *
 * @author Carsten Volmer
 */

function InterCom_user_messageinfo()
{
    $out = '';
    if(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
        $totalarray = pnModAPIFunc('InterCom', 'user', 'getmessagecount', '');
        $modname = pnModGetName();

        if ($totalarray['popup'] >= 1 && $totalarray['unread'] >= 1 && $modname <> "InterCom") {
            PageUtil::addVar('stylesheet', 'modules/InterCom/pnstyle/modal.css');
            PageUtil::addVar('javascript', 'javascript/ajax/prototype.js');
            PageUtil::addVar('javascript', 'javascript/ajax/effects.js');
            PageUtil::addVar('javascript', 'modules/InterCom/pnjavascript/control.modal.js');

            pnModAPIFunc('InterCom', 'user', 'mark_popup', array('to_userid' => pnUserGetVar('uid')));
            $pnr = & pnRender::getInstance('InterCom', false);
            $pnr->assign('totalarray', $totalarray);
            $out = $pnr->fetch('intercom_user_messageinfo.htm');
        }
    }
    return $out;
}

/**
 * loginscreen
 * show a login screen to the user and redirect to the previouse page after login by supplying a url
 *
 *@author Frank Schummertz
 *@params $args['page'] string  the page to redirect to after a successful login
 *@returns html
 */

function InterCom_user_loginscreen($args)
{
    $page = (isset($args['page']) && !empty($args['page'])) ? $args['page'] : 'main';

    $renderer = & pnRender::getInstance('InterCom', false);
    $renderer->assign('url', pnModURL('InterCom', 'user', $page));
    return $renderer->fetch('intercom_user_login.htm');
}



/**
 * forward a message from the inbox
 *
 *@params msgid int the id of the message to forward
 */

function InterCom_user_forwardfrominbox()
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // Security check
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
        return LogUtil::registerPermissionError(pnConfigGetVar('entrypoint', 'index.php'));
    }

    $messageid = (int)FormUtil::getPassedValue('messageid', 0, 'GETPOST');
    if ($messageid == 0) {
        return LogUtil::registerArgsError;
    }

    $message = pnModAPIFunc('InterCom', 'user', 'getmessages',
    array('boxtype'  => 'msg_inbox',
                                  'msg_id'   => $messageid));

    // Prepare text of mesage for display
    $message['msg_text'] = pnModAPIFunc('InterCom', 'user', 'prepmessage_for_form',
    array('msg_text' => $message['msg_text']));

    // Create output object
    $renderer = & pnRender::getInstance('InterCom', false, null, true);

    $bbcode = pnModIsHooked('bbcode', 'InterCom');
    $message['forward_text'] = DataUtil::formatForDisplay($message['msg_text']);
    if ($bbcode == true) {
        $message['forward_text'] = '[quote=' . pnUserGetVar('uname', $message['from_userid']) . ']' . $message['forward_text'] . '[/quote]';
    }

    $renderer->assign('pmtype',       'forward');
    $renderer->assign('currentuid',   pnUserGetVar('uid'));
    $renderer->assign('message',      $message);
    $renderer->assign('allowsmilies', pnModIsHooked('bbsmile', 'InterCom'));
    $renderer->assign('allowbbcode',  $bbcode);
    $renderer->assign('allowhtml',    pnModGetVar('InterCom', 'messages_allowhtml'));
    $renderer->assign('msgtogroups',  SecurityUtil::checkPermission('InterCom::', 'MsgToGroups::', ACCESS_COMMENT));
    $renderer->assign('ictitle',      DataUtil::formatForDisplay(__('Forward message', $dom)));

    return $renderer->fetch('intercom_user_pm.htm');
}

/**
 * add inline js with language defines
 *
 */
function InterCom_addinlinejs()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // inline js for language defines
    $inlinejs = '<script type="text/javascript">
var loadingReply="' . DataUtil::formatForDisplay(__('Preparing the reply. Please be patient...', $dom))  . '";
var storingReply = "' . DataUtil::formatForDisplay(__('Saving the reply. Please be patient...', $dom)) . '";
var loadingForward="' . DataUtil::formatForDisplay(__('Preparing to forward the message. Please be patient...', $dom))  . '";
var storingForward = "' . DataUtil::formatForDisplay(__('Forwarding the message. Please be patient...', $dom)) . '";
var archivingMessage = "' . DataUtil::formatForDisplay(__('Archiving the message. Please be patient...', $dom)) . '";
var deletingMessage = "' . DataUtil::formatForDisplay(__('Deleting the message. Please be patient...', $dom)) . '";
var norecipientfound = "' . DataUtil::formatForDisplay(__('Error! Could not find a recipient e-mail address.', $dom)) . '";
var nosubjectfound = "' . DataUtil::formatForDisplay(__('Error! Could not find a subject line for the message.', $dom)) . '";
var nomessagefound = "' . DataUtil::formatForDisplay(__('Error! Could not find any message text for the message.', $dom)) . '";
var messageposted = "' . DataUtil::formatfordisplay(__('Done! Message sent.', $dom)) . '";
var messagearchived = "' . DataUtil::formatfordisplay(__('Done! Message archived.', $dom)) . '";
var messagedeleted = "' . DataUtil::formatfordisplay(__('Done! Message deleted.', $dom)) . '";
var userdeleted = "' . DataUtil::formatfordisplay(__('Sorry! You cannot reply to the message from this user because the person\'s user account has been deleted.', $dom)) . '";
</script>';
    PageUtil::addVar('rawtext', $inlinejs);
    return true;
}
