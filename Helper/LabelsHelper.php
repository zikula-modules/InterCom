<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Helper;

use Doctrine\ORM\EntityManager;

use Zikula\ExtensionsModule\Api\VariableApi;

/**
 * Description of LabelsHelper
 *
 * @author Kaik
 */
class LabelsHelper
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var VariableApi
     */
    private $variableApi;


    public function __construct(
            EntityManager $entityManager,
            VariableApi $variableApi
         ) {
        $this->name = 'ZikulaIntercomModule';
        $this->entityManager = $entityManager;
        $this->variableApi = $variableApi;

        return $this;
    }

    public function getAll()
    {
        return $this->entityManager->getRepository('Zikula\IntercomModule\Entity\Label\LabelEntity')->findAll();
    }

    public function add($label)
    {
        $labels = [];

        return $labels;
    }

    public function edit($label)
    {
        $labels = [];

        return $labels;
    }

    public function delete($label)
    {
        $labels = [];

        return $labels;
    }

    public function getDefaultLabels()
    {
        return $this->entityManager->getRepository('Zikula\IntercomModule\Entity\Label\LabelEntity')->findBy(['user' => null]);
    }

    public function getUserLabels($user)
    {
        return $this->entityManager->getRepository('Zikula\IntercomModule\Entity\Label\LabelEntity')->findBy(['user' => $user]);
    }

}
