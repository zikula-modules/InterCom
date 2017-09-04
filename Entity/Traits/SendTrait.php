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
 * Description of SendTrait
 *
 * @author Kaik
 */
trait SendTrait
{
    /**
     * send
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $send;

    /**
     * Set message send
     *
     * @param DateTime object $send
     * @return $this
     */
    public function setSend(\DateTime $send)
    {
        $this->send = $send;

        return $this;
    }

    /**
     * Get message send
     *
     * @return DateTime object
     */
    public function getSend()
    {
        return $this->send;
    }
}
