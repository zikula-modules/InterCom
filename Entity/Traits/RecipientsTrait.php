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

/**
 * Recipients Trait.
 *
 * @author Kaik
 */
trait RecipientsTrait
{
    /**
     * The recipient users.
     *
     * @ORM\OneToMany(targetEntity="Zikula\IntercomModule\Entity\Recipient\UserRecipientEntity", mappedBy="message", cascade={"persist", "remove"})
     */
    private $recipientUsers;

    /**
     * The recipient groups.
     *
     * @ORM\OneToMany(targetEntity="Zikula\IntercomModule\Entity\Recipient\GroupRecipientEntity", mappedBy="message")
     */
    private $recipientGroups;

    public function getRecipientUsers()
    {
        return $this->recipientUsers;
    }

    public function setRecipientUsers($recipientUsers)
    {
        $this->recipientUsers = $recipientUsers;

        return $this;
    }

    public function addRecipientUser($recipientUser)
    {
        if ($this->recipientUsers->contains($recipientUser)) {
            return;
        }

        $this->recipientUsers[] = $recipientUser;
        $recipientUser->setMessage($this);
    }

    public function removeRecipientUser($recipientUser)
    {
        $this->recipientUsers->removeElement($recipientUser);
    }

    public function getRecipientGroups()
    {
        return $this->recipientGroups;
    }

    public function setRecipientGroups($recipientGroups)
    {
        $this->recipientGroups = $recipientGroups;

        return $this;
    }
}
