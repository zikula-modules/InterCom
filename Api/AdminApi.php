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

use SecurityUtil;

class AdminApi extends \Zikula_AbstractApi
{
    /**
     * get available admin panel links
     *
     * @return array array of admin links
     */
    public function getLinks()
    {
        $links = array();
        if (SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => $this->get('router')->generate('zikulaintercommodule_admin_index'),
                'text' => $this->__('Info'),
                'title' => $this->__('Display informations'),               
                'icon' => 'dashboard');
            $links[] = array(
                'url' => $this->get('router')->generate('zikulaintercommodule_admin_tools'),
                'text' => $this->__('Utilities'),
                'title' => $this->__('Here you can manage your messages database'),                
                'icon' => 'magic');
            $links[] = array(
                'url' => $this->get('router')->generate('zikulaintercommodule_admin_preferences'),
                'text' => $this->__('Settings'),
                'title' => $this->__('Adjust module settings'),                
                'icon' => 'wrench');
        }
        return $links;
    }
}