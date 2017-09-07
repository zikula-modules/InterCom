<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SenderTrait.
 *
 * @author Kaik
 */
trait SenderTrait
{
    /**
     * The sender user.
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Zikula\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(name="sender", referencedColumnName="uid")
     */
    private $sender;

    /**
     * The send as group.
     *
     * @ORM\ManyToOne(targetEntity="Zikula\GroupsModule\Entity\GroupEntity")
     * @ORM\JoinColumn(name="sendAsGroup", referencedColumnName="gid", nullable=true)
     */
    private $sendAsGroup;

    /**
     * Set sender.
     *
     * @param UserEntity $sender
     *
     * @return User
     */
    public function setSender(\Zikula\UsersModule\Entity\UserEntity $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender.
     *
     * @return Sender user object
     */
    public function getSender()
    {
        return $this->sender;
    }

    public function getSendAsGroup()
    {
        return $this->sendAsGroup;
    }

    public function setSendAsGroup($sendAsGroup = null)
    {
        $this->sendAsGroup = $sendAsGroup;

        return $this;
    }
}
