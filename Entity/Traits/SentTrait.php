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
 * SentTrait.
 *
 * @author Kaik
 */
trait SentTrait
{
    /**
     * send.
     *
     * @ORM\Column(type="datetime", nullable=true, options={"default":null})
     */
    private $sent;

    /**
     * Set sent.
     *
     * @param DateTime object $sent
     *
     * @return $this
     */
    public function setSent(\DateTime $sent = null)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent.
     *
     * @return DateTime object
     */
    public function getSent()
    {
        return $this->sent;
    }
}
