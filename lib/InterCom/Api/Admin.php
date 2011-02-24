<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

class InterCom_Api_Admin extends Zikula_Api
{
    /**
     * get available admin panel links
     *
     * @return array array of admin links
     */
    public function getlinks()
    {
        $links = array();
        if (SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('InterCom', 'admin', 'main'),
                'text' => $this->__('Statistics'),
                'class' => 'z-icon-es-info'
            );
            $links[] = array(
                'url' => ModUtil::url('InterCom', 'admin', 'tools'),
                'text' => $this->__('Utilities'),
                'class' => 'z-icon-es-gears'
            );
            $links[] = array(
                'url' => ModUtil::url('InterCom', 'admin', 'modifyconfig'),
                'text' => $this->__('Settings'),
                'class' => 'z-icon-es-config'
            );
        }
        return $links;
    }

    public function delete_all()
    {
        // Security check - important to do this as early on as possible to
        // avoid potential security holes or just too much wasted processing
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        DBUtil::truncateTable('intercom');
        return true;
    }

    public function delete_inboxes()
    {
        // Security check - important to do this as early on as possible to
        // avoid potential security holes or just too much wasted processing
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $pntable = DBUtil::getTables();
        // define tables and columns
        $messagestable  = $pntable['intercom'];
        $messagescolumn = $pntable['intercom_column'];

        DBUtil::executeSQL("UPDATE $messagestable SET $messagescolumn[msg_inbox]='0'");

        ModUtil::apiFunc('InterCom', 'admin', 'optimize_db');
        return true;
    }

    public function delete_outboxes()
    {
        // Security check - important to do this as early on as possible to
        // avoid potential security holes or just too much wasted processing
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $pntable = DBUtil::getTables();
        // define tables and columns
        $messagestable  = $pntable['intercom'];
        $messagescolumn = $pntable['intercom_column'];

        DBUtil::executeSQL("UPDATE $messagestable SET $messagescolumn[msg_outbox]='0'");

        ModUtil::apiFunc('InterCom', 'admin', 'optimize_db');
        return true;
    }

    public function delete_archives()
    {
        // Security check - important to do this as early on as possible to
        // avoid potential security holes or just too much wasted processing
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $pntable = DBUtil::getTables();
        // define tables and columns
        $messagestable  = $pntable['intercom'];
        $messagescolumn = $pntable['intercom_column'];

        DBUtil::executeSQL("UPDATE $messagestable SET $messagescolumn[msg_stored]='0'");

        ModUtil::apiFunc('InterCom', 'admin', 'optimize_db');
        return true;
    }

    public function optimize_db()
    {
        // Security check - important to do this as early on as possible to
        // avoid potential security holes or just too much wasted processing
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // remomve unused mails
        ModUtil::apiFunc('InterCom', 'user', 'optimize_db');

        $pntable = DBUtil::getTables();
        $messagestable  = $pntable['intercom'];
        DBUtil::executeSQL("OPTIMIZE TABLE $messagestable");

        return true;
    }

    public function default_config()
    {
        // Security check - important to do this as early on as possible to
        // avoid potential security holes or just too much wasted processing
        if (!defined('_PNINSTALLVER') && !SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $this->setVar('messages_limitarchive', '50');
        $this->setVar('messages_limitoutbox', '50');
        $this->setVar('messages_limitinbox', '50');
        $this->setVar('messages_allowhtml', false);
        $this->setVar('messages_allowsmilies', false);
        $this->setVar('messages_perpage', '25');

        $this->setVar('messages_allow_emailnotification', true);
        $this->setVar('messages_mailsubject', $this->__('You have a new private message'));
        $this->setVar('messages_fromname', '');
        $this->setVar('messages_from_email', '');

        $this->setVar('messages_allow_autoreply', true);

        $this->setVar('messages_userprompt', $this->__('Welcome to the private messaging system'));
        $this->setVar('messages_userprompt_display', false);
        $this->setVar('messages_active', true);
        $this->setVar('messages_maintain', $this->__('Sorry! The private messaging system is currently off-line for maintenance. Please check again later, or contact the site administrator.'));

        $this->setVar('messages_protection_on', true);
        $this->setVar('messages_protection_time', '15');
        $this->setVar('messages_protection_amount', '15');
        $this->setVar('messages_protection_mail', false);

        $this->setVar('messages_welcomemessagesender', $this->__('Site admin'));
        $this->setVar('messages_welcomemessagesubject', $this->__('Welcome to the private messaging system on %sitename%'));  // quotes are important here!!
        $this->setVar('messages_welcomemessage', $this->__('Hello!' .'Welcome to the private messaging system on %sitename%. Please remember that use of the private messaging system is subject to the site\'s terms of use and privacy statement. If you have any questions or encounter any problems, please contact the site administrator. Site admin')); // quotes are important here!!!
        $this->setVar('messages_savewelcomemessage', false);

        return true;
    }
}