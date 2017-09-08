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
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\PermissionsModule\Api\PermissionApi;
use Zikula\UsersModule\Api\CurrentUserApi;
use Zikula\UsersModule\Entity\UserEntity;

/**
 * UserManager.
 *
 * @author Kaik
 */
class UserManager
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

    /**
     * Managed forum user.
     *
     * @var UserEntity
     */
    private $_managedUser;

    private $loggedIn = false;

    private $lastVisit;

    protected $name;

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
     *
     * @param int $user user id (optional: defaults to current user)
     */
    public function getManager($user = null)
    {
        //current user id
        $current = $this->userApi->isLoggedIn() ? $this->request->getSession()->get('uid') : 1;
        if (!empty($user)) {
            if ($user instanceof UserEntity) {
                $this->_managedUser = $user;
            } else {
                //if uid instance of zikua user
                $this->_managedUser = $this->entityManager->find('Zikula\UsersModule\Entity\UserEntity', $user);
            }
        } elseif (empty($user)) {
            $this->_managedUser = $this->entityManager->find('Zikula\UsersModule\Entity\UserEntity', $current);
        } else {
            $this->_managedUser = null;
        }
        // $this
//        return $this->checkLastVisit();

        return $this;
    }

    /**
     * Get managed user by name.
     *
     * @return bool
     */
    public function getManagedByUserName($uname)
    {
        $zuser = $this->entityManager->getRepository('Zikula\UsersModule\Entity\UserEntity')->findOneBy(['uname' => $uname]);
        if ($zuser) {
            return $this->getManager($zuser);
        }

        return $this;
    }

    /**
     * Check if user exists.
     *
     * @return bool
     */
    public function exists()
    {
        return $this->_managedUser instanceof UserEntity ? true : false;
    }

    /**
     * Return forum user as doctrine2 object.
     *
     * @return UserEntity
     */
    public function get()
    {
        return $this->_managedUser;
    }

    /**
     * Get user id.
     *
     * @return UserEntity
     */
    public function getId()
    {
        return $this->exists() ? $this->_managedUser->getUid() : false;
    }

    /**
     * Return forum user as array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->exists() ? $this->_managedUser->toArray() : false;
    }

    /**
     * Return zikula user as doctrine2 object.
     *
     * @return serEntity
     */
    public function getUser()
    {
        return $this->_managedUser->getUser();
    }

    /**
     * Return username.
     *
     * @return string
     */
    public function getUserName()
    {
        return ($this->exists() && !$this->isAnonymous()) ? $this->_managedUser->getUname() : 'Anonymous';
    }

    /**
     * Return forum user logged in status.
     *
     * @return ForumUserEntity
     */
    public function isLoggedIn()
    {
        return ($this->loggedIn && $this->getId() > 1) ? true : false;
    }

    /**
     * Return forum user online in status.
     *
     * @todo remove this duplicate
     *
     * @return ForumUserEntity
     */
    public function isOnline()
    {
        return ($this->loggedIn && $this->getId() > 1) ? true : false;
    }

    /**
     * @todo check to remove... or rename to isCurrent().
     *
     * @return string
     */
    public function isMe($user)
    {
        return $this->_managedUser->getUserId() == $user->getUserId() ? true : false;
    }

    /**
     * Return forum user logged in status.
     *
     * @return ForumUserEntity
     */
    public function isAnonymous()
    {
        return ($this->loggedIn && $this->getId() == 1) ? true : false;
    }

    /**
     * Return current user page.
     *
     * @deprecated to remove
     *
     * @return ForumUserEntity
     */
    public function getCurrentPosition()
    {
        return $this->request->attributes->get('_route');
    }

    /**
     * Is user allowed to comment check.
     *
     * @param object Object to check comment permissions for
     *
     * @return bool
     */
    public function allowedToComment($object)
    {
        if ($object instanceof self) {
            return true;
        }

        return false;
    }

    /**
     * Is user allowed to edit check.
     *
     * @param object Object to check edit permissions for
     *
     * @return bool
     */
    public function allowedToEdit($object)
    {
        if ($object instanceof self) {
            if ($object->getId() == $this->getId()) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Is user allowed to moderate check.
     *
     * @param object Object to check moderate permissions for
     *
     * @return bool
     */
    public function allowedToModerate($object)
    {
        if ($object instanceof self) {
            return $object->getId() == $this->getId();
        }

        return false;
    }

    /**
     * Get user intercom preferences.
     *
     * @param array
     */
    public function getPreferences()
    {
        $preferences = [];
        $preferences['ic_note'] = $this->_managedUser->getAttributes()->offsetExists('ic_note') ? $this->_managedUser->getAttributeValue('ic_note') : 0;
        $preferences['ic_ar'] = $this->_managedUser->getAttributes()->offsetExists('ic_ar') ? $this->_managedUser->getAttributeValue('ic_ar') : 0;
        $preferences['ic_art'] = $this->_managedUser->getAttributes()->offsetExists('ic_art') ? $this->_managedUser->getAttributeValue('ic_art') : '';

        return $preferences;
    }

    /**
     * Set user intercom preferences.
     *
     * @param this
     */
    public function setPreferences($preferences)
    {
        $this->_managedUser->setAttribute('ic_note', array_key_exists('ic_note', $preferences) ? $preferences['ic_note'] : false);
        $this->_managedUser->setAttribute('ic_ar', array_key_exists('ic_ar', $preferences) ? $preferences['ic_ar'] : false);
        $this->_managedUser->setAttribute('ic_art', array_key_exists('ic_art', $preferences) ? $preferences['ic_art'] : '');
        $this->entityManager->flush();

        return $this;
    }

    /**
     * Get user avatar.
     *
     * @todo - add deleted user and anonymous avatar image
     *
     * @return string
     */
    public function getAvatar()
    {
        $userAttr = $this->_managedUser->getUser()->getAttributes();
        if ($userAttr->offsetExists('avatar')) {
            //@todo add anonymous avatar setting
            return $this->_managedUser->getUser()->getAttributeValue('avatar');
        } else {
            return 'web/modules/zikuladizkus/images/anonymous.png';
        }
    }

    /**
     * Get user signature.
     *
     * @param string
     */
    public function getSignature()
    {
        return $this->_managedUser->getUser()->getAttributes()->offsetExists('signature') ? $this->_managedUser->getUser()->getAttributeValue('signature') : '';
    }

    /**
     * Set user signature.
     *
     * @param string $signature
     */
    public function setSignature($signature)
    {
        $zuser = $this->_managedUser->getUser();
        $zuser->setAttribute('signature', $signature);
        $this->entityManager->persist($zuser);
        $this->entityManager->flush();
    }

    /**
     * Check last visit.
     * reads the cookie, updates it and returns the last visit date in unix timestamp.
     *
     * @param none
     *
     * @return unix timestamp last visit date
     */
    public function checkLastVisit()
    {
        /**
         * set last visit cookies and get last visit time
         * set LastVisit cookie, which always gets the current time and lasts one year.
         */
        $time = time();
        $response = new Response();
        $cookie = new Cookie('IntercomLastVisit', $time, $time + 1800);
        $cookies = $this->request->cookies;
        if ($cookies->has('IntercomLastVisit')) {
            $this->lastVisit = $cookies->get('IntercomLastVisit');
            if ($this->lastVisit < $time - 1800) {
                //                $response->headers->setCookie($cookie);
//                $response->sendHeaders();
                dump('expired');
            }
        } else {
            $response->headers->setCookie($cookie);
            $response->sendHeaders();
            $this->lastVisit = $time;
        }

        return $this;
    }

    /**
     * Get last visit.
     *
     * @return unix timestamp last visit date
     */
    public function getLastVisit()
    {
        return $this->lastVisit;
    }

    /**
     * Get last visit.
     *
     * @return unix timestamp last visit date
     */
    public function getSendAsGroups()
    {
        return [];
    }
}
