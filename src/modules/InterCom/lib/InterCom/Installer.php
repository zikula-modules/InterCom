<?php
/**
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

class InterCom_Installer extends Zikula_AbstractInstaller
{
    public function install()
    {
        if (!DBUtil::createTable('intercom')) {
            return false;
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

        $this->setVar('messages_welcomemessagesender', $this->__('admin'));
        $this->setVar('messages_welcomemessagesubject', $this->__('Welcome to the private messaging system on %sitename%'));  // quotes are important here!!
        $this->setVar('messages_welcomemessage', $this->__('Hello!' .'Welcome to the private messaging system on %sitename%. Please remember that use of the private messaging system is subject to the site\'s terms of use and privacy statement. If you have any questions or encounter any problems, please contact the site administrator. Site admin')); // quotes are important here!!!
        $this->setVar('messages_savewelcomemessage', false);

        return true;
    }

    public function upgrade($oldversion)
    {
        switch ($oldversion)
        {
           case '2.1':
            /* in a new installation of InterCom 2.1 the createhook has not been added, we will do this now if necessary */
//                TODO: Fix hooks for Zikula 1.3
//                if (ModUtil::registerHook('item', 'create', 'API', 'InterCom', 'user', 'createhook')) {
//                    // enable the create hook for the Users module
//                    ModUtil::apiFunc('Modules', 'admin', 'enablehooks', array('callermodname' => 'Users', 'hookmodname' => 'InterCom'));
//                }
            case '2.2':
                $this->setVar('messages_force_emailnotification', true);
            case '2.2.0':
                DBUtil::changeTable('intercom');
        }

        return true;
    }

    public function uninstall()
    {
        if (!DBUtil::dropTable('intercom')) {
            return false;
        }

        EventUtil::unregisterPersistentModuleHandlers('InterCom');
        $this->delVars('InterCom');

        return true;
    }
}
