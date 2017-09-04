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
 * Recipients Trait
 *
 * @author Kaik
 */
trait RecipientsTrait
{
    /**
     * The recipient users
     *
     * @ORM\OneToMany(targetEntity="Zikula\IntercomModule\Entity\Recipient\UserRecipientEntity", mappedBy="message")
     */
    private $recipientUsers;

    /**
     * The recipient groups
     *
     * @ORM\OneToMany(targetEntity="Zikula\IntercomModule\Entity\Recipient\GroupRecipientEntity", mappedBy="message")
     */
    private $recipientGroups;

    public function getRecipientUsers()
    {
        return $this->recipientUsers;
    }

    public function getRecipientGroups()
    {
        return $this->recipientGroups;
    }

    public function setRecipientUsers($recipientUsers)
    {
        $this->recipientUsers = $recipientUsers;
        
        return $this;
    }

    public function setRecipientGroups($recipientGroups)
    {
        $this->recipientGroups = $recipientGroups;

        return $this;
    }
}
