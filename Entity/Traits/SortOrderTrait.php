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
 * OrderTrait.
 *
 * @author Kaik
 */
trait SortOrderTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="sortorder", type="smallint", nullable=false)
     */
    private $sortorder;

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Entity
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder.
     *
     * @return int
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }
}
