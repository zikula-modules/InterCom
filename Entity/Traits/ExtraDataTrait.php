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
 * ExtraData Trait
 *
 * @author Kaik
 */
trait ExtraDataTrait
{
    /**
     *
     * @ORM\Column(type="array", nullable=true, options={"default":null})
     */
    private $extraData;


    public function getExtraData()
    {
        return $this->extraData;
    }

    public function setExtraData($extraData)
    {
        $this->extraData = $extraData;

        return $this;
    }
}
