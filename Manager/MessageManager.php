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
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\UsersModule\Api\CurrentUserApi;
use Zikula\PermissionsModule\Api\PermissionApi;

/**
 * MessageManager
 *
 * @author Kaik
 */
class MessageManager
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

    /**
     * Construct the manager
     *
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @param RequestStack $requestStack
     * @param EntityManager $entityManager
     * @param CurrentUserApi $userApi
     * @param PermissionApi $permission
     * @param VariableApi $variableApi
     * @param RankHelper $ranksHelper
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
     * Get manager
     *
     * @param int  $uid  user id (optional: defaults to current user)
     */
    public function getManager()
    {
//        //current user id
//        $current = $this->userApi->isLoggedIn() ? $this->request->getSession()->get('uid') : 1;
//        if (!empty($user)) {
//            if ($user instanceof UserEntity) {
//                $this->_managedUser = $user;
//            } else {
//            //if uid instance of zikua user
//                $this->_managedUser = $this->entityManager->find('Zikula\UsersModule\Entity\UserEntity', $uid);
//            }
//
//        } elseif (empty($user)) {
//            $this->_managedUser= $this->entityManager->find('Zikula\UsersModule\Entity\UserEntity', $current);
//        } else {
//            $this->_managedUser = null;
//        }
//        // $this
////        return $this->checkLastVisit();

        return $this;
    }
}

//lass Messages {
//
//    private $name;
//    public $entityManager;
//    private $messages;
//    private $filters;
//
//    /**
//     * construct
//     */
//    public function __construct($entityManager) {
//        $this->name = 'ZikulaIntercomModule';
//        $this->entityManager = $entityManager;
//    }
//
//    /**
//     *  load messages
//     */
//    public function load($box, $filters) {
//
//        switch ($box) {
//            case 'inbox':
//                $filters['recipient'] = \UserUtil::getVar('uid');
//                $filters['deleted'] = 'byrecipient';
//                break;
//            case 'outbox':
//                $filters['sender'] = \UserUtil::getVar('uid');
//                $filters['deleted'] = 'bysender';
//                break;
//            case 'archive':
//                $filters['recipient'] = \UserUtil::getVar('uid');
//                $filters['deleted'] = 'byrecipient';
//                $filters['stored'] = 'byrecipient';
//                break;
//            case 'admin':
//
//                break;
//            default:
//                break;
//        }
//
//
//        $this->messages = $this->entityManager
//                ->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
//                ->getAll($filters);
//
//        $this->filters = $filters;
//
//        return $this;
//    }
//
//    /**
//     *  get messages
//     */
//    public function getmessages() {
//        return $this->messages;
//    }
//
//    /**
//     *  get messages
//     */
//    public function getmessages_array() {
//        $messages_array = array();
//        foreach ($this->messages as $key => $message) {
//            $messages_array[$key] = $message;
//        }
//        return $messages_array;
//    }
//
//    /**
//     *  get messages count
//     */
//    public function getmessages_count() {
//        return $this->messages->count();
//    }
//
//    /**
//     *  get messages count
//     */
//    public function getPager() {
//        return ['page' => $this->filters['page'],
//            'total' => ceil($this->getmessages_count() / $this->filters['limit'])];
//    }
//
//    /**
//     *  get user messages counts
//     */
//    public function count() {
//        return $this->messages->count();
//    }
//
//}

