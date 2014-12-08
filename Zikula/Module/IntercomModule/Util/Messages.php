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
 * message regarding copyright.
 */

namespace Zikula\Module\IntercomModule\Util;

use ModUtil;
use ServiceUtil;
use DataUtil;
use Zikula\Module\IntercomModule\Util\Validator;


class Messages {

  private $name;
  public  $entityManager;

    /**
     * construct
     */
    public function __construct()
    {
        $this->name = 'ZikulaIntercomModule';
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
        
    }

    /**
     * This function returns the amount of Messages within the inbox, outbox, and the archives
     *
     * @author Chasm
     * @param  $
     * @return
     */
    public function getmessagecount()
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();;
        }

        // Get DB
        $pntable = DBUtil::getTables();
        $messagestable  = $pntable['intercom'];
        $messagescolumn = $pntable['intercom_column'];

        $uid = UserUtil::getVar('uid');
        // Get items - prepare queries
        $sql_1 = "SELECT sum( $messagescolumn[msg_stored] ) msg_stored,
                     sum( $messagescolumn[msg_inbox] ) msg_inbox,
                     sum(CASE WHEN $messagescolumn[msg_inbox] = 1 THEN $messagescolumn[msg_read] ELSE 0 END) read_msg,
                     sum(CASE WHEN $messagescolumn[msg_inbox] = 1 THEN $messagescolumn[msg_popup] ELSE 0 END) msg_popup
              FROM $messagestable
              WHERE $messagescolumn[to_userid] = $uid";

        $sql_2 = "SELECT count($messagescolumn[msg_id])
              FROM   $messagestable
              WHERE  $messagescolumn[msg_outbox]  = '1'
              AND    $messagescolumn[from_userid] = $uid";

        $res1 = DBUtil::marshallObjects(DBUtil::executeSQL($sql_1),
                array('totalarchive', 'totalin', 'read', 'msg_popup') );
        if (is_array($res1[0])) extract($res1[0]);

        $res2 = DBUtil::marshallObjects(DBUtil::executeSQL($sql_2),
                array('totalout'));
        $totalout = $res2[0]['totalout'];
        $unread = $totalin - $read;
        $popup = $totalin - $msg_popup;

        // prepare return variables
        $limitin = ModUtil::getVar('InterCom', 'messages_limitinbox');
        $limitout = ModUtil::getVar('InterCom', 'messages_limitoutbox');
        $limitarchive = ModUtil::getVar('InterCom', 'messages_limitarchive');

	// This and totalarchive are set by the extract above.
	if (empty($totalin)) {
            $totalin = 0;
        }
        if (empty($totalout)) {
            $totalout = 0;
        }
        if (empty($totalarchive)) {
            $totalarchive = 0;
        }

        if ($totalin >= $limitin) {
            $inboxlimitreached = true;
            $inboxlimitclass = 'ic-limitreached';
        } else {
            $inboxlimitreached = false;
            $inboxlimitclass = 'ic-limitnotreached';
        }
        if ($totalout >= $limitout) {
            $outboxlimitreached = true;
            $outboxlimitclass = 'ic-limitreached';
        } else {
            $outboxlimitreached = false;
            $outboxlimitclass = 'ic-limitnotreached';
        }
        if ($totalarchive >= $limitarchive) {
            $archivelimitreached = true;
            $archivelimitclass = 'ic-limitreached';
        } else {
            $archivelimitreached = false;
            $archivelimitclass = 'ic-limitnotreached';
        }

        $indicatorbarin = round(($totalin / $limitin) * 100);
        $indicatorbarout = round(($totalout / $limitout) * 100);
        $indicatorbararchive = round(($totalarchive / $limitarchive) * 100);
        // form a variable to return
        $ReturnArray = array();

        $ReturnArray['unread'] = $unread;
        $ReturnArray['popup'] = $popup;
        $ReturnArray['totalin'] = $totalin;
        $ReturnArray['totalout'] = $totalout;
        $ReturnArray['totalarchive'] = $totalarchive;

        $ReturnArray['limitin'] = $limitin;
        $ReturnArray['limitout'] = $limitout;
        $ReturnArray['limitarchive'] = $limitarchive;

        $ReturnArray['inboxlimitreached'] = $inboxlimitreached;
        $ReturnArray['outboxlimitreached'] = $outboxlimitreached;
        $ReturnArray['archivelimitreached'] = $archivelimitreached;

        $ReturnArray['inboxlimitclass'] = $inboxlimitclass;
        $ReturnArray['outboxlimitclass'] = $outboxlimitclass;
        $ReturnArray['archivelimitclass'] = $archivelimitclass;

        $ReturnArray['indicatorbarin'] = $indicatorbarin;
        $ReturnArray['indicatorbarout'] = $indicatorbarout;
        $ReturnArray['indicatorbararchive'] = $indicatorbararchive;

        // Return the variable
        return $ReturnArray;
    } 
}
