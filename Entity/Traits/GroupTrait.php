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
use Zikula\GroupsModule\Entity\GroupEntity as ZikulaGroup;

/**
 * GroupTrait
 *
 * @author Kaik
 */
trait GroupTrait
{
    /**
     * Zikula Core Group Entity
     *
     * @ORM\ManyToOne(targetEntity="Zikula\GroupsModule\Entity\GroupEntity")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="gid")
     */
    private $group;

    /**
     * get Core Group
     *
     * @return \Zikula\GroupsModule\Entity\GroupEntity
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * set group
     * @param \Zikula\GroupsModule\Entity\GroupEntity $group
     */
    public function setGroup(ZikulaGroup $group)
    {
        $this->group = $group;
    }
}
