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
 * SubjectTrait
 *
 * @author Kaik
 */
trait SubjectTrait
{
    /**
     * subject
     *
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     */
    private $subject;

    /**
     * Set subject
     *
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get message subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * get reply subject
     *
     * @param array $conversation the child categories
     */
    public function getReplySubject()
    {
        return 'Re: ' . $this->subject . '';

    }
}
