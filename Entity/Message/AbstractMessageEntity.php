<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Entity\Message;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\IntercomModule\Entity\Traits\IdTrait;
use Zikula\IntercomModule\Entity\Traits\SubjectTrait;
use Zikula\IntercomModule\Entity\Traits\TextTrait;
use Zikula\IntercomModule\Entity\Traits\SendTrait;
use Zikula\IntercomModule\Entity\Traits\SenderTrait;
use Zikula\IntercomModule\Entity\Traits\RecipientsTrait;
use Zikula\IntercomModule\Entity\Traits\ConversationTrait;

/**
 * Message
 *
 * @ORM\Table(name="intercom_messages")
 * @ORM\Entity(repositoryClass="Zikula\IntercomModule\Entity\Repository\MessagesRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="mtype")
 * @ORM\DiscriminatorMap({"normal" = "NormalEntity",
 *                         "system" = "SystemEntity",
 *                         "notification" = "NotificationEntity"})
 */
abstract class AbstractMessageEntity extends EntityAccess
{
    /**
     * Module name
     *
     * @var string
     */
    const MODULENAME = 'ZikulaIntercomModule';

    use IdTrait;
    use SubjectTrait;
    use TextTrait;
    use SendTrait;
    use SenderTrait;
    //need to be constructed
    use RecipientsTrait;
    use ConversationTrait;

    /**
     * Message details
     *
     * @ORM\OneToMany(targetEntity="Zikula\IntercomModule\Entity\MessageDetails\MessageUserDetailsEntity", mappedBy="message")
     */
    private $messageUserData;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->conversation = new ArrayCollection();
        $this->recipientUsers = new ArrayCollection();
        $this->recipientGroups = new ArrayCollection();
        $this->messageUserData = new ArrayCollection();
    }

    public function getMessageUserData()
    {
        return $this->messageUserData;
    }

    public function setMessageUserData($messageUserData)
    {
        $this->messageUserData = $messageUserData;

        return $this;
    }

    public function getMtype()
    {
        return $this->mtype;
    }
}