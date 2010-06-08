<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

/**
 * get available admin panel links
 *
 * @return array array of admin links
 */
function InterCom_adminapi_getlinks()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    $links = array();
    if (SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        $links[] = array('url' => pnModURL('InterCom', 'admin', 'main'), 'text' => __('Statistics', $dom));
        $links[] = array('url' => pnModURL('InterCom', 'admin', 'tools'), 'text' => __('Utilities', $dom));
        $links[] = array('url' => pnModURL('InterCom', 'admin', 'modifyconfig'), 'text' => __('Settings', $dom));
    }
    return $links;
}

function InterCom_adminapi_delete_all()
{
    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    DBUtil::truncateTable('intercom');
    return true;
}

function InterCom_adminapi_delete_inboxes()
{
    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $pntable = pnDBGetTables();
    // define tables and columns
    $messagestable  = $pntable['intercom'];
    $messagescolumn = $pntable['intercom_column'];

    DBUtil::executeSQL("UPDATE $messagestable SET $messagescolumn[msg_inbox]='0'");

    pnModAPIFunc('InterCom', 'admin', 'optimize_db');
    return true;
}

function InterCom_adminapi_delete_outboxes()
{
    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $pntable = pnDBGetTables();
    // define tables and columns
    $messagestable  = $pntable['intercom'];
    $messagescolumn = $pntable['intercom_column'];

    DBUtil::executeSQL("UPDATE $messagestable SET $messagescolumn[msg_outbox]='0'");

    pnModAPIFunc('InterCom', 'admin', 'optimize_db');
    return true;
}

function InterCom_adminapi_delete_archives()
{
    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $pntable = pnDBGetTables();
    // define tables and columns
    $messagestable  = $pntable['intercom'];
    $messagescolumn = $pntable['intercom_column'];

    DBUtil::executeSQL("UPDATE $messagestable SET $messagescolumn[msg_stored]='0'");

    pnModAPIFunc('InterCom', 'admin', 'optimize_db');
    return true;
}

function InterCom_adminapi_optimize_db()
{
    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // remomve unused mails
    pnModAPIFunc('InterCom', 'user', 'optimize_db');

    $pntable = pnDBGetTables();
    $messagestable  = $pntable['intercom'];
    DBUtil::executeSQL("OPTIMIZE TABLE $messagestable");

    return true;
}

function InterCom_adminapi_default_config()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!defined('_PNINSTALLVER') && !SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    pnModSetVar('InterCom', 'messages_limitarchive', '50');
    pnModSetVar('InterCom', 'messages_limitoutbox', '50');
    pnModSetVar('InterCom', 'messages_limitinbox', '50');
    pnModSetVar('InterCom', 'messages_allowhtml', false);
    pnModSetVar('InterCom', 'messages_allowsmilies', false);
    pnModSetVar('InterCom', 'messages_perpage', '25');

    pnModSetVar('InterCom', 'messages_allow_emailnotification', true);
    pnModSetVar('InterCom', 'messages_mailsubject', __('You have a new private message', $dom));
    pnModSetVar('InterCom', 'messages_fromname', '');
    pnModSetVar('InterCom', 'messages_from_email', '');

    pnModSetVar('InterCom', 'messages_allow_autoreply', true);

    pnModSetVar('InterCom', 'messages_userprompt', __('Welcome to the private messaging system', $dom));
    pnModSetVar('InterCom', 'messages_userprompt_display', false);
    pnModSetVar('InterCom', 'messages_active', true);
    pnModSetVar('InterCom', 'messages_maintain', __('Sorry! The private messaging system is currently off-line for maintenance. Please check again later, or contact the site administrator.', $dom));

    pnModSetVar('InterCom', 'messages_protection_on', true);
    pnModSetVar('InterCom', 'messages_protection_time', '15');
    pnModSetVar('InterCom', 'messages_protection_amount', '15');
    pnModSetVar('InterCom', 'messages_protection_mail', false);

    pnModSetVar('InterCom', 'messages_welcomemessagesender', __('Site admin', $dom));
    pnModSetVar('InterCom', 'messages_welcomemessagesubject', __('Welcome to the private messaging system on %sitename%', $dom));  // quotes are important here!!
    pnModSetVar('InterCom', 'messages_welcomemessage', __('Hello!', $dom .'Welcome to the private messaging system on %sitename%. Please remember that use of the private messaging system is subject to the site\'s terms of use and privacy statement. If you have any questions or encounter any problems, please contact the site administrator. Site admin', $dom)); // quotes are important here!!!
    pnModSetVar('InterCom', 'messages_savewelcomemessage', false);

    return true;
}
