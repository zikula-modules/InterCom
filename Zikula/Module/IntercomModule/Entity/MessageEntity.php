<?php

/**
 * Intercom
 *
 * @copyright (c) 2001-now, Intercom Development Team
 * @link https://github.com/zikula-modules/Intercom
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Intercom
 */

namespace Zikula\Module\IntercomModule\Entity;

use ServiceUtil;
use ModUtil;
use DateTime;
use UserUtil;
use System;
use Zikula\Core\Doctrine\EntityAccess;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Message entity class
 *
 * @ORM\Entity
 * @ORM\Table(name="intercom")
 * @ORM\Entity(repositoryClass="Zikula\Module\IntercomModule\Entity\Repository\MessageRepository")
 */
class MessageEntity extends EntityAccess
{
    /**
     * Module name
     * @var string
     */
    const MODULENAME = 'ZikulaIntercomModule';

    /**
     * id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The sender user
     *     
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Zikula\Module\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(name="sender", referencedColumnName="uid")
     */
    private $sender;

    /**
     * The recipient uid
     *     
     * @ORM\ManyToOne(targetEntity="Zikula\Module\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(name="recipient", referencedColumnName="uid")
     */
    private $recipient;
    
    /**
     * subject
     *
     * @ORM\Column(type="string", length=255)
     */
    private $subject = '';    

    /**
     * time
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $time;

    /**
     * text
     *
     * @ORM\Column(type="text")
     */
    private $text = '';
    
    /**
     * read
     *
     * @ORM\Column(type="datetime")type="datetime", nullable=true, options={"default":null})
     */
    private $read;

    /**
     * replied
     *
     * @ORM\Column(type="datetime")type="datetime", nullable=true, options={"default":null})
     */
    private $replied;
    
    /**
     * notified
     *
     * @ORM\Column(type="boolean")
     */
    private $notified;
    
    /**
     * inbox
     *
     * @ORM\Column(type="boolean")
     */
    private $inbox;
    
    /**
     * outbox
     *
     * @ORM\Column(type="boolean")
     */
    private $outbox;
    
    /**
     * stored
     *
     * @ORM\Column(type="boolean")
     */
    private $stored;

    /**
     * Constructor
     */
    public function __construct()
    {

    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set sender
     *
     * @param UserEntity $sender
     * @return User
     */
    public function setSender(\Zikula\Module\UsersModule\Entity\UserEntity $sender = null)
    {
        $this->sender = $sender;  
        return $this;
    }
    
    /**
     * Get sender
     *
     * @return Sender user object 
     */
    public function getSender()
    {
        return $this->sender;
    }
    
    /**
     * Set recipient
     *
     * @param UserEntity $recipient
     * @return User
     */
    public function setRecipient(\Zikula\Module\UsersModule\Entity\UserEntity $recipient = null)
    {
        $this->recipient = $recipient;  
        return $this;
    }
    
    /**
     * Get recipient
     *
     * @return Recipient user object 
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
    /**
     * Set subject
     *
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;  
        return $this;
    }
    
    /**
     * Get message subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
     * Set message time
     *
     * @param DateTime object $time
     * @return $this
     */
    public function setTime(\DateTime $time)
    {
        $this->time = $time;  
        return $this;
    }
    
    /**
     * Get message time
     *
     * @return DateTime object 
     */
    public function getTime()
    {
        return $this->time;
    }
    
    /**
     * Set message text
     *
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;  
        return $this;
    }
    
    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * Set read status
     *
     * @param  DateTime/null $read
     * @return $this
     */
    public function setRead(\DateTime $read = null)
    {
        $this->read = $read;  
        return $this;
    }
    
    /**
     * Get read status
     *
     * @return DateTime/null 
     */
    public function getRead()
    {
        return $this->read;
    }
    
    /**
     * Set replied status
     *
     * @param DateTime $replied
     * @return $this
     */
    public function setReplied(\DateTime $replied = null)
    {
        $this->replied = $replied;  
        return $this;
    }
    
    /**
     * Get replied status
     *
     * @return DateTime/null 
     */
    public function getReplied()
    {
        return $this->replied;
    }
    
    /**
     * Set notified status
     *
     * @param DateTime/null $notified
     * @return $this
     */
    public function setNotified(\DateTime $notified = null)
    {
        $this->notified = $notified;  
        return $this;
    }
    
    /**
     * Get notified status
     *
     * @return DateTime/null 
     */
    public function getNotified()
    {
        return $this->notified;
    }
    
    /**
     * Set inbox status
     *
     * @param $inbox
     * @return $this
     */
    public function setInbox($inbox)
    {
        $this->inbox = $inbox;  
        return $this;
    }
    
    /**
     * Get inbox status
     *
     * @return boolean 
     */
    public function getInbox()
    {
        return $this->inbox;
    }
    
    /**
     * Set outbox status
     *
     * @param $outbox
     * @return $this
     */
    public function setOutbox($outbox)
    {
        $this->outbox = $outbox;  
        return $this;
    }
    
    /**
     * Get outbox
     *
     * @return boolean  
     */
    public function getOutbox()
    {
        return $this->outbox;
    } 
    
    /**
     * Set stored status
     *
     * @param $stored
     * @return $this
     */
    public function setStored($stored)
    {
        $this->stored = $stored;  
        return $this;
    }
    
    /**
     * Get stored status
     *
     * @return boolean 
     */
    public function getStored()
    {
        return $this->stored;
    }     

}
