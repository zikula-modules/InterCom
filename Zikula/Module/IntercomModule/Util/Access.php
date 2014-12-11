<?php
/**
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @subpackage Util
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */
namespace Zikula\Module\IntercomModule\Util;

use DataUtil;
use ServiceUtil;
use UserUtil;
use SecurityUtil;

class Access {
   
    /***
     * Do all user checks in one method:
     * Check if logged in, has correct access, and if site is disabled
     * Returns the appropriate error/return value if failed, which can be
     *          returned by calling method.
     * Returns false if use has permissions.
     * On exit, $uid has the user's UID if logged in.
    */  

    protected function checkAccess($access = ACCESS_READ)
    {

        // If not logged in, redirect to login screen
        if (!UserUtil::isLoggedIn())
	{
            return false;
	}
        // Perform access check
        if (!SecurityUtil::checkPermission('ZikulaIntercomModule::', '::', $access))
        {
            return false;
        }
        // Maintenance message
        if ($this->getVar('active') == 0 && !SecurityUtil::checkPermission('ZikulaIntercomModule::', '::', ACCESS_ADMIN)) {
            return false;
        }

        // Get the uid of the user
        $uid = UserUtil::getVar('uid');

        // Return false to signify everything is OK.
        return $uid;
    }    
   
    
    
    
    
}