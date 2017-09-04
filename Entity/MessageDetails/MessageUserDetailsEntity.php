<?php
/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Entity\MessageDetails;

use Doctrine\ORM\Mapping as ORM;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\IntercomModule\Entity\Traits\IdTrait;
use Zikula\IntercomModule\Entity\Traits\ExtraDataTrait;
use Zikula\IntercomModule\Entity\Traits\UserTrait;

/**
 *  MessageUserDetails entity
 *
 * @ORM\Entity
 * @ORM\Table(name="intercom_message_user_details")
 */
class MessageUserDetailsEntity extends EntityAccess
{
    use IdTrait;
    use ExtraDataTrait;
    use UserTrait;

   /**
     * @ORM\ManyToOne(targetEntity="Zikula\IntercomModule\Entity\Message\AbstractMessageEntity", inversedBy="messageUserData")
     * @ORM\JoinColumn(name="message", referencedColumnName="id", nullable=false)
     */
    private $message;

    /**
     * @ORM\ManytoOne(targetEntity="Zikula\IntercomModule\Entity\Label\LabelEntity")
     * @ORM\JoinColumn(name="label", referencedColumnName="id")
     */
    private $label;

    /**
     * seen
     *
     * @ORM\Column(type="datetime", nullable=true, options={"default":null})
     */
    private $seen;

    /**
     * replied
     *
     * @ORM\Column(type="datetime", nullable=true, options={"default":null})
     */
    private $replied;

    /**
     * notified
     *
     * @ORM\Column(type="datetime", nullable=true, options={"default":null})
     */
    private $notified;

    /**
     * stored
     *
     * @ORM\Column(type="datetime", nullable=true, options={"default":null})
     */
    private $stored;

    /**
     * deleted
     *
     * @ORM\Column(type="datetime", nullable=true, options={"default":null})
     */
    private $deleted;


    public function __construct()
    {
        //@todo user should be validated on construct
        // because trait allows it to be null
    }

    /**
     * Set seen status
     *
     * @param  DateTime/null $seen
     * @return $this
     */
    public function setSeen(\DateTime $seen = null)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen status
     *
     * @return DateTime/null
     */
    public function getSeen()
    {
        return $this->seen;
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
     * Set stored status
     *
     * @param DateTime/null $stored
     * @return $this
     */
    public function setStored(\DateTime $stored = null)
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
        return $this->storedr;
    }

    /**
     * Set deleted status
     *
     * @param DateTime/null $deleted
     * @return $this
     */
    public function setDeleted(\DateTime $deleted = null)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted status
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }
}
