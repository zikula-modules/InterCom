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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\IntercomModule\Entity\Traits\ConversationTrait;
use Zikula\IntercomModule\Entity\Traits\CreatedAtTrait;
use Zikula\IntercomModule\Entity\Traits\IdTrait;
use Zikula\IntercomModule\Entity\Traits\RecipientsTrait;
use Zikula\IntercomModule\Entity\Traits\SenderTrait;
use Zikula\IntercomModule\Entity\Traits\SentTrait;
use Zikula\IntercomModule\Entity\Traits\SubjectTrait;
use Zikula\IntercomModule\Entity\Traits\TextTrait;

/**
 * Message.
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
     * Module name.
     *
     * @var string
     */
    const MODULENAME = 'ZikulaIntercomModule';

    use IdTrait;
    use SubjectTrait;
    use TextTrait;
    use SentTrait;
    use SenderTrait;
    //need to be constructed
    use RecipientsTrait;
    use ConversationTrait;
    use CreatedAtTrait;

    /**
     * Message details.
     *
     * @ORM\OneToMany(targetEntity="Zikula\IntercomModule\Entity\MessageDetails\MessageUserDetailsEntity", mappedBy="message", cascade={"persist"})
     */
    private $messageUserData;

    private $userData;

    /**
     * Constructor.
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

    public function getUserData()
    {
        return $this->userData;
    }

    public function setUserData($userData)
    {
        $this->userData = $userData;

        return $this;
    }

    public function getMessageDataByUser($user)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('user', $user));

        $this->userData = $this->getMessageUserData()->matching($criteria)->first();

        return $this;
    }
}
