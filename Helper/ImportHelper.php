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
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\ExtensionsModule\Api\VariableApi;

/**
 * ImportHelper.
 *
 * @author Kaik
 */
class ImportHelper
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var VariableApi
     */
    private $variableApi;

    public function __construct(
            RequestStack $requestStack,
            EntityManager $entityManager,
            VariableApi $variableApi
         ) {
        $this->name = 'ZikulaIntercomModule';
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();
        $this->entityManager = $entityManager;
        $this->variableApi = $variableApi;
    }

    public function isUpgrade()
    {
        return $this->variableApi->get('ZikulaIntercomModule', 'upgrading', false);
    }
}
