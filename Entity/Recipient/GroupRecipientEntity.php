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
use Zikula\IntercomModule\Entity\Traits\GroupTrait;
use Zikula\IntercomModule\Entity\Traits\IdTrait;

/**
 * UserRecipientEntity.
 *
 * @ORM\Entity
 * @ORM\Table(name="intercom_message_recipient_groups")
 */
class GroupRecipientEntity extends EntityAccess
{
    use IdTrait;
    use GroupTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Zikula\IntercomModule\Entity\Message\AbstractMessageEntity", inversedBy="recipientGroups")
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
