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
 * message regarding copyright.
 */

namespace Zikula\Module\IntercomModule\Util;

use ModUtil;
use ServiceUtil;
use DataUtil;
use UserUtil;
use Zikula\Module\IntercomModule\Util\Validator;
use Zikula\Module\IntercomModule\Entity\MessageEntity;


class Message {

  private $name;
  private $_message;
  private $_new;
  private $validator;
  public  $entityManager;

    /**
     * construct
     */
    public function __construct()
    {
        $this->name = 'ZikulaIntercomModule';
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
        $this->validator = new Validator();
        
    }

    /**
     * create new blank message
     *
     */
    public function create()
    {
        $this->_message = new MessageEntity();
    }
    
    /**
     * load message from database
     *
     */
    public function load($p)
    {
        $this->_message = $this->entityManager
            ->getRepository('Zikula\Module\IntercomModule\Entity\MessageEntity')
            ->getOneBy($p);
    }    
    
    /**
     * set new message array
     *
     * @return boolean
     */
    public function setNewData($p)
    {
        $this->validator->setData($p);
        $this->_new = $this->validator->getData();
        return true;
    }
    
    /**
     * return new message array
     *
     * @return boolean
     */
    public function getNewData()
    {
        return $this->_new;   
    }    
 
    /**
     * return message id
     *
     * @return array
    */
    public function getId()
    {
        return $this->_message->getId();
    }
   
    /**
     * return message as doctrine2 object
     *
     * @return object
     */
    public function get()
    {
        return $this->_message;
    }
    
    /**
     * return message as array
     *
     * @return mixed array or false
    */ 
    public function toArray()
    {
        if (!$this->exist()) {
        return false;
        }
        return $this->_message->toArray();
    }
    
    /**
     * return exist status
     *
     * @return boolean
     */
    public function exist()
    {
        return (!$this->_message) ? false : true ;
    }
    
    /**
     * return validation status
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->validator->isValid();
    }
    
    /**
     * return errors array
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->validator->getErrors();
    }
    
    /**
     * set seen status
     *
     * @return boolean
     */
    public function setSeen()
    {
       $this->_message->setSeen(new \DateTime('now'));
       $this->entityManager->persist($this->_message);
       $this->entityManager->flush();        
       return true; 
    }
    
    /**
     * return reply message array
     *
     * @return array
     */
    public function prepareForReply()
    {
        $reply = array();
        $reply['id'] = $this->_message->getId();        
        $reply['sender'] = $this->_message->getRecipient()->toArray();
        $reply['recipient'] = $this->_message->getSender()->toArray();
        $reply['subject'] = __('Re:').' '.$this->_message->getSubject();
        $reply['text'] = __('Text').' '.$this->_message->getText();
        return $reply;
    }
    
    /**
     * return forward message array
     *
     * @return array
     */
    public function prepareForForward()
    {
        $reply = array();
        $reply['id'] = $this->_message->getId();        
        $reply['sender'] = $this->_message->getRecipient()->toArray();
        $reply['recipient'] = '';
        $reply['subject'] = __('Fwd:').' '.$this->_message->getSubject();
        $reply['text'] = __('Text').' '.$this->_message->getText();
        return $reply;
    }    
    /**
     * perform send
     *
     * @return boolean
     */
    public function send()
    {
        $this->create(); 
        return $this->save(); 
    }  
    /**
     * perform reply
     *
     * @return boolean
     */
    public function reply()
    {
        $this->_message->setReplied(new \DateTime('now'));
        $this->entityManager->persist($this->_message);
        $this->entityManager->flush();
        $this->create(); 
        return $this->save(); 
    }
    
    /**
     * perform store
     *
     * @return boolean
     */
    public function store()
    {
        $inbox = $this->_message->getRecipient()->getUid() == UserUtil::getVar('uid') ? $this->_message->setInbox(0):1;
        $outbox = $this->_message->getSender()->getUid() == UserUtil::getVar('uid') ? $this->_message->setOutbox(0):1;        
        $this->_message->setStored(1);
        $this->entityManager->persist($this->_message);
        $this->entityManager->flush();
        return true;
    }
    
    /**
     * perform delete
     *
     * @return boolean
     */
    public function delete()
    {
        $inbox = $this->_message->getRecipient()->getUid() == UserUtil::getVar('uid') ? $this->_message->setInbox(0):1;
        $outbox = $this->_message->getSender()->getUid() == UserUtil::getVar('uid') ? $this->_message->setOutbox(0):1;
        $this->entityManager->persist($this->_message);
        $this->entityManager->flush();
        return ($inbox || $outbox);
    }     
    
    /**
     * perform forward
     *
     * @return boolean
     */
    public function forward()
    {
        $this->create(); 
        return $this->save();  
    }
    
    /**
     * perform save
     *
     * @return boolean
     */
    public function save()
    {  
        if (!$this->getId() && $this->_new && $this->isValid()){
            unset($this->_new['id']);            
            $this->_message->merge($this->_new);
            $this->entityManager->persist($this->_message);
            $this->entityManager->flush();
            return true;
        }
        if ($this->getId() && $this->_new && $this->isValid()){
            unset($this->_new['id']);
            $this->_message->merge($this->_new);
            $this->entityManager->flush();
            return true;
        }            
    }
    
    /**
     * remove message compleatly
     *
     * @return boolean
     */
    public function remove()
    {
        $this->entityManager->remove($this->_message);
        $this->entityManager->flush();
        return true;
    }
    
    /**
     * Edit field.
     */
    public function editField($args)
    {
        if (!isset($args['id']) || !is_numeric($args['id'])) {
            throw new \InvalidArgumentException(__('Invalid arguments array received'));
        }
        
        if (!isset($args['field']) || !is_string($args['field'])) {
            throw new \InvalidArgumentException(__('Invalid arguments array received'));
        }
        
        $id = $args['id'];
        $field = $args['field'];
        $value = $args['value'];
        
        $em = \ServiceUtil::get('doctrine.entitymanager');
        $item = $em->getRepository('Zikula\Module\IntercomModule\Entity\MessageEntity')
            ->getOneBy(array('id' => $id));
        
        if (!$item){
            return false;    
        }
        switch($field){
            case 'inbox':
            $item->setInbox($value);
            break;
            case 'outbox':
            $item->setOutbox($value);
            break;            
            case 'stored':
            $item->setStored($value);
            break;
            case 'notified':
            $item->setNotified(new \DateTime());
            break;
            case 'seen':
            $item->setSeen(new \DateTime());
            break;
            case 'replied':
            $item->setReplied(new \DateTime());
            break;        
        }    
        $this->entityManager->flush();
        return true; 
    }
}
