<?php
/**
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */
namespace Zikula\IntercomModule\Api;

use ModUtil;
use UserUtil;
use SecurityUtil;

class AccountApi extends \Zikula_AbstractApi
{
    /**
     * Return an array of items to show in the your account panel
     *
     * @return   array   array of items, or false on failure
     */
    public function getall($args)
    {
        // the array that will hold the options
        $items = array();

        // show link for users only
        if(!UserUtil::isLoggedIn()) {
            // not logged in
            return $items;
        }

        // Create an array of links to return
        if(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_OVERVIEW) && $this->getVar('active') == 1) {
            
            $items[] = array('url'     => $this->get('router')->generate('zikulaintercommodule_user_preferences'),
                            'title'   => $this->__('Private messaging settings'),
                            'text' => $this->__('Display messages settings'),                
                            'icon'    => 'userconfig.png');
            
            if($this->getVar('mode') == 1){                
                $items[] = array('url' => $this->get('router')->generate('zikulaintercommodule_user_conversations'),
                           'text' => $this->__('Display Conversations'),
                           'title'   => $this->__('Conversations list'),
                           'icon' => 'viewinbox.png');
            }else{
                $items[] = array('url' => $this->get('router')->generate('zikulaintercommodule_user_inbox'),
                           'text' => $this->__('Display messages'),
                           'title'   => $this->__('Private messaging mailbox'),
                           'icon' => 'viewinbox.png');                                      
            }         
        }

        // Return the items
        return $items;
    }
}