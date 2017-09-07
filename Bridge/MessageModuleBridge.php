<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Bridge;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\UsersModule\Api\CurrentUserApi;
use Zikula\UsersModule\MessageModule\MessageModuleInterface;

/**
 * MessagesModuleBridge.
 *
 * @author Kaik
 */
class MessageModuleBridge implements MessageModuleInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var VariableApi
     */
    private $variableApi;

    /**
     * @var CurrentUserApi
     */
    private $currentUser;

    /**
     * MessagesModuleBridge constructor.
     *
     * @param RouterInterface         $router
     * @param RequestStack            $requestStack
     * @param VariableApiInterface    $variableApi
     * @param CurrentUserApi          $currentUser
     * @param UserRepositoryInterface $userRepository
     * @param string                  $prefix
     */
    public function __construct(
        RouterInterface $router,
        RequestStack $requestStack,
        VariableApiInterface $variableApi,
        CurrentUserApi $currentUser
    ) {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->variableApi = $variableApi;
        $this->currentUser = $currentUser;
    }

    public function getInboxUrl($uid = null)
    {
    }

    public function getMessageCount($uid = null, $unreadOnly = false)
    {
    }

    public function getSendMessageUrl($uid = null)
    {
    }
}
