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

/**
 * Intercom module installer.
 */

namespace Zikula\Module\IntercomModule;

use DoctrineHelper;
use EventUtil;
use System;
use Zikula\Module\IntercomModule\Entity\MessageEntity;


class IntercomModuleInstaller extends \Zikula_AbstractInstaller
{

    public function SetDefaultVars()
    { 
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
    
    }   
    
    public function install()
    {
    
        try {
            DoctrineHelper::createSchema($this->entityManager, array('Zikula\Module\IntercomModule\Entity\MessageEntity'));
        } catch (\Exception $e) {
            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());
            return false;
        }
        $this->SetDefaultVars();

        EventUtil::registerPersistentModuleHandler('InterCom', 'user.account.create',
                array('InterCom_Listener_CreateUserListener', 'onCreateUser'));

        return true;
    }

    public function upgrade($oldversion)
    {
        switch ($oldversion)
        {
        }

        return true;
    }

    public function uninstall()
    {
        try {
            DoctrineHelper::dropSchema($this->entityManager, array('Zikula\Module\IntercomModule\Entity\MessageEntity'));
        } catch (\PDOException $e) {
            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());
            return false;
        }
        // Delete any module variables
        $this->delVars();
        
        EventUtil::unregisterPersistentModuleHandlers('InterCom');
        return true;
    }
}
