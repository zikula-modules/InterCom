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
use Zikula\IntercomModule\Entity\Message\AbstractMessageEntity;
use Zikula\PermissionsModule\Api\PermissionApi;
use Zikula\UsersModule\Api\CurrentUserApi;
use Zikula\UsersModule\Entity\UserEntity;

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

    private $user;

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
     * @param int $user user (optional: defaults to current user)
     */
    public function getMessenger($user = null)
    {
        if ($user == null && $this->userApi->isLoggedIn()) {
            $this->user = $this->entityManager->getRepository(UserEntity::class)->findOneBy(['uid' => $this->userApi->get('uid')]);
        } elseif ($user instanceof UserEntity) {
            $this->user = $user;
        } elseif (is_numeric($user)) {
            $this->user = $this->entityManager->getRepository(UserEntity::class)->findOneBy(['uid' => (int) $user]);
        } else {
            $user = null;
        }

        return $this;
    }

    /**
     *  load messages.
     */
    public function load($box, $filters)
    {
        if (!$this->user) {
            return $this;
        }

        $this->filters = $filters;
        switch ($box) {
            case 'inbox':
                $this->messages = $this->entityManager
                ->getRepository(AbstractMessageEntity::class)
                ->getRecivedMessagesByUser($this->user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page']);

                break;
            case 'sent':
                $this->messages = $this->entityManager
                ->getRepository(AbstractMessageEntity::class)
                ->getSentMessagesByUser($this->user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page']);

                break;
            case 'stored':
                $this->messages = $this->entityManager
                ->getRepository(AbstractMessageEntity::class)
                ->getStoredMessagesByUser($this->user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page']);

                break;
            case 'draft':
                $this->messages = $this->entityManager
                ->getRepository(AbstractMessageEntity::class)
                ->getDraftMessagesByUser($this->user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page']);
                break;
            case 'trash':
                $this->messages = $this->entityManager
                ->getRepository(AbstractMessageEntity::class)
                ->getDeletedMessagesByUser($this->user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page']);

                break;
            case 'labels':
                $this->messages = $this->entityManager
                ->getRepository(AbstractMessageEntity::class)
                ->getLabeledMessagesByUser($this->user, $filters['sortby'], $filters['sortorder'], $filters['limit'], $filters['page'], $filters['label']);

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
        if (!$this->user) {
            return $this;
        }

        foreach ($this->messages as $message) {
            $message->getMessageDataByUser($this->user);
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

    /**
     *  get messages counts for bridge.
     */
    public function getMessageCount($notSeenOnly = false)
    {
        return $this->entityManager->getRepository(AbstractMessageEntity::class)->getMessagesCountByUser($this->user, $notSeenOnly);
    }
}
