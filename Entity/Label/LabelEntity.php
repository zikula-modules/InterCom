<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Entity\Label;

use Doctrine\ORM\Mapping as ORM;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\IntercomModule\Entity\Traits\ExtraDataTrait;
use Zikula\IntercomModule\Entity\Traits\IdTrait;
use Zikula\IntercomModule\Entity\Traits\NameTrait;
use Zikula\IntercomModule\Entity\Traits\SortOrderTrait;
use Zikula\IntercomModule\Entity\Traits\UserTrait;

/**
 * Label entity class.
 *
 * @ORM\Entity
 * @ORM\Table(name="intercom_labels")
 * @ORM\Entity(repositoryClass="Zikula\IntercomModule\Entity\Repository\LabelsRepository")
 */
class LabelEntity extends EntityAccess
{
    use IdTrait;
    use NameTrait;
    use UserTrait;
    use ExtraDataTrait;
    use SortOrderTrait;

    public function __construct()
    {
    }

    /**
     * Get url name.
     *
     * @return string
     */
    public function getUrlName()
    {
        return urlencode(strtolower($this->name).'_'.$this->id);
    }
}
