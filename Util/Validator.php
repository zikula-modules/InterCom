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
use ModUtil;
use UserUtil;
use ServiceUtil;

class Validator {

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
    
    public function checkSender() {
        
        $sender = $this->data['sender'];
        if (empty($sender) || !is_numeric($sender)){
            $this->valid = false;    
            $this->errors['sender'] = 'Sender cannot be empty';
            $this->data['sender']['uid'] = -1;
            return;    
        }
        
        // get entity manager
        $em = ServiceUtil::get('doctrine.entitymanager');
        $exist = $em->find('Zikula\Module\UsersModule\Entity\UserEntity', $sender);
        if (!$exist) {
            $this->valid = false;    
            $this->errors['sender'] = 'Sender not found';
            $this->data['sender']['uid'] = -1;
        return;    
        }else {
            $this->data['sender'] = $exist;             
        }  
    }
    
    public function checkRecipient() {
        
        $recipients = $this->data['recipients'];
        if (empty($recipients)){
            $this->valid = false;    
            $this->errors['recipient'] = 'Recipient cannot be empty';
            return;    
        }
        // get entity manager
        $em = ServiceUtil::get('doctrine.entitymanager');
        
        if ($recipients['groups'] !== ''){
          //handle groups here
            $this->handleGroups();
            return;
        }
        unset($recipients['groups']);    
        //usernames 
        if (isset($recipients['names'])){
            $recipients['names'] = str_replace(' ', '', $recipients['names']);
            $uname_array = explode(',', $recipients['names']);
            $this->data['recipients']['names'] = '';
            if(count($uname_array) > 0){               
                foreach ($uname_array as $key => $uname) {
                $oneuid[$key] = UserUtil::getIdFromName($uname);               
                $exist[$key] = $em->find('Zikula\Module\UsersModule\Entity\UserEntity', $oneuid[$key]);
                if (!$exist[$key]) {
                        $this->valid = false;
                        $this->data['recipients']['names'] == ''
                        ? $this->data['recipients']['names'] .= '!'.$uname
                        : $this->data['recipients']['names'] .= ',!'.$uname;
                        $this->errors['recipient'] = 'Recipients with exclamation sign not found';   
                } else {
                    if (count($uname_array) >1){
                        $this->data['recipients']['names'] == ''
                        ? $this->data['recipients']['names'] .= ''.$uname
                        : $this->data['recipients']['names'] .= ','.$uname;                    
                        $this->data['multiple'][$key] = $exist[$key];               
                    }else{
                        $this->data['recipients']['names'] = $uname;                    
                        $this->data['recipient'] = $exist[$key];
                        $this->data['multiple'] = false;
                    //var_dump($this->data['recipient']);
                    //exit(0);                         
                    }                          
                }             
                }
              return; 
            }           
        }
        $this->valid = false;    
        $this->errors['recipient'] = 'Recipient cannot be empty';
        return;         
    }
    
    public function handleGroups() {

                // get entity manager
        $em = ServiceUtil::get('doctrine.entitymanager');
        $recipients = $this->data['recipients'];
        $recipients['groups'] = str_replace(' ', '', $recipients['groups']);
            $groups_array = explode(',', $recipients['groups']);
            $this->data['recipients']['groups'] = '';
            if(count($groups_array) > 0){               
                foreach ($groups_array as $key => $gname) {
                $exist[$key] = ModUtil::apiFunc('Groups', 'admin', 'getgidbyname', array ('name' => $gname));
                if (!$exist[$key]) {
                        $this->valid = false;
                        $this->data['recipients']['groups'] == ''
                        ? $this->data['recipients']['groups'] .= '!'.$gname
                        : $this->data['recipients']['groups'] .= ',!'.$gname;
                        $this->errors['group'] = 'Groups with exclamation sign not found';   
                } else {
                        $this->data['recipients']['groups'] == ''
                        ? $this->data['recipients']['groups'] .= ''.$gname
                        : $this->data['recipients']['groups'] .= ','.$gname;                                       
                        $uids = \UserUtil::getUsersForGroup($exist[$key]);
                        foreach ($uids as $ukey => $userid){
                        $user[$ukey] = $em->find('Zikula\Module\UsersModule\Entity\UserEntity', $userid);                            
                        $this->data['multiple'][$ukey] = $user[$ukey];    
                        }
                }             
                }
              return;
        }     
        $this->valid = false;    
        $this->errors['recipient'] = 'Recipient cannot be empty';
        return;   
    }
}
