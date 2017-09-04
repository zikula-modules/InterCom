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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IdTrait
 *
 * @author Kaik
 */
trait IdTrait {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     * @Assert\LessThan(value=1000000000, message="Length of field value must not be higher than 9.")) {
     * @var integer $id.
     */
    protected $id = 0;

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id) {
        $this->id = $id;
    }
}
