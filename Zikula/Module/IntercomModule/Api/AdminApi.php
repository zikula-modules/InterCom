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
                'url' => ModUtil::url('InterCom', 'admin', 'main'),
                'text' => $this->__('Statistics'),
                'class' => 'z-icon-es-info'
            );
            $links[] = array(
                'url' => ModUtil::url('InterCom', 'admin', 'tools'),
                'text' => $this->__('Utilities'),
                'class' => 'z-icon-es-gears'
            );
            $links[] = array(
                'url' => ModUtil::url('InterCom', 'admin', 'modifyconfig'),
                'text' => $this->__('Settings'),
                'class' => 'z-icon-es-config'
            );
        }
        return $links;
    }
}