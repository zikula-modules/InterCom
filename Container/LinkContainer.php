<?php
/**
 * Copyright InterCom Team 2015
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package InterCom
 * @link https://github.com/zikula-modules/Pages
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\IntercomModule\Container;

use UserUtil;
use SecurityUtil;
use Symfony\Component\Routing\RouterInterface;
use Zikula\Common\Translator\Translator;
use Zikula\Core\LinkContainer\LinkContainerInterface;

class LinkContainer implements LinkContainerInterface
{
    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router, $translator)
    {
        $this->translator = $translator;
        $this->router = $router;
    }
    
    /**
     * get Links of any type for this extension
     * required by the interface
     *
     * @param string $type
     * @return array
     */
    public function getLinks($type = LinkContainerInterface::TYPE_ADMIN)
    {
    	$method = 'get' . ucfirst(strtolower($type));
    	if (method_exists($this, $method)) {
    		return $this->$method();
    	}
    
    	return array();
    }
    
    /**
     * get the Admin links for this extension
     *
     * @return array
     */
    private function getAdmin()
    {
    	$links = array();
            if (SecurityUtil::checkPermission('ZikulaInterCom::', '::', ACCESS_ADMIN)) {
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_admin_index'),
        				'text' => $this->translator->__('Info'),
        				'title' => $this->translator->__('Display informations'),
        				'icon' => 'dashboard');
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_admin_tools'),
        				'text' => $this->translator->__('Utilities'),
        				'title' => $this->translator->__('Here you can manage your messages database'),
        				'icon' => 'magic');
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_admin_preferences'),
        				'text' => $this->translator->__('Settings'),
        				'title' => $this->translator->__('Adjust module settings'),
        				'icon' => 'wrench');
        	}        	  
    	return $links;
    }
    
    /**
     * get the User Links for this extension
     *
     * @return array
     */
    private function getUser()
    {
    	$links = array();
            if (UserUtil::isLoggedIn()) {        	 
        	if($this->getVar('mode') == 0){
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_inbox_view'),
        				'text' => $this->translator->__('Inbox'),
        				'title'   => $this->translator->__('Recived messages'),
        				'icon' => 'inbox'
        		);
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_send_list'),
        				'text' => $this->translator->__('Outbox'),
        				'title'   => $this->translator->__('Messages send by you'),
        				'icon' => 'upload'
        		);
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_archive_list'),
        				'text' => $this->translator->__('Archive'),
        				'title'   => $this->translator->__('Your saved messages'),
        				'icon' => 'archive'
        		);
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_user_preferences'),
        				'text' => $this->translator->__('Display messages settings'),
        				'title'   => $this->translator->__('Private messaging settings'),
        				'icon' => 'wrench'
        		);
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_message_new'),
        				'text' => $this->translator->__('New message'),
        				'title'   => $this->translator->__('Click here to compose new message'),
        				'icon' => 'file'
        		);
        	}        
        	if($this->getVar('mode') == 1){
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_conversations_list'),
        				'text' => $this->translator->__('Conversations'),
        				'title'   => $this->translator->__('See all of your conversations'),
        				'icon' => 'coffee'
        		);
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_archive_list'),
        				'text' => $this->translator->__('Archive'),
        				'title'   => $this->translator->__('Your saved conversations'),
        				'icon' => 'archive'
        		);
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_user_preferences'),
        				'text' => $this->translator->__('Display messages settings'),
        				'title'   => $this->translator->__('Private messaging settings'),
        				'icon' => 'wrench'
        		);
        		$links[] = array(
        				'url' => $this->router->generate('zikulaintercommodule_conversations_list'),
        				'text' => $this->translator->__('Start new conversation'),
        				'title'   => $this->translator->__('New conversation'),
        				'icon' => 'file'
        		);
        	}        	
        }        
    	
    	return $links;
    }    

    public function getBundleName()
    {
        return 'ZikulaIntercomModule';
    }
}