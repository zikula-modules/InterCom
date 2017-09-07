<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Container;

use Symfony\Component\Routing\RouterInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Core\LinkContainer\LinkContainerInterface;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\IntercomModule\Helper\LabelsHelper;
use Zikula\PermissionsModule\Api\PermissionApi;
use Zikula\UsersModule\Api\CurrentUserApi;

class LinkContainer implements LinkContainerInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PermissionApi
     */
    private $permissionApi;

    /**
     * @var VariableApi
     */
    private $variableApi;

    /**
     * @var CurrentUserApi
     */
    private $currentUser;

    /**
     * @var LabelsHelper
     */
    private $labelsHelper;

    /**
     * constructor.
     *
     * @param TranslatorInterface $translator
     * @param RouterInterface     $router
     * @param PermissionApi       $permissionApi
     * @param VariableApi         $variableApi
     * @param CurrentUserApi      $currentUserApi
     */
    public function __construct(
        TranslatorInterface $translator,
        RouterInterface     $router,
        PermissionApi       $permissionApi,
        VariableApi         $variableApi,
        CurrentUserApi      $currentUserApi,
        LabelsHelper        $labelsHelper
    ) {
        $this->name = 'ZikulaIntercomModule';
        $this->translator = $translator;
        $this->router = $router;
        $this->permissionApi = $permissionApi;
        $this->variableApi = $variableApi;
        $this->currentUser = $currentUserApi;
        $this->labelsHelper = $labelsHelper;
    }

    /**
     * get Links of any type for this extension
     * required by the interface.
     *
     * @param string $type
     *
     * @return array
     */
    public function getLinks($type = LinkContainerInterface::TYPE_ADMIN)
    {
        $method = 'get'.ucfirst(strtolower($type));
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return [];
    }

    private function getAccount()
    {
        $links = [];
        if (!$this->currentUser->isLoggedIn()) {
            return $links;
        }

        $links[] = [
            'url'   => $this->router->generate('zikulaintercommodule_messages_getmessages', ['box' => 'inbox']),
            'text'  => $this->translator->__('Messages'),
            'title' => $this->translator->__('Recived messages'),
            'icon'  => 'inbox',
        ];

        $links[] = [
            'url'   => $this->router->generate('zikulaintercommodule_user_preferences'),
            'text'  => $this->translator->__('Messages settings'),
            'title' => $this->translator->__('Private messaging settings'),
            'icon'  => 'wrench',
        ];

        return $links;
    }

    /**
     * get the Admin links for this extension.
     *
     * @return array
     */
    private function getAdmin()
    {
        $links = [];
        if ($this->permissionApi->hasPermission('ZikulaIntercomModule::', '::', ACCESS_ADMIN)) {
            $links[] = [
                'url'   => $this->router->generate('zikulaintercommodule_admin_status'),
                'text'  => $this->translator->__('Info'),
                'title' => $this->translator->__('Display informations'),
                'icon'  => 'dashboard', ];
            $links[] = [
                'url'   => $this->router->generate('zikulaintercommodule_admin_preferences'),
                'text'  => $this->translator->__('Settings'),
                'title' => $this->translator->__('Adjust module settings'),
                'icon'  => 'wrench', ];
            if ($this->variableApi->get($this->name, 'labels_enabled', false)) {
                $links[] = [
                    'url'   => $this->router->generate('zikulaintercommodule_labels_list'),
                    'text'  => $this->translator->__('Labels'),
                    'title' => $this->translator->__('Here you can import messages from older versions'),
                    'icon'  => 'tags', ];
            }
            if ($this->variableApi->get($this->name, 'support_enabled', false)) {
                $links[] = [
                    'url'   => $this->router->generate('zikulaintercommodule_support_list'),
                    'text'  => $this->translator->__('Support messages'),
                    'title' => $this->translator->__('Here you can import messages from older versions'),
                    'icon'  => 'life-buoy', ];
            }
            if ($this->variableApi->get($this->name, 'notifications_enabled', false)) {
                $links[] = [
                    'url'   => $this->router->generate('zikulaintercommodule_notifications_list'),
                    'text'  => $this->translator->__('Notifications'),
                    'title' => $this->translator->__('Here you can import messages from older versions'),
                    'icon'  => 'bullhorn', ];
            }
            $links[] = [
                'url'   => $this->router->generate('zikulaintercommodule_admin_import'),
                'text'  => $this->translator->__('Import'),
                'title' => $this->translator->__('Here you can import messages from older versions'),
                'icon'  => 'cloud-download', ];
        }

        return $links;
    }

    /**
     * get the User Links for this extension.
     *
     * @return array
     */
    private function getUser()
    {
        $links = [];
        if (!$this->currentUser->isLoggedIn()) {
            return $links;
        }

        $links[] = [
            'url'   => $this->router->generate('zikulaintercommodule_messages_getmessages', ['box' => 'inbox']),
            'text'  => $this->translator->__('Inbox'),
            'title' => $this->translator->__('Recived messages'),
            'icon'  => 'inbox',
        ];
        $links[] = [
            'url'   => $this->router->generate('zikulaintercommodule_messages_getmessages', ['box' => 'sent']),
            'text'  => $this->translator->__('Sent'),
            'title' => $this->translator->__('Messages sent by you'),
            'icon'  => 'envelope',
        ];
        $links[] = [
            'url'   => $this->router->generate('zikulaintercommodule_messages_getmessages', ['box' => 'draft']),
            'text'  => $this->translator->__('Draft'),
            'title' => $this->translator->__('Draft messages'),
            'icon'  => 'file-text',
        ];
        $links[] = [
            'url'   => $this->router->generate('zikulaintercommodule_messages_getmessages', ['box' => 'stored']),
            'text'  => $this->translator->__('Stored'),
            'title' => $this->translator->__('Saved messages'),
            'icon'  => 'floppy-o',
        ];
        $links[] = [
            'url'   => $this->router->generate('zikulaintercommodule_messages_getmessages', ['box' => 'trash']),
            'text'  => $this->translator->__('Trash'),
            'title' => $this->translator->__('Deleted messages'),
            'icon'  => 'trash',
        ];

        $defaultLabels = $this->labelsHelper->getDefaultLabels();
        $labelsLinks = [];
        foreach ($defaultLabels as $dLabel) {
            $labelsLinks[] = [
                        'url'   => $this->router->generate('zikulaintercommodule_messages_getmessages', ['box' => 'labels', 'label' => $dLabel->getUrlName()]),
                        'text'  => $this->translator->__($dLabel->getName()),
                        'icon'  => 'tag',
                    ];
        }
        $links[] = [
            'url'   => $this->router->generate('zikulaintercommodule_messages_getmessages', ['box' => 'labels']),
            'text'  => $this->translator->__('Labels'),
            'title' => $this->translator->__('Messages by label'),
            'icon'  => 'tags',
            'links' => $labelsLinks,
            ];
        $links[] = [
            'url'   => $this->router->generate('zikulaintercommodule_user_preferences'),
            'text'  => $this->translator->__('Settings'),
            'title' => $this->translator->__('Private messaging settings'),
            'icon'  => 'wrench',
        ];
        $links[] = [
            'url'   => $this->router->generate('zikulaintercommodule_messages_newmessage'),
            'text'  => $this->translator->__('New message'),
            'title' => $this->translator->__('Click here to compose new message'),
            'icon'  => 'file',
        ];

        return $links;
    }

    public function getBundleName()
    {
        return 'ZikulaIntercomModule';
    }
}
