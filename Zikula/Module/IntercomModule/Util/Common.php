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
     * This function prepares a message for display
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function prepmessage_for_display($args)
    {
        $msg_text = nl2br($args['msg_text']);
        $msg_text = DataUtil::formatforDisplayHTML(stripslashes($msg_text));

     // TODO  list($msg_text) = ModUtil::callHooks('item', 'transform', '', array($msg_text));
        return $msg_text;
    }

    /**
     * This function prepares a message for the textarea form
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function prepmessage_for_form($args)
    {
        $msg_text = stripslashes(str_replace(array('<br />', '<br>', '<BR>'), "\n", $args['msg_text']));
        return $msg_text;
    }

    /**
     * This function prepares a url for display
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function prepurl_for_display($args)
    {
        $url = $args['url'];
        if ($url != '') {
            if ($url == 'http://' || $url == 'http://http://' || $url == 'http:///') {
                $url = '';
            } elseif (strstr("http://", $url)) {
                $url = "http://" . $url;
            }
        }
        return $url;
    }

    /**
     * getposterdata
     * reads the posters data and fakes them if the poster has been deleted in the meantime
     *
     *@params $uid   int the user id
     *
     */
    public function getposterdata($args)
    {
        if (!isset($args['uid']) || empty($args['uid']) || !is_numeric($args['uid'])) {
            return LogUtil::registerArgsError();
        }

        $posterdata = UserUtil::getVars($args['uid']);
        if ($posterdata == false || empty($posterdata)) {
            $posterdata = UserUtil::getVars(1);
            $posterdata['uname']    = $this->__('*Deleted user*');
        }
        return $posterdata;
    }
    
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
