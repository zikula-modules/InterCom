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
        $welcomemessage        = ModUtil::getVar('messages_welcomemessage');
        $welcomemessagesubject = ModUtil::getVar('messages_welcomemessagesubject');
        // replace placeholders
        $uid = $user['uid'];
        $uname = $user['uname'];
        if ($uname == '') $uname = 'New User';
        $realname = $user['__ATTRIBUTES__']['realname'];
        $sitename = System::getVar('sitename');

        $welcomemessage = str_replace('%username%', $uname, $welcomemessage);
        $welcomemessage = str_replace('%realname%', $realname, $welcomemessage);
        $welcomemessage = str_replace('%sitename%', $sitename, $welcomemessage);
        $welcomemessagesubject = str_replace('%username%', $uname, $welcomemessagesubject);
        $welcomemessagesubject = str_replace('%realname%', $realname, $welcomemessagesubject);
        $welcomemessagesubject = str_replace('%sitename%', $sitename, $welcomemessagesubject);

        $obj =  array (
            'from_userid' => UserUtil::getIdFromName(ModUtil::getVar('InterCom', 'messages_welcomemessagesender')),
            'to_userid' => $uid,
            'msg_subject' => $welcomemessagesubject,
            'msg_time' => date("Y-m-d H:i:s"),
            'msg_text' => $welcomemessage,
            'msg_inbox' => '1',
            'msg_outbox' => '0',
            'msg_stored' => '0');
        ModUtil::apiFunc('InterCom', 'user', 'store_message', $obj);
        
    }

}
