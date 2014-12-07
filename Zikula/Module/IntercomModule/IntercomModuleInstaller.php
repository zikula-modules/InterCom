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
use System;
use ZLanguage;
use Zikula\Module\IntercomModule\Entity\MessageEntity;


class IntercomModuleInstaller extends \Zikula_AbstractInstaller
{

    const MODULENAME = 'ZikulaIntercomModule';    
    
    /**
     * get the default module var values
     *
     * @return array
     */
    public static function getDefaultVars()
    {
        $dom = ZLanguage::getModuleDomain(self::MODULENAME);

        return array(
            'messages_limitarchive'=> '50',
            'messages_limitoutbox'=> '50',
            'messages_limitinbox'=> '50',
            'messages_allowhtml'=> false,
            'messages_allowsmilies'=> false,
            'messages_perpage'=> '25',
            'messages_allow_emailnotification'=> true,
            'messages_mailsubject'=> __('You have a new private message', $dom),
            'messages_fromname'=> '',
            'messages_from_email'=> '',
            'messages_allow_autoreply'=> true,
            'messages_userprompt'=> __('Welcome to the private messaging system', $dom),
            'messages_userprompt_display'=> false,
            'messages_active'=> true,
            'messages_maintain'=> __('Sorry! The private messaging system is currently off-line for maintenance. Please check again later, or contact the site administrator.', $dom),
            'messages_protection_on'=> true,
            'messages_protection_time'=> '15',
            'messages_protection_amount'=> '15',
            'messages_protection_mail'=> false,
            'messages_welcomemessagesender'=> __('admin', $dom),
            'messages_welcomemessagesubject'=> __('Welcome to the private messaging system on %sitename%', $dom),  // quotes are important here!!
            'messages_welcomemessage'=> __('Hello!' .'Welcome to the private messaging system on %sitename%. Please remember that use of the private messaging system is subject to the site\'s terms of use and privacy statement. If you have any questions or encounter any problems, please contact the site administrator. Site admin', $dom), // quotes are important here!!!
            'messages_savewelcomemessage'=> false);
    
    }   
    
    public function install()
    {
    
        try {
            DoctrineHelper::createSchema($this->entityManager, array('Zikula\Module\IntercomModule\Entity\MessageEntity'));
        } catch (\Exception $e) {
            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());
            return false;
        }
        $this->setVars(self::getDefaultVars());

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
        return true;
    }
}
