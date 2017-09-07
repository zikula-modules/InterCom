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
 * ConversationTrait.
 *
 * @author Kaik
 */
trait ConversationTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="Zikula\IntercomModule\Entity\Message\AbstractMessageEntity", inversedBy="conversation")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id", nullable=true)
     **/
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Zikula\IntercomModule\Entity\Message\AbstractMessageEntity", mappedBy="parent", cascade={"persist", "remove"})
     * @ORM\OrderBy({"sent" = "ASC"})
     **/
    private $conversation;

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get the messages for conversation.
     *
     * @return array the conversation messages
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * Set the messages for conversation.
     *
     * @param array $conversation the child categories
     */
    public function setConversation($conversation)
    {
        $this->conversation = $conversation;
    }
}
