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

class InterCom_Listener_CreateUserListener
{
    /**
     * On an module remove hook call this listener
     *
     * Listens for the 'user.account.create' event.
     *
     * @param Zikula_Event $event Event.
     */
    public static function onCreateUser(Zikula_Event $event)
    {
        
        $user = $event->getSubject();
        //$uid = $user['uid'];
        
        $welcomemessage        = ModUtil::getVar('messages_welcomemessage');
        $welcomemessagesubject = ModUtil::getVar('messages_welcomemessagesubject');
        // replace placeholders
        $user = ModUtil::apiFunc('InterCom', 'user', 'getposterdata', array('uid' => $args['objectid']));
        $sitename = System::getVar('sitename');
        $welcomemessage = str_replace('%username%', $user['uname'], $welcomemessage);
        $welcomemessage = str_replace('%realname%', $user['_UREALNAME'], $welcomemessage);
        $welcomemessage = str_replace('%sitename%', $sitename, $welcomemessage);
        $welcomemessagesubject = str_replace('%username%', $user['uname'], $welcomemessagesubject);
        $welcomemessagesubject = str_replace('%realname%', $user['_UREALNAME'], $welcomemessagesubject);
        $welcomemessagesubject = str_replace('%sitename%', $sitename, $welcomemessagesubject);

        $obj =  array (
            'from_userid' => pnUserGetIDFromName(pnModGetVar('InterCom', 'messages_welcomemessagesender')),
            'to_userid' => $user['uid'],
            'msg_subject' => $welcomemessagesubject,
            'msg_time' => date("Y-m-d H:i:s"),
            'msg_text' => $welcomemessage,
            'msg_inbox' => '1',
            'msg_outbox' => '0',
            'msg_stored' => '0');
        ModUtil::apiFunc('InterCom', 'user', 'store_message', $obj);
        
    }

}
