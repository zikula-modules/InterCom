<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
//use Zikula\Core\Event\GenericEvent;
//use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\HttpFoundation\RequestStack;
//use Doctrine\ORM\EntityManager;

abstract class CreateUserListener implements EventSubscriberInterface
{
    /*
     * On an module remove hook call this listener
     *
     * Listens for the 'user.account.create' event.
     *
     * @param Zikula_Event $event Event.

    public static function onCreateUser(Zikula_Event $event)
    {
        // If sending messages isn't enabled, just return.
        if (!ModUtil::getVar('InterCom', 'messages_welcomemessage_send'))
        {
            return;
        }

        $user = $event->getSubject();
        $welcomemessage        = ModUtil::getVar('InterCom', 'messages_welcomemessage');
        $welcomemessagesubject = ModUtil::getVar('InterCom', 'messages_welcomemessagesubject');

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

        DBUtil::insertObject($obj, 'intercom', 'msg_id');
        return;
    }
public static function getSubscribedEvents(){
}*/
}
