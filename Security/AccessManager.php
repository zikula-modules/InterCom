<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\PermissionsModule\Api\PermissionApi;

/**
 * AccessManager.
 *
 * @author Kaik
 */
class AccessManager
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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var PermissionApi
     */
    private $permissionApi;

    /**
     * @var VariableApi
     */
    private $variableApi;

    private $user;

    public function __construct(
        RequestStack $requestStack,
        EntityManager $entityManager,
        TranslatorInterface $translator,
        PermissionApi $permissionApi,
        VariableApi $variableApi
    ) {
        $this->name = 'ZikulaIntercomModule';
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->permissionApi = $permissionApi;
        $this->variableApi = $variableApi;
        $this->user = $this->request->getSession()->get('uid') > 1 ? $this->request->getSession()->get('uid') : 1;
    }

    /*
     * Do all user checks in one method:
     * Check if logged in, has correct access, and if site is disabled
     * Returns the appropriate error/return value if failed, which can be
     *          returned by calling method.
     * Returns false if use has permissions.
     * On exit, $uid has the user's UID if logged in.
     */
    public function hasPermission($access = ACCESS_READ)
    {
        // If not logged in, redirect to login screen
        if ($this->user <= 1) {
            return false;
        }

        // Return user uid to signify everything is OK.
        return $this->user;
    }

    public function hasPermissionRaw($component, $instance, $level)
    {
        return $this->permissionApi->hasPermission($this->name.'::', $component.'::'.$instance, $level);
    }
}
