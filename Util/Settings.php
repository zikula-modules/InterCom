<?php
/**
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @subpackage Util
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */
namespace Zikula\IntercomModule\Util;

use DataUtil;
use ServiceUtil;
use ZLanguage;
use ModUtil;

use Zikula\IntercomModule\Util\SettingsValidator;


class Settings {
 
    private $settings;
    private $_newsettings;
    private $validator;
    private $name;
    
    public function __construct() {
        $this->name = 'ZikulaIntercomModule';
        $this->validator = new SettingsValidator();        
    }

    public function setNewData($p)
    {
        $this->validator->setData($p);
        $this->_newsettings = $this->validator->getData();
    }
    
    public function getNewData()
    {
        return $this->_newsettings;   
    }

    public function isValid()
    {
        return $this->validator->isValid();
    }

    public function getErrors()
    {
        return $this->validator->getErrors();
    }    
    
    public function resetSettings() {
        ModUtil::delVar($this->name); 
        ModUtil::setVars($this->name,$this->getDefault());
        return true;
    }

    public function checkSettings() {
        
    }
    
    public function save() {
        if($this->isValid()){
        ModUtil::setVars($this->name,  $this->_newsettings);
        }
    }    
    
     /**
     * get the default module var values
     *
     * @return array
     */
    public static function getDefault()
    {
        return array(
            //General
            'active'=> true,
            'maintain'=> __('Sorry! The private messaging system is currently off-line for maintenance. Please check again later, or contact the site administrator.'),
            'disable_ajax'=> false,
            'allowhtml'=> false,
            'allowsmilies'=> false,            
            //Limitations
            'limitarchive'=> '50',
            'limitoutbox'=> '50',
            'limitinbox'=> '50',
            'perpage'=> '25',
            //Email
            'allow_emailnotification'=> true,            
            'force_emailnotification'=> false,
            'mailsubject'=> __('You have a new private message'),
            'fromname'=> '',
            'from_email'=> '',            
            'mailsender'=> '',
            //Autoresponder
            'allow_autoreply' => false,
            //Users prompt
            'userprompt'=> __('Welcome to the private messaging system'),
            'userprompt_display'=> false,
            //Welcome
            'welcomemessage_send' => false,
            'welcomemessagesender'=> __('admin'),
            'welcomemessagesubject'=> __('Welcome to the private messaging system on %sitename%'),  // quotes are important here!!
            'welcomemessage'=> __('Hello!' .'Welcome to the private messaging system on %sitename%. Please remember that use of the private messaging system is subject to the site\'s terms of use and privacy statement. If you have any questions or encounter any problems, please contact the site administrator. Site admin'), // quotes are important here!!!
            'savewelcomemessage'=> false,
            'intlwelcomemessage'=> '',            
            //Protection
            'protection_on'=> true,
            'protection_time'=> '15',
            'protection_amount'=> '15',
            'protection_mail'=> false);    
    }
    
    
}