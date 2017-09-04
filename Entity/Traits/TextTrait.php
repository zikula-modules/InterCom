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
 * TextTrait
 *
 * @author Kaik
 */
trait TextTrait
{
    /**
     * text
     *
     * @ORM\Column(type="text", nullable=true, options={"default":null})
     */
    private $text;

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
     * get reply text
     *
     * @param array $conversation the child categories
     */
    public function getReplyText()
    {
        return '--------- ' . $this->text . ' ----------';
    }
}
