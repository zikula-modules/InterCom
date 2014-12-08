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
namespace Zikula\Module\IntercomModule\Api;

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
        $items = null;

        // show link for users only
        if(!UserUtil::isLoggedIn()) {
            // not logged in
            return $items;
        }

        // Create an array of links to return
        if(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_OVERVIEW)) {
            $items = array(array('url'     => ModUtil::url('InterCom', 'user', 'settings'),
                            'title'   => $this->__('Private messaging settings'),
                            'icon'    => 'userconfig.png'),
                    array('url'     => ModUtil::url('InterCom', 'user', 'main'),
                            'title'   => $this->__('Private messaging mailbox'),
                            'icon'    => 'viewinbox.png'));
        }

        // Return the items
        return $items;
    }
}