//class Message {
//
//    private $name;
//    private $_message;
//    private $_new;
//    public $entityManager;
//
//    /**
//     * construct
//     */
//    public function __construct($entityManager) {
//        $this->name = 'ZikulaIntercomModule';
//        $this->entityManager = $entityManager;
//    }
//
//    /**
//     * create new blank message
//     *
//     */
//    public function create() {
//        $this->_message = new MessageEntity();
//    }
//
//    /**
//     * load message from database
//     *
//     */
//    public function load($p) {
//        $this->_message = $this->entityManager
//                ->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
//                ->getOneBy($p);
//    }
//
//    /**
//     * set new message array
//     *
//     * @return boolean
//     */
//    public function setNewData($p) {
//        $this->_new = $p;
//        return true;
//    }
//
//    /**
//     * return new message array
//     *
//     * @return boolean
//     */
//    public function getNewData() {
//        return $this->_new;
//    }
//
//    /**
//     * return message id
//     *
//     * @return array
//     */
//    public function getId() {
//        return $this->_message->getId();
//    }
//
//    /**
//     * return message as doctrine2 object
//     *
//     * @return object
//     */
//    public function get() {
//        return $this->_message;
//    }
//
//    /**
//     * return message as array
//     *
//     * @return mixed array or false
//     */
//    public function toArray() {
//        if (!$this->exist()) {
//            return false;
//        }
//        return $this->_message->toArray();
//    }
//
//    /**
//     * return exist status
//     *
//     * @return boolean
//     */
//    public function exist() {
//        return (!$this->_message) ? false : true;
//    }
//
//    /**
//     * return validation status
//     *
//     * @return boolean
//     */
//    public function isValid() {
//        return true;//return $this->validator->isValid();
//    }
//
//    /**
//     * return errors array
//     *
//     * @return array
//     */
//    public function getErrors() {
//        //return $this->validator->getErrors();
//    }
//
//    /**
//     * set seen status
//     *
//     * @return boolean
//     */
//    public function setSeen() {
//        $this->_message->setSeen(new \DateTime('now'));
//        $this->entityManager->persist($this->_message);
//        $this->entityManager->flush();
//        return true;
//    }
//
//    /**
//     * return reply message array
//     *
//     * @return array
//     */
//    public function prepareForReply() {
//        $reply = array();
//        $reply['id'] = $this->_message->getId();
//        $reply['sender'] = $this->_message->getRecipient()->toArray();
//        $reply['recipient'] = $this->_message->getSender()->toArray();
//        $reply['subject'] = __('Re:') . ' ' . $this->_message->getSubject();
//        $reply['text'] = __('Text') . ' ' . $this->_message->getText();
//        return $reply;
//    }
//
//    /**
//     * return forward message array
//     *
//     * @return array
//     */
//    public function prepareForForward() {
//        $reply = array();
//        $reply['id'] = $this->_message->getId();
//        $reply['sender'] = $this->_message->getRecipient()->toArray();
//        $reply['recipient'] = '';
//        $reply['subject'] = __('Fwd:') . ' ' . $this->_message->getSubject();
//        $reply['text'] = __('Text') . ' ' . $this->_message->getText();
//        return $reply;
//    }
//
//    /**
//     * perform send
//     *
//     * @return boolean
//     */
//    public function send() {
//        $this->create();
//        return $this->save();
//    }
//
//    /**
//     * perform reply
//     *
//     * @return boolean
//     */
//    public function reply() {
//        $this->_message->setReplied(new \DateTime('now'));
//        $this->entityManager->persist($this->_message);
//        $this->entityManager->flush();
//        $this->create();
//        return $this->save();
//    }
//
//    /**
//     * perform store
//     *
//     * @return boolean
//     *
//     * @todo Implement new storage system
//     */
//    public function store() {
//        $recipient = $this->_message->getRecipient()->getUid() == UserUtil::getVar('uid') ? $this->_message->setStoredbyrecipient(1) : 1;
//        $sender = $this->_message->getSender()->getUid() == UserUtil::getVar('uid') ? $this->_message->setStoredbysender(1) : 1;
//        $this->entityManager->persist($this->_message);
//        $this->entityManager->flush();
//        return ($recipient || $sender);
//    }
//
//    /**
//     * perform delete
//     *
//     * @return boolean
//     */
//    public function delete() {
//        $recipient = $this->_message->getRecipient()->getUid() == UserUtil::getVar('uid') ? $this->_message->setDeletedbyrecipient(1) : 1;
//        $sender = $this->_message->getSender()->getUid() == UserUtil::getVar('uid') ? $this->_message->setDeletedbysender(1) : 1;
//        $this->entityManager->persist($this->_message);
//        $this->entityManager->flush();
//        return ($recipient || $sender);
//    }
//
//    /**
//     * perform forward
//     *
//     * @return boolean
//     */
//    public function forward() {
//        $this->create();
//        return $this->save();
//    }
//
//    /**
//     * prepare for save
//     *
//     * @return boolean
//     */
//    public function prepareForSave() {
//        unset($this->_new['id']);
//        unset($this->_new['recipients']);
//        unset($this->_new['multiple']);
//    }
//
//    /**
//     * perform save
//     *
//     * @return boolean
//     */
//    public function save() {
//        if (!$this->getId() && $this->_new && $this->isValid()) {
//            $this->prepareForSave();
//            $this->_message->merge($this->_new);
//            $this->entityManager->persist($this->_message);
//            $this->entityManager->flush();
//            return true;
//        }
//        if ($this->getId() && $this->_new && $this->isValid()) {
//            $this->prepareForSave();
//            $this->_message->merge($this->_new);
//            $this->entityManager->flush();
//            return true;
//        }
//    }
//
//    /**
//     * remove message compleatly
//     *
//     * @return boolean
//     */
//    public function remove() {
//        $this->entityManager->remove($this->_message);
//        $this->entityManager->flush();
//        return true;
//    }
//
//    /**
//     * remove message compleatly
//     *
//     * @return boolean
//     */
//    public function isMultiple() {
//        if (is_array($this->_new['multiple'])) {
//            return true;
//        }
//        return false;
//    }
//
//    /**
//     * remove message compleatly
//     *
//     * @return boolean
//     */
//    public function sendMultiple() {
//        $multiple = $this->_new['multiple'];
//        unset($this->_new['multiple']);
//        unset($this->_new['recipients']);
//        foreach ($multiple as $key => $recipient) {
//            $this->_new['recipient'] = $recipient;
//            $this->send();
//        }
//        return true;
//    }
//
//    /**
//     * Edit field.
//     */
//    public function editField($args) {
//        if (!isset($args['id']) || !is_numeric($args['id'])) {
//            throw new \InvalidArgumentException(__('Invalid arguments array received'));
//        }
//        if (!isset($args['field']) || !is_string($args['field'])) {
//            throw new \InvalidArgumentException(__('Invalid arguments array received'));
//        }
//        $id = $args['id'];
//        $field = $args['field'];
//        $value = $args['value'];
//
//        $item = $this->entityManager->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
//                ->getOneBy(array('id' => $id));
//
//        if (!$item) {
//            return false;
//        }
//        switch ($field) {
//            case 'deletedbysender':
//                $item->setInbox($value);
//                break;
//            case 'deletedbyrecipient':
//                $item->setOutbox($value);
//                break;
//            case 'notified':
//                $item->setNotified(new \DateTime());
//                break;
//            case 'seen':
//                $item->setSeen(new \DateTime());
//                break;
//            case 'replied':
//                $item->setReplied(new \DateTime());
//                break;
//        }
//        $this->entityManager->flush();
//        return true;
//    }
//
//}
