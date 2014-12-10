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

class UserApi extends \Zikula_AbstractApi
{
    /**
     * Send a email notification to the user over System::mail-API
     * Code is taken from pnforum-module
     *
     * @author chaos
     * @version
     * @return
     */
    public function emailnotification($args)
    {
        // Extract expected variables
        $to_uid   = $args['to_uid'];
        $from_uid = $args['from_uid'];
        $subject  = $args['subject'];

        // First check if the Mailer module is avaible
        if(!ModUtil::available('Mailer')) {
            return true;
        }

        // Then check if admin allowed email notifications
        $allow_emailnotification = ModUtil::getVar('InterCom', 'messages_allow_emailnotification');
        if ($allow_emailnotification != 1) {
            return true;
        }

        // check the user attributes for userprefs
        $user = DBUtil::selectObjectByID('users', $to_uid, 'uid', null, null, null, false);
        if (!is_array($user)){
            // WTF, no user data?
            return true;
        }

        if (!isset($user['__ATTRIBUTES__']) || (!isset($user['__ATTRIBUTES__']['ic_note'])
                        && !isset($user['__ATTRIBUTES__']['ic_ar'])
                        && !isset($user['__ATTRIBUTES__']['ic_art']))) {
            // ic_note: email notifiaction yes/no
            // ic_ar  : autoreply yes/no
            // ic_art : autoreply text
            // load values from userprefs tables and store them in attributes
            // get all tables from the database, tbls is a non-assoc array
            $tbls = DBUtil::metaTables();
            // if old intercom_userprefs table exists, try to read the values for user $to_uid
            $olduserprefs = in_array('intercom_userprefs', $tbls);
            if ($olduserprefs == true) {
                $userprefs = DBUtil::selectObjectByID('intercom_userprefs', $to_uid, 'user_id');
            }
            if (is_null($userprefs)) {
                // userprefs table does not exist or userprefs for this user do not exist, create them with defaults
                $user['__ATTRIBUTES__']['ic_note'] = 0;
                $user['__ATTRIBUTES__']['ic_ar']   = 0;
                $user['__ATTRIBUTES__']['ic_art']  = '';
            } else {
                $user['__ATTRIBUTES__']['ic_note'] = $userprefs['email_notification'];
                $user['__ATTRIBUTES__']['ic_ar']   = $userprefs['autoreply'];
                $user['__ATTRIBUTES__']['ic_art']  = $userprefs['autoreply_text'];
            }
            // store attributes
            DBUtil::updateObject($user, 'users', '', 'uid');
            // delete entry in userprefs table
            if ($olduserprefs == true) {
                DBUtil::deleteObjectByID('intercom_userprefs', $to_uid, 'user_id');
            }
        }

        if ($user['__ATTRIBUTES__']['ic_note'] != 1) {
            return true;
        }

        // Get the needed variables for the mail

        $renderer = Zikula_View::getInstance('InterCom', false);
        $renderer->assign('message_from',UserUtil::getVar('uname', $from_uid));
        $renderer->assign('subject', $subject);
        $renderer->assign('viewinbox', ModUtil::url('InterCom', 'user', 'inbox'));
        $renderer->assign('prefs', ModUtil::url('InterCom', 'user', 'settings'));
        $renderer->assign('baseURL', System::getBaseUrl());

        $message = $renderer->fetch("mail/emailnotification.tpl");

        $fromname = ModUtil::getVar('InterCom', 'messages_fromname');
        if ($fromname == '') {
            $fromname = System::getVar('sitename');
        }

        $fromaddress = ModUtil::getVar('InterCom', 'messages_from_email');
        if ($fromaddress == '') {
            $fromaddress = System::getVar('adminmail');
        }

        $modinfo = ModUtil::getInfo(ModUtil::getIdFromName('InterCom'));
        $args = array( 'fromname'    => $fromname,
                'fromaddress' => $fromaddress,
                'toname'      => UserUtil::getVar('uname', $to_uid),
                'toaddress'   => UserUtil::getVar('email', $to_uid),
                'subject'     => ModUtil::getVar('InterCom', 'messages_mailsubject'),
                'body'        => $message,
                'headers'     => array('X-Mailer: ' . $modinfo['name'] . ' ' . $modinfo['version']));
        ModUtil::apiFunc('Mailer', 'user', 'sendmessage', $args);
        return true;
    }

    /**
     * Send an autoreply to the sender if recipient wants that
     *
     * @author chaos
     * @version
     * @return
     */
    public function autoreply($args)
    {
        // Extract expected variables
        $to_uid = $args['to_uid'];
        $from_uid = $args['from_uid'];
        $subject = $args['subject'];

        // Check if admin allowed autoreply
        $allow_autoreply = ModUtil::getVar('InterCom', 'messages_allow_autoreply');
        if ($allow_autoreply != 1) {
            return true;
        }

        // Return if the recipient does not have autoreply activated
	if (!UserUtil::getVar('ic_ar', $to_uid)) {
            return true;
        }

        // Get the needed variables for the autoreply
        $time = date("Y-m-d H:i:s");
        $this->store_message( array(
                'from_userid' => $to_uid,
                'to_userid' => $from_uid,
                'msg_subject' => $this->__('Re') . ': ' . $subject,
                'msg_time' => $time,
                'msg_text' => UserUtil::getVar('ic_art', $to_uid),
                'msg_inbox' => '1',
                'msg_outbox' => '1',
                'msg_stored' => '0'
        ));
    }

    /**
     * get available admin panel links
     *
     * @return array array of user links
     */
    public function getLinks()
    {
        $links = array();
        if (UserUtil::isLoggedIn()) {
            $links[] = array(
                'url' => $this->get('router')->generate('zikulaintercommodule_user_inbox'),
                'text' => $this->__('Inbox'),
                'icon' => 'inbox'
            );
            $links[] = array(
                'url' => $this->get('router')->generate('zikulaintercommodule_user_outbox'),
                'text' => $this->__('Outbox'),
                'icon' => 'external-link'
            );
            $links[] = array(
                'url' => $this->get('router')->generate('zikulaintercommodule_user_archive'),
                'text' => $this->__('Archive'),
                'icon' => 'wrench'
            );
            if ($this->getVar('allow_emailnotification')|| $this->getVar('allow_autoreply')) {
                $links[] = array(
                    'url' => $this->get('router')->generate('zikulaintercommodule_admin_index'),
                    'text' => $this->__('Settings'),
                    'class' => 'z-icon-es-config'
                );
            }
            $links[] = array(
                'url' => $this->get('router')->generate('zikulaintercommodule_user_new'),
                'text' => $this->__('New message'),
                'icon' => 'file'
            );
        }
        return $links;
    }

}