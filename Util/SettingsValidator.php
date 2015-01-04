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
        $this->checkLimitinbox();
        $this->checkLimitoutbox();
        $this->checkLimitarchive();
        $this->checkPerpage();
    }
    
    public function checkLimitinbox() {
        $limitinbox = $this->data['limitinbox'];
        if (empty($limitinbox)){
            $this->valid = false;    
            $this->errors['limitinbox'] = 'Inbox limit must be set';            
        }         
    }
    
    public function checkLimitoutbox() {
        $limitoutbox = $this->data['limitoutbox'];
        if (empty($limitoutbox)){
            $this->valid = false;    
            $this->errors['limitoutbox'] = 'Outbox limit must be set';            
        }         
    }
    
    public function checkLimitarchive() {
        $limitarchive = $this->data['limitarchive'];
        if (empty($limitarchive)){
            $this->valid = false;    
            $this->errors['limitarchive'] = 'Archive limit must be set';            
        }         
    }
    
    public function checkPerpage() {
        $perpage = $this->data['perpage'];
        if (empty($perpage)){
            $this->valid = false;    
            $this->errors['perpage'] = 'Items per page limit must be set';            
        }         
    }        
}