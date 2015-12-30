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

namespace Zikula\IntercomModule\Manager;

use Zikula\IntercomModule\Entity\MessageEntity;

class Message {

    private $name;
    private $_message;
    private $_new;
    public $entityManager;

    /**
     * construct
     */
    public function __construct($entityManager) {
        $this->name = 'ZikulaIntercomModule';
        $this->entityManager = $entityManager;
    }

    /**
     * create new blank message
     *
     */
    public function create() {
        $this->_message = new MessageEntity();
    }

    /**
     * load message from database
     *
     */
    public function load($p) {
        $this->_message = $this->entityManager
                ->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
                ->getOneBy($p);
    }

    /**
     * set new message array
     *
     * @return boolean
     */
    public function setNewData($p) {
        $this->_new = $p;
        return true;
    }

    /**
     * return new message array
     *
     * @return boolean
     */
    public function getNewData() {
        return $this->_new;
    }

    /**
     * return message id
     *
     * @return array
     */
    public function getId() {
        return $this->_message->getId();
    }

    /**
     * return message as doctrine2 object
     *
     * @return object
     */
    public function get() {
        return $this->_message;
    }

    /**
     * return message as array
     *
     * @return mixed array or false
     */
    public function toArray() {
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
    public function exist() {
        return (!$this->_message) ? false : true;
    }

    /**
     * return validation status
     *
     * @return boolean
     */
    public function isValid() {
        return true;//return $this->validator->isValid();
    }

    /**
     * return errors array
     *
     * @return array
     */
    public function getErrors() {
        //return $this->validator->getErrors();
    }

    /**
     * set seen status
     *
     * @return boolean
     */
    public function setSeen() {
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
    public function prepareForReply() {
        $reply = array();
        $reply['id'] = $this->_message->getId();
        $reply['sender'] = $this->_message->getRecipient()->toArray();
        $reply['recipient'] = $this->_message->getSender()->toArray();
        $reply['subject'] = __('Re:') . ' ' . $this->_message->getSubject();
        $reply['text'] = __('Text') . ' ' . $this->_message->getText();
        return $reply;
    }

    /**
     * return forward message array
     *
     * @return array
     */
    public function prepareForForward() {
        $reply = array();
        $reply['id'] = $this->_message->getId();
        $reply['sender'] = $this->_message->getRecipient()->toArray();
        $reply['recipient'] = '';
        $reply['subject'] = __('Fwd:') . ' ' . $this->_message->getSubject();
        $reply['text'] = __('Text') . ' ' . $this->_message->getText();
        return $reply;
    }

    /**
     * perform send
     *
     * @return boolean
     */
    public function send() {
        $this->create();
        return $this->save();
    }

    /**
     * perform reply
     *
     * @return boolean
     */
    public function reply() {
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
     * 
     * @todo Implement new storage system
     */
    public function store() {
        $recipient = $this->_message->getRecipient()->getUid() == UserUtil::getVar('uid') ? $this->_message->setStoredbyrecipient(1) : 1;
        $sender = $this->_message->getSender()->getUid() == UserUtil::getVar('uid') ? $this->_message->setStoredbysender(1) : 1;
        $this->entityManager->persist($this->_message);
        $this->entityManager->flush();
        return ($recipient || $sender);
    }

    /**
     * perform delete
     *
     * @return boolean
     */
    public function delete() {
        $recipient = $this->_message->getRecipient()->getUid() == UserUtil::getVar('uid') ? $this->_message->setDeletedbyrecipient(1) : 1;
        $sender = $this->_message->getSender()->getUid() == UserUtil::getVar('uid') ? $this->_message->setDeletedbysender(1) : 1;
        $this->entityManager->persist($this->_message);
        $this->entityManager->flush();
        return ($recipient || $sender);
    }

    /**
     * perform forward
     *
     * @return boolean
     */
    public function forward() {
        $this->create();
        return $this->save();
    }

    /**
     * prepare for save
     *
     * @return boolean
     */
    public function prepareForSave() {
        unset($this->_new['id']);
        unset($this->_new['recipients']);
        unset($this->_new['multiple']);
    }

    /**
     * perform save
     *
     * @return boolean
     */
    public function save() {
        if (!$this->getId() && $this->_new && $this->isValid()) {
            $this->prepareForSave();
            $this->_message->merge($this->_new);
            $this->entityManager->persist($this->_message);
            $this->entityManager->flush();
            return true;
        }
        if ($this->getId() && $this->_new && $this->isValid()) {
            $this->prepareForSave();
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
    public function remove() {
        $this->entityManager->remove($this->_message);
        $this->entityManager->flush();
        return true;
    }

    /**
     * remove message compleatly
     *
     * @return boolean
     */
    public function isMultiple() {
        if (is_array($this->_new['multiple'])) {
            return true;
        }
        return false;
    }

    /**
     * remove message compleatly
     *
     * @return boolean
     */
    public function sendMultiple() {
        $multiple = $this->_new['multiple'];
        unset($this->_new['multiple']);
        unset($this->_new['recipients']);
        foreach ($multiple as $key => $recipient) {
            $this->_new['recipient'] = $recipient;
            $this->send();
        }
        return true;
    }

    /**
     * Edit field.
     */
    public function editField($args) {
        if (!isset($args['id']) || !is_numeric($args['id'])) {
            throw new \InvalidArgumentException(__('Invalid arguments array received'));
        }
        if (!isset($args['field']) || !is_string($args['field'])) {
            throw new \InvalidArgumentException(__('Invalid arguments array received'));
        }
        $id = $args['id'];
        $field = $args['field'];
        $value = $args['value'];

        $item = $this->entityManager->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
                ->getOneBy(array('id' => $id));

        if (!$item) {
            return false;
        }
        switch ($field) {
            case 'deletedbysender':
                $item->setInbox($value);
                break;
            case 'deletedbyrecipient':
                $item->setOutbox($value);
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
