<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Entity\Recipient;

use Doctrine\ORM\Mapping as ORM;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\IntercomModule\Entity\Traits\IdTrait;
use Zikula\IntercomModule\Entity\Traits\UserTrait;

/**
 * UserRecipientEntity
 *
 * @ORM\Entity
 * @ORM\Table(name="intercom_message_recipient_users")
 */
class UserRecipientEntity extends EntityAccess
{
    use IdTrait;
    use UserTrait;

   /**
     * @ORM\ManyToOne(targetEntity="Zikula\IntercomModule\Entity\Message\AbstractMessageEntity", inversedBy="recipientUsers")
     * @ORM\JoinColumn(name="message", referencedColumnName="id", nullable=false)
     */
    private $message;

    public function __construct()
    {

    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
