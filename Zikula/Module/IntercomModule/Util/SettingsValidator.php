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
        $this->checkLimitinbox();       
    }
    
    public function checkLimitinbox() {
        $limitinbox = $this->data['limitinbox'];
        if (empty($limitinbox)){
            $this->valid = false;    
            $this->errors['limitinbox'] = 'Inbox limit must be set';            
        }         
    }
       
}