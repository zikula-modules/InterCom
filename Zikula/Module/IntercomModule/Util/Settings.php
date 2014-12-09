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
namespace Zikula\Module\IntercomModule\Util;

use DataUtil;
use ServiceUtil;

use Zikula\Module\IntercomModule\Util\SettingsValidator;


class Settings {
 
    private $settings;
    private $_newsettings;
    private $validator;
    
    public function __construct() {
        
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
        
    }

    public function checkSettings() {
        
    }
    
    public function saveSettings() {
        
    }    
    
    
        /**
     * get the default module var values
     *
     * @return array
     */
    public static function getDefault()
    {
        return array(
            'limitarchive'=> '50',
            'limitoutbox'=> '50',
            'limitinbox'=> '50',
            'allowhtml'=> false,
            'allowsmilies'=> false,
            'perpage'=> '25',
            'allow_emailnotification'=> true,
            'mailsubject'=> __('You have a new private message', $dom),
            'fromname'=> '',
            'from_email'=> '',
            'allow_autoreply'=> true,
            'userprompt'=> __('Welcome to the private messaging system', $dom),
            'userprompt_display'=> false,
            'active'=> true,
            'maintain'=> __('Sorry! The private messaging system is currently off-line for maintenance. Please check again later, or contact the site administrator.', $dom),
            'protection_on'=> true,
            'protection_time'=> '15',
            'protection_amount'=> '15',
            'protection_mail'=> false,
            'welcomemessagesender'=> __('admin', $dom),
            'welcomemessagesubject'=> __('Welcome to the private messaging system on %sitename%', $dom),  // quotes are important here!!
            'welcomemessage'=> __('Hello!' .'Welcome to the private messaging system on %sitename%. Please remember that use of the private messaging system is subject to the site\'s terms of use and privacy statement. If you have any questions or encounter any problems, please contact the site administrator. Site admin', $dom), // quotes are important here!!!
            'savewelcomemessage'=> false);    
    }
    
    
}