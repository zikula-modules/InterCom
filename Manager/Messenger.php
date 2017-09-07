<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\PermissionsModule\Api\PermissionApi;
use Zikula\UsersModule\Api\CurrentUserApi;

/**
 * MessageManager.
 *
 * @author Kaik
 */
class Messenger
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
     * @var CurrentUserApi
     */
    private $userApi;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    protected $name;

    private $messages;

    private $filters;

    /**
     * Construct the manager.
     *
     * @param TranslatorInterface $translator
     * @param RouterInterface     $router
     * @param RequestStack        $requestStack
     * @param EntityManager       $entityManager
     * @param CurrentUserApi      $userApi
     * @param PermissionApi       $permission
     * @param VariableApi         $variableApi
     */
    public function __construct(
        TranslatorInterface $translator,
        RouterInterface $router,
        RequestStack $requestStack,
        EntityManager $entityManager,
        CurrentUserApi $userApi,
        PermissionApi $permission,
        VariableApi $variableApi
    ) {
        $this->name = 'ZikulaDizkusModule';
        $this->translator = $translator;
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();
        $this->entityManager = $entityManager;
        $this->userApi = $userApi;
        $this->permission = $permission;
        $this->variableApi = $variableApi;
    }

    /**
     * Get manager.
     *
     * @param int $uid user id (optional: defaults to current user)
     */
    public function getMessenger()
    {
        return $this;
    }

    /**
     *  load messages.
     */
    public function load($box, $filters)
    {
        if (!$this->userApi->isLoggedIn()) {
            return $this;
        }

        $user = $this->entityManager->getRepository('Zikula\UsersModule\Entity\UserEntity')->findOneBy(['uid' => $this->userApi->get('uid')]);

        $this->filters = $filters;
        switch ($box) {
            case 'inbox':
                $this->messages = $this->entityManager
                ->getRepository('Zikula\IntercomModule\Entity\Message\AbstractMessageEntity')
                ->getRecivedMessagesByUser($user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page'] = 1);

                break;
            case 'sent':
                $this->messages = $this->entityManager
                ->getRepository('Zikula\IntercomModule\Entity\Message\AbstractMessageEntity')
                ->getSentMessagesByUser($user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page'] = 1);

                break;
            case 'stored':
                $this->messages = $this->entityManager
                ->getRepository('Zikula\IntercomModule\Entity\Message\AbstractMessageEntity')
                ->getStoredMessagesByUser($user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page'] = 1);

                break;
            case 'draft':
                $this->messages = $this->entityManager
                ->getRepository('Zikula\IntercomModule\Entity\Message\AbstractMessageEntity')
                ->getDraftMessagesByUser($user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page'] = 1);
                break;
            case 'trash':
                $this->messages = $this->entityManager
                ->getRepository('Zikula\IntercomModule\Entity\Message\AbstractMessageEntity')
                ->getDeletedMessagesByUser($user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page'] = 1);

                break;
            case 'labels':
                $this->messages = $this->entityManager
                ->getRepository('Zikula\IntercomModule\Entity\Message\AbstractMessageEntity')
                ->getLabeledMessagesByUser($user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page'] = 1, $filters['label']);

                break;
            default:
                break;
        }

        return $this;
    }

    /**
     *  get messages.
     */
    public function getmessages()
    {
        return $this->messages;
    }

    /**
     *  get messages.
     */
    public function getmessages_array()
    {
        $messages_array = [];
        foreach ($this->messages as $key => $message) {
            $messages_array[$key] = $message;
        }

        return $messages_array;
    }

    /**
     *  get messages.
     */
    public function loadUserData()
    {
        if (!$this->userApi->isLoggedIn()) {
            return $this;
        }

        $user = $this->entityManager->getRepository('Zikula\UsersModule\Entity\UserEntity')->findOneBy(['uid' => $this->userApi->get('uid')]);
        foreach ($this->messages as $message) {
            $message->getMessageDataByUser($user);
        }

        return $this;
    }

    /**
     *  get messages count.
     */
    public function getmessages_count()
    {
        return $this->messages->count();
    }

    /**
     *  get messages count.
     */
    public function getPager()
    {
        return ['page' => $this->filters['page'],
            'total'    => ceil($this->getmessages_count() / $this->filters['limit']), ];
    }

    /**
     *  get user messages counts.
     */
    public function count()
    {
        return $this->messages->count();
    }
}
