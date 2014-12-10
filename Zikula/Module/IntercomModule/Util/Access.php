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

class Access {
   
    /***
     * Do all user checks in one method:
     * Check if logged in, has correct access, and if site is disabled
     * Returns the appropriate error/return value if failed, which can be
     *          returned by calling method.
     * Returns false if use has permissions.
     * On exit, $uid has the user's UID if logged in.
     */

    protected function checkuserAction(&$uid, $access = ACCESS_READ)
    {

        // If not logged in, redirect to login screen
        if (!UserUtil::isLoggedIn())
	{
	    $url = ModUtil::url('users', 'user', 'login',
		    array( 'returnpage' => urlencode(System::getCurrentUri()),
			)
	    );
	    return System::redirect($url);
	}

        // Perform access check
        if (!SecurityUtil::checkPermission('InterCom::', '::', $access))
        {
            return LogUtil::registerPermissionError();
        }

        // Maintenance message
        if ($this->getVar('messages_active') == 0 && !SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            $this->view->setCaching(false);
            return $this->view->fetch('user/maintenance.tpl');
        }

        // Get the uid of the user
        $uid = UserUtil::getVar('uid');

        // Return false to signify everything is OK.
        return false;
    }    
    
    
    
    
    
}