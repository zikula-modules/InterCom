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
use Zikula\IntercomModule\Manager\Messenger;
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
     * @var Messenger
     */
    private $messenger;

    /**
     * MessagesModuleBridge constructor.
     *
     * @param RouterInterface         $router
     * @param RequestStack            $requestStack
     * @param VariableApiInterface    $variableApi
     * @param CurrentUserApi          $currentUser
     * @param UserRepositoryInterface $userRepository
     * @param Messenger               $messenger
     */
    public function __construct(
        RouterInterface $router,
        RequestStack $requestStack,
        VariableApiInterface $variableApi,
        CurrentUserApi $currentUser,
        Messenger $messanger
    ) {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->variableApi = $variableApi;
        $this->currentUser = $currentUser;
        $this->messenger = $messanger;
    }

    public function getInboxUrl($uid = null)
    {
        return $this->router->generate('zikulaintercommodule_messages_getmessages', ['box' => 'inbox']);
    }

    public function getMessageCount($uid = null, $unreadOnly = false)
    {
        return $this->messenger->getMessenger($uid)->getMessagesCount($unreadOnly);
    }

    public function getSendMessageUrl($uid = null)
    {
        return $this->router->generate('zikulaintercommodule_messages_newmessage');
    }
}
