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


class Common {
    
    /**
     * This function deletes all messages wich are not stored in any boxes
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function optimize_db()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();;
        }
        // Get DB
        $pntable = DBUtil::getTables();
        $messagescolumn = $pntable['intercom_column'];
        $where = "WHERE   $messagescolumn[msg_inbox]  = '0'
                AND   $messagescolumn[msg_outbox] = '0'
                AND   $messagescolumn[msg_stored] = '0'";
        DBUtil::deleteWhere('intercom', $where);
        return true;
    }    
  
}
