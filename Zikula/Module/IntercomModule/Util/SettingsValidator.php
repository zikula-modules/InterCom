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


class SettingsValidator {

    private $valid;
    private $data;
    private $errors;
    
    public function __construct() {
        
        $this->valid = true;
        $this->data = array();
        $this->errors = array();  
    }
    
    public function isValid() {
        return $this->valid;
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function setData($p){   
        $this->data = $p;
        $this->validate();
    }
    
    public function getData(){   
        return $this->data;
    }    
    
    public function validate(){
        $this->checkSubject();
        $this->checkText();
        $this->checkInbox();
        $this->checkOutbox();
        $this->checkStored();
        $this->checkNotified();
        $this->checkReplied();
        $this->checkSeen();
        $this->checkSend();
        $this->checkSender();
        $this->checkRecipient();        
    }
    

    public function checkSubject() {
        $subject = $this->data['subject'];
        if (empty($subject)){
            $this->valid = false;    
            $this->errors['subject'] = 'Subject cannot be empty';            
        }         
    }

    public function checkText() {    
        if (empty($this->data['text'])){
            $this->valid = false;    
            $this->errors['text'] = 'Message cannot be empty';            
        } 
    }
    
    public function checkInbox() {
        
    }

    public function checkOutbox() {
        
    }

    public function checkStored() {
      
    }

    public function checkNotified() {
        
    }
    
    public function checkReplied() {
        
    }    
    
    public function checkSeen() {
        
    }

    public function checkSend() {
      
    }       
}