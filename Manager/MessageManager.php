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
use Zikula\IntercomModule\Entity\Message\NormalEntity;
use Zikula\IntercomModule\Entity\MessageDetails\MessageUserDetailsEntity;
use Zikula\PermissionsModule\Api\PermissionApi;
use Zikula\UsersModule\Api\CurrentUserApi;

/**
 * MessageManager.
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

    private $_message;

    private $preview = false;

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
     * @param RankHelper          $ranksHelper
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
        $this->name = 'ZikulaIntercomModule';
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
     */
    public function getManager($message = null)
    {
        if ($message === null) {
            return $this;
        } elseif ($message instanceof AbstractMessageEntity) {
            return $this->setMessage($message);
        } elseif (is_int((int) $message)) {
            return $this->load(['id' => $message]);
        }

        return $this;
    }

    /**
     * create new blank message.
     */
    public function create()
    {
        $this->_message = new NormalEntity();

        return $this;
    }

    /**
     * start managing from entity.
     *
     * @return this
     */
    public function setMessage($message)
    {
        $this->_message = $message;

        return $this;
    }

    /**
     * load message from database.
     */
    public function load($p)
    {
        $this->_message = $this->entityManager
        ->getRepository(AbstractMessageEntity::class)
        ->findOneBy($p);

        return $this;
    }

    /**
     * return message id.
     *
     * @return array
     */
    public function getId()
    {
        return ($this->exists()) ? $this->_message->getId() : false;
    }

    /**
     * return message as doctrine2 object.
     *
     * @return object
     */
    public function get()
    {
        return $this->_message;
    }

    /**
     * return message as array.
     *
     * @return mixed array or false
     */
    public function toArray()
    {
        if (!$this->exists()) {
            return false;
        }

        return $this->_message->toArray();
    }

    /**
     * return exist status.
     *
     * @return bool
     */
    public function exists()
    {
        return (!$this->_message) ? false : true;
    }

    /**
     * get new message.
     *
     * @return object NormalEntity
     */
    public function getNewMessage()
    {
        $this->create();

        return $this->_message;
    }

    /**
     * prepare preview.
     *
     * @return this
     */
    public function prepareForPreview()
    {
        $this->setPreview(true);

        return $this;
    }

    /**
     * set preview mode.
     *
     * @return this
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;

        return $this;
    }

    /**
     * is preview mode.
     *
     * @return bool
     */
    public function isPreview()
    {
        return $this->preview;
    }

    /**
     * perform save as draft.
     *
     * @return this
     */
    public function saveAsDraft()
    {
        return $this->store();
    }

    /**
     * perform send.
     *
     * @return this
     */
    public function send()
    {
        $this->_message->setSent(new \DateTime('now'));

        return $this->store();
    }

    /**
     * return reply message array.
     *
     * @return array
     */
    public function getReplyPrepared()
    {
        $reply = new NormalEntity();
        $reply->setParent($this->_message);
        $reply->setSubject($this->translator->__('Re:').' '.$this->_message->getReplySubject());
        $reply->setText($this->translator->__('Text:').' '.$this->_message->getReplyText());

        return $reply;
    }

    /**
     * perform reply.
     *
     * @return bool
     */
    public function reply()
    {
//        $this->_message->setReplied(new \DateTime('now'));
//        $this->entityManager->persist($this->_message);
//        $this->entityManager->flush();
//        $this->create();
//
//        return $this->save();
    }

    /**
     * return forward message array.
     *
     * @return array
     */
    public function prepareForForward()
    {
//        $reply = [];
//        $reply['id'] = $this->_message->getId();
//        $reply['sender'] = $this->_message->getRecipient()->toArray();
//        $reply['recipient'] = '';
//        $reply['subject'] = __('Fwd:') . ' ' . $this->_message->getSubject();
//        $reply['text'] = __('Text') . ' ' . $this->_message->getText();
//
//        return $reply;
    }

    /**
     * perform forward.
     *
     * @return bool
     */
    public function forward()
    {
    }

    /**
     * perform store.
     *
     * @return bool
     *
     * @todo Implement new storage system
     */
    public function store()
    {
        $this->entityManager->persist($this->_message);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * remove message.
     *
     * @return bool
     */
    public function remove()
    {
//        $this->entityManager->remove($this->_message);
//        $this->entityManager->flush();

        return true;
    }

    /**
     * get message details.
     *
     * @return object NormalEntity
     */
    public function getMessageUserDetails()
    {
        if (!$this->userApi->isLoggedIn()) {
            return $this;
        }

        $messageUserDetails = $this->entityManager->getRepository(MessageUserDetailsEntity::class)
                ->findOneBy(['message' => $this->_message, 'user' => $this->userApi->get('uid')]);

        if (!$messageUserDetails) {
            $messageUserDetails = new MessageUserDetailsEntity();
            $messageUserDetails->setMessage($this->_message);
            $user = $this->entityManager->getRepository('Zikula\UsersModule\Entity\UserEntity')->findOneBy(['uid' => $this->userApi->get('uid')]);
            $messageUserDetails->setUser($user);
            // it should be seen by now or not?
//            $messageUserDetails->setSeen(new \DateTime('now'));
        }

        return $messageUserDetails;
    }

    /**
     * set label.
     *
     * @return object this
     */
    public function setlabel($label = null)
    {
        $messageUserDetails = $this->getMessageUserDetails();
        $messageUserDetails->setLabel($label);
        $this->entityManager->persist($messageUserDetails);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * set seen status.
     *
     * @return object this
     */
    public function setSeen()
    {
        $messageUserDetails = $this->getMessageUserDetails();
        if ($messageUserDetails->getSeen()) {
            return $this;
        }

        $messageUserDetails->setSeen(new \DateTime('now'));
        $this->entityManager->persist($messageUserDetails);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * set replied status.
     *
     * @return object this
     */
    public function setReplied()
    {
        $messageUserDetails = $this->getMessageUserDetails();
        if ($messageUserDetails->getReplied()) {
            return $this;
        }

        $messageUserDetails->setReplied(new \DateTime('now'));
        $this->entityManager->persist($messageUserDetails);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * set notified status.
     *
     * @return object this
     */
    public function setNotified()
    {
        $messageUserDetails = $this->getMessageUserDetails();
        if ($messageUserDetails->getNotified()) {
            return $this;
        }

        $messageUserDetails->setNotified(new \DateTime('now'));
        $this->entityManager->persist($messageUserDetails);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * set seen status.
     *
     * @return bool
     */
    public function setStored()
    {
        $messageUserDetails = $this->getMessageUserDetails();
        if ($messageUserDetails->getStored()) {
            return $this;
        }

        $messageUserDetails->setStored(new \DateTime('now'));
        $this->entityManager->persist($messageUserDetails);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * perform delete.
     *
     * @return bool
     */
    public function delete()
    {
        $messageUserDetails = $this->getMessageUserDetails();
        if ($messageUserDetails->getDeleted()) {
            return $this;
        }

        $messageUserDetails->setDeleted(new \DateTime('now'));
        $this->entityManager->persist($messageUserDetails);
        $this->entityManager->flush();

        return $this;
    }
}
