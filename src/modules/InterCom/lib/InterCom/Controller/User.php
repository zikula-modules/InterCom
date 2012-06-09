<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

class InterCom_Controller_User extends Zikula_AbstractController
{
    /**
     * The main user function -
     * This function redirects to the inbox function.
     *
     * @author Chasm
     * @version 1.0
     * @return
     */
    public function main()
    {
        // This is a user only module - redirect everyone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        return System::redirect(ModUtil::url('InterCom', 'user', 'inbox'));
    }

    /**
     * Function to modify the user preferences
     *
     * @author chaos
     * @version 1.0
     * @return
     */
    public function settings()
    {
        // This is a user only module - redirect everyone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        // and read the user data incl. the attributes
        $attr = UserUtil::getVar('__ATTRIBUTES__', $uid);

        // Get the admin preferences
        $allow_emailnotification = $this->getVar('messages_allow_emailnotification');
        $allow_autoreply = $this->getVar('messages_allow_autoreply');

        // Create output object
		$this->view->setCaching(false); // not suitable for caching
        $this->view->add_core_data();
        $this->view->assign('email_notification', $attr['ic_note']);
        $this->view->assign('autoreply',          $attr['ic_ar']);
        $this->view->assign('autoreply_text',     $attr['ic_art']);
        return $this->view->fetch('user/prefs.tpl');
    }

    /**
     * Update the user preferences
     *
     * @author chaos
     * @version 1.0
     * @param
     */
    public function modifyprefs()
    {
        // Security check
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        ModUtil::apiFunc('InterCom', 'user', 'updateprefs');
        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('InterCom', 'user', 'settings'));
    }

    /**
     * View inbox -
     * This function shows the inbox.
     *
     * @author Chasm
     * @version 1.0
     * @param  int $sort
     * @return
     */
    public function inbox()
    {
        // This is a user only module - redirect everyone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        // Get variables for autoreply
        $autoreply = 0;
        if ($this->getVar('messages_allow_autoreply') == 1) {
            // and read the user data incl. the attributes
            $attr = UserUtil::getVar('__ATTRIBUTES__'); DBUtil::selectObjectByID('users', UserUtil::getVar('uid'), 'uid', null, null, null, false);
            $autoreply = $attr['ic_ar'];
        }

        // Get startnum and perpage parameter for pager
        $startnum = (int)FormUtil::getPassedValue('startnum', 0, 'GETPOST');
        $messagesperpage = $this->getVar('messages_perpage', 25);

        // Get parameter for inboxlimit
        $inboxlimit = $this->getVar('messages_limitinbox');

        // Get parameters from whatever input we need.
        $sort = (int)FormUtil::getPassedValue('sort', 3, 'GETPOST');

        // Get the amount of messages within each box
        $totalarray = ModUtil::apiFunc('InterCom', 'user', 'getmessagecount', '');

        $messagearray = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => 'msg_inbox',
                'orderby'  => $sort,
                'startnum' => $startnum,
                'perpage'  => $messagesperpage));

        // inline js for language defines
        $this->addinlinejs();

        if(ModUtil::isHooked('BBSmile', 'InterCom')) {
            PageUtil::addVar('javascript', 'prototype');
            PageUtil::addVar('javascript', 'modules/BBSmile/javascript/dosmilie.js');
            PageUtil::addVar('javascript', 'modules/BBSmile/javascript/control_modal.js');
            PageUtil::addVar('stylesheet', ThemeUtil::getModuleStylesheet('BBSmile'));
        }
        if(ModUtil::isHooked('BBCode', 'InterCom')) {
            PageUtil::addVar('javascript', 'prototype');
            PageUtil::addVar('javascript', 'modules/BBCode/javascript/bbcode.js');
            PageUtil::addVar('stylesheet', ThemeUtil::getModuleStylesheet('BBCode'));
        }

        // Create output object
		$this->view->setCaching(false); // not suitable for caching
        $this->view->add_core_data();
        $this->view->assign('boxtype',          'inbox');
        $this->view->assign('currentuid',       UserUtil::getVar('uid'));
        $this->view->assign('messagearray',     $messagearray);
        $this->view->assign('sortarray',        $sortarray);
        $this->view->assign('getmessagecount',  $totalarray);
        $this->view->assign('sortbar_target',   'inbox');
        $this->view->assign('messagesperpage',  $messagesperpage);
        $this->view->assign('autoreply',        $autoreply);
        $this->view->assign('sort',             $sort);
        $this->view->assign('ictitle',          DataUtil::formatForDisplay($this->__('Inbox')));
        // Return output object
        return $this->view->fetch('user/view.tpl');
    }

    /**
     * View outbox -
     * This function shows the outbox.
     *
     * @author Chasm
     * @version 1.0
     * @param $sort
     * @return
     */
    public function outbox()
    {
        // This is a user only module - redirect everyone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        // Maintenance message
        if ($this->getVar('messages_active') == 0 && !SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            $this->view->setCaching(false);
            return $this->view->fetch('user/maintenance.tpl');
        }

        // Get startnum and perpage parameter for pager
        $startnum = (int)FormUtil::getPassedValue('startnum', 0, 'GETPOST');

        $messagesperpage = $this->getVar('messages_perpage');
        if (empty ($messagesperpage) || !is_numeric($messagesperpage)) {
            $messagesperpage = 25;
        }

        // Get parameter for inboxlimit
        $outboxlimit = $this->getVar('messages_limitoutbox');

        // Get parameters from whatever input we need.
        $sort = (int)FormUtil::getPassedValue('sort', 3, 'GETPOST');

        if (!is_numeric($sort)) {
            return LogUtil::registerArgsError;
        }

        // Get the amount of messages within each box
        $totalarray = ModUtil::apiFunc('InterCom', 'user', 'getmessagecount', '');

        $messagearray = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => 'msg_outbox',
                'orderby'  => $sort,
                'startnum' => $startnum,
                'perpage'  => $messagesperpage));


        for ($i = 1; $i <= $totalarray['totalout']; $i++) {
            $message = $messagearray[$i -1]['msg_id'] - 1;
            if ($messagearray[$i -1]['msg_read'] == '1' && $messagearray[$i -1]['msg_inbox'] == '1') {
                $messagearray[$i -1]['checkit_img'] = '1';
            }
            if ($messagearray[$i -1]['msg_read'] == '0' && $messagearray[$i -1]['msg_inbox'] == '1') {
                $messagearray[$i -1]['checkit_img'] = '2';
            }
            if ($messagearray[$i -1]['msg_inbox'] == '0') {
                $messagearray[$i -1]['checkit_img'] = '3';
            }
        }

        // inline js for language defines
        $this->addinlinejs();

        // Create output object
		$this->view->setCaching(false); // not suitable for caching
        $this->view->add_core_data();
        $this->view->assign('boxtype',         'outbox');
        $this->view->assign('currentuid',      UserUtil::getVar('uid'));
        $this->view->assign('messagearray',    $messagearray);
        $this->view->assign('sortarray',       $sortarray);
        $this->view->assign('getmessagecount', $totalarray);
        $this->view->assign('sortbar_target',  'outbox');
        $this->view->assign('messagesperpage', $messagesperpage);
        $this->view->assign('sort',            $sort);
        $this->view->assign('ictitle',         DataUtil::formatForDisplay($this->__('Outbox')));
        // Return output object
        return $this->view->fetch('user/view.tpl');
    }

    /**
     * View archive -
     * This function shows the archive.
     *
     * @author Chasm
     * @version 1.0
     * @param int $sort
     * @return
     */
    public function archive()
    {
        // This is a user only module - redirect everyone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        // Get startnum and perpage parameter for pager
        $startnum = (int)FormUtil::getPassedValue('startnum', 0, 'GETPOST');

        $messagesperpage = $this->getVar('messages_perpage');
        if (empty ($messagesperpage) || !is_numeric($messagesperpage)) {
            $messagesperpage = 25;
        }

        // Get parameters from whatever input we need.
        $sort = (int) FormUtil::getPassedValue('sort', 3, 'GETPOST');

        if (!is_numeric($sort)) {
            return LogUtil::registerArgsError;
        }

        // Get the amount of messages within each box
        $totalarray = ModUtil::apiFunc('InterCom', 'user', 'getmessagecount', '');

        $messagearray = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => 'msg_stored',
                'orderby'  => $sort,
                'startnum' => $startnum,
                'perpage'  => $messagesperpage));

        // inline js for language defines
        $this->addinlinejs();

        // Create output object
		$this->view->setCaching(false); // not suitable for caching
        $this->view->add_core_data();
        $this->view->assign('boxtype',         'archive');
        $this->view->assign('currentuid',      UserUtil::getVar('uid'));
        $this->view->assign('messagearray',    $messagearray);
        $this->view->assign('sortarray',       $sortarray);
        $this->view->assign('getmessagecount', $totalarray);
        $this->view->assign('sortbar_target',  'archive');
        $this->view->assign('messagesperpage', $messagesperpage);
        $this->view->assign('sort',            $sort);
        $this->view->assign('ictitle',          DataUtil::formatForDisplay($this->__('Archive')));
        // Return output object
        return $this->view->fetch('user/view.tpl');
    }

    /**
     * Read inbox -
     * This function shows a inbox message.
     *
     * @author Chasm
     * @version 1.0
     * @param  int $messageid
     * @return
     */
    public function readinbox()
    {
        // This is a user only module - redirect everyone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        // Get parameters from whatever input we need.
        $messageid = (int)FormUtil::getPassedValue('messageid', 0, 'GETPOST');
        if ($messageid == 0) {
            return LogUtil::registerArgsError;
        }

        $message = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => 'msg_inbox',
                'msg_id'   => $messageid));

        // Check if a message exists
        if ($message == false) {
            return LogUtil::registerError($this->__('Error! Could not find message text. Please check and try again.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
        } else {
            // Extract the info we need, unset the rest
            // Get additional informations about the poster of this message
            // Merge arrays
            $message = array_merge($message, ModUtil::apiFunc('InterCom', 'user', 'getposterdata', array('uid' => $message['from_userid'])));

            // Mark current message as read
            ModUtil::apiFunc('InterCom', 'user', 'mark_read', array ('msg_id' => $message['msg_id']));

            // Prepare text of message for display
            $message['msg_text'] = ModUtil::apiFunc('InterCom', 'user', 'prepmessage_for_display',
                    array('msg_text' => $message['msg_text']));
            // URL - the db may contain false urls, try to clean them
            $message['url'] = ModUtil::apiFunc('InterCom', 'user', 'prepurl_for_display',
                    array('url' => $message['url']));

            // Create output object
            $this->view->setCaching(false); // not suitable for caching
            $this->view->add_core_data();
            $this->view->assign('currentuid', UserUtil::getVar('uid'));
            $this->view->assign('boxtype', 'inbox');
            $this->view->assign('message',  $message);
            return $this->view->fetch('user/readpm.tpl');
        }
    }

    /**
     * read outbox -
     * This function shows a outbox message.
     *
     * @author Chasm
     * @version 1.0
     * @param  int $messageid
     * @return
     */
    public function readoutbox()
    {
        // This is a user only module - redirect everyone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        // Get parameters from whatever input we need.
        $messageid = (int)FormUtil::getPassedValue('messageid', 0, 'GETPOST');

        if ($messageid == 0) {
            return LogUtil::registerArgsError;
        }

        $message = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => 'msg_outbox',
                'msg_id'   => $messageid));

        // no message? display error
        if ($message == false) {
            return LogUtil::registerError($this->__('Error! Could not find message text. Please check and try again.'), null, ModUtil::url('InterCom', 'user', 'outbox'));
            // message exits? continue
        } else {
            // get additional informations about the poster of this message
            // merge arrays
            $message = array_merge($message, ModUtil::apiFunc('InterCom', 'user', 'getposterdata', array('uid' => $message['to_userid'])));

            // Prepare text of mesage for display
            $message['msg_text'] = ModUtil::apiFunc('InterCom', 'user', 'prepmessage_for_display',
                    array ('msg_text' => $message['msg_text']));

            // URL - the db may contain false urls, try to clean them
            $message['url'] = ModUtil::apiFunc('InterCom', 'user', 'prepurl_for_display',
                    array ('url' => $message['url']));

            // Create output object
            $this->view->setCaching(false); // not suitable for caching
            $this->view->add_core_data();
            $this->view->assign('currentuid', UserUtil::getVar('uid'));
            $this->view->assign('boxtype', 'outbox');
            $this->view->assign('message',  $message);
            return $this->view->fetch('user/readpm.tpl');
        }
    }

    /**
     * read archive -
     * This function shows an archive message.
     *
     * @author Chasm
     * @version 1.0
     * @param  int $messageid
     * @return
     */
    public function readarchive()
    {
        // This is a user only module - redirect everyone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        // Get parameters from whatever input we need.
        $messageid = (int) FormUtil::getPassedValue('messageid', 0, 'GETPOST');

        if ($messageid == 0) {
            return LogUtil::registerArgsError;
        }

        $message = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => 'msg_stored',
                'msg_id'   => $messageid));

        // no message? display error
        if ($message == false) {
            return LogUtil::registerError($this->__('Error! Could not find message text. Please check and try again.'), null, ModUtil::url('InterCom', 'user', 'archive'));
            // message exits? continue
        } else {
            // get additional informations about the poster of this message
            // merge arrays
            $message = array_merge($message, ModUtil::apiFunc('InterCom', 'user', 'getposterdata', array('uid' => $message['from_userid'])));

            // Prepare text of mesage for display
            $message['msg_text'] = ModUtil::apiFunc('InterCom', 'user', 'prepmessage_for_display',
                    array('msg_text' => $message['msg_text']));
            // URL - the db may contain false urls, try to clean them
            $message['url'] = ModUtil::apiFunc('InterCom', 'user', 'prepurl_for_display',
                    array('url' => $message['url']));

            // Create output object
            $this->view->setCaching(false); // not suitable for caching
            $this->view->add_core_data();
            $this->view->assign('currentuid', UserUtil::getVar('uid'));
            $this->view->assign('boxtype', 'archive');
            $this->view->assign('message',  $message);
            return $this->view->fetch('user/readpm.tpl');
        }
    }

    /**
     * reply inbox -
     * This function shows the reply form for a inbox message.
     *
     * @author Chasm
     * @version 1.0
     * @return
     */
    public function replyinbox()
    {
        // This is a user only module - redirect everyone else
        $notauth = $this->checkuser($uid, ACCESS_COMMENT);
        if ($notauth) return $notauth;

        // Get parameters from whatever input we need.
        $messageid = (int)FormUtil::getPassedValue('messageid', 0, 'GETPOST');
        if ($messageid == 0) {
            return LogUtil::registerArgsError;
        }

        $message = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => 'msg_inbox',
                'msg_id'   => $messageid));

        // Chasm: display errormessage if no message is returned
        if ($message == false) {
            return LogUtil::registerError($this->__('Error! Could not find message text. Please check and try again.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
            // Chasm: message exits, go on
        } else {
            // Chasm: get details...
            $fromuserdata = ModUtil::apiFunc('InterCom', 'user', 'getposterdata', array('uid' => $message['from_userid']));
            $touserdata = ModUtil::apiFunc('InterCom', 'user', 'getposterdata', array('uid' => $message['to_userid']));
            // Chasm: only the person who recived the PM may reply...
            if (UserUtil::getVar('uid') != $touserdata['uid']) {
                return LogUtil::registerError($this->__('Error! Message has no recipient. Do not try this again!'), null, ModUtil::url('InterCom', 'user', 'inbox'));
            }

            // Prepare text of mesage for display
            $message['msg_text'] = ModUtil::apiFunc('InterCom', 'user', 'prepmessage_for_form',
                    array('msg_text' => $message['msg_text']));
            // Create output object
            $this->view->setCaching(false); // not suitable for caching
            $this->view->add_core_data();
            $this->view->assign('pmtype',       'reply');
            $this->view->assign('currentuid',   UserUtil::getVar('uid'));
            $this->view->assign('message',      $message);
            $this->view->assign('allowsmilies', ModUtil::isHooked('BBSmile', 'InterCom'));
            $this->view->assign('allowbbcode',  ModUtil::isHooked('BBCode', 'InterCom'));
            $this->view->assign('allowhtml',    $this->getVar('messages_allowhtml'));
            $this->view->assign('allowsmilies', $this->getVar('messages_smilies'));
            $this->view->assign('msgtogroups',  SecurityUtil::checkPermission('InterCom::', 'MsgToGroups::', ACCESS_COMMENT));
            $this->view->assign('to_user',      DataUtil::formatforDisplay($fromuserdata['uname']));
            $this->view->assign('to_user_string', DataUtil::formatforDisplay($fromuserdata['uname']));
            $this->view->assign('from_uname',   $touserdata['uname']);
            $this->view->assign('from_uid',     UserUtil::getVar('uid'));
            $this->view->assign('ictitle',      DataUtil::formatForDisplay($this->__('Send reply')));

            return $this->view->fetch('user/pm.tpl');
        }
    }

    /**
     * new pm -
     * This function shows the form for a new message.
     *
     * @author Chasm
     * @version 1.0
     * @return
     */
    public function newpm($args)
    {
        // This is a user only module - redirect everyone else
        $notauth = $this->checkuser($uid, ACCESS_COMMENT);
        if ($notauth) return $notauth;

        // Check if outboxlimit is reached
        if (!SecurityUtil::checkPermission("InterCom::", "::", ACCESS_ADMIN)) {
            $totalarray = ModUtil::apiFunc('InterCom', 'user', 'getmessagecount', '');
            if ($totalarray['totalout'] >= $this->getVar('messages_limitoutbox')) {
                return LogUtil::registerError($this->__('Sorry! There are too many messages in your outbox. Please delete some messages in the outbox, so that you can send further messages.'), null, ModUtil::url('InterCom', 'user', 'outbox'));
            }
        }

        // Extract expected variables
        $uid = (int)FormUtil::getPassedValue('uid', 0, 'GETPOST');
        // if uname and uid are passed, uid always overrides uname, but uid must be > 1 (guest)
        if ($uid > 1) {
            $uname = UserUtil::getVar('uname', $uid);
        } else {
            $uname = FormUtil::getPassedValue('uname', '', 'GETPOST');
            $uid = UserUtil::getIdFromName($uname);
        }
        // Check argument

        if ((!empty($uname) && $uid==false) || $uid == 1) {
            LogUtil::registerError($this->__('Error! Unknown user.') . DataUtil::formatForDisplay($uname));
            unset($uname);
            unset($uid);
        }

        // if to_user is not empty, we have been here before, this overrides the uid/uname we may have received
        // format: user1,user2,user3
        $to_user      = FormUtil::getPassedValue('to_user',     isset($args['to_user'])     ? $args['to_user']     : '', 'GETPOST');
        $to_group     = FormUtil::getPassedValue('to_group',    isset($args['to_group'])    ? $args['to_group']    : '', 'GETPOST');
        $msg_subject  = FormUtil::getPassedValue('subject',     isset($args['subject'])     ? $args['subject']     : '', 'GETPOST');
        $msg_text     = FormUtil::getPassedValue('message',        isset($args['message'])     ? $args['message']     : '', 'GETPOST');
        $msg_preview  = FormUtil::getPassedValue('msg_preview', isset($args['msg_preview']) ? $args['msg_preview'] :  0, 'GETPOST');
        $html         = (int) FormUtil::getPassedValue('html',  isset($args['html'])        ? $args['html']         : 0, 'GETPOST');

        // remove HTML if it isn't allowed or user doesn't want it
        if ($this->getVar('messages_allowhtml', 0) == 0 || $html == 1) {
            $msg_text = strip_tags($msg_text);
        }

        // Clean some variables
        //$msg_preview = DataUtil::formatforDisplay($msg_preview);
        $msg_subject = DataUtil::formatforDisplayHTML($msg_subject);
        $msg_text    = DataUtil::formatforDisplayHTML($msg_text);

        // Compose an array out of it
        $message = compact('msg_subject', 'msg_text', 'html');
        if (!$to_user == "") {
            $uname .= $to_user;
        }

        // in the template we need an array fo the facebook style user selection
        $to_user_array = array();
        if (!empty($uname)) {
            $to_user_array = explode(",", $uname);
        }
        // same for the group
        $to_group_array = array();
        if (!empty($to_group)) {
            $to_group_array =explode(",", $to_group);
        }

        // get current users id
        $currentuid = UserUtil::getVar('uid');

        //check for contacts
        $cl_buddies = array();
        if (ModUtil::available('ContactList')) {
            $cl_buddies = ModUtil::apiFunc('ContactList', 'user', 'getall',
                    array ('bid'   => $currentuid,
                    'state' => '1'));
        }

        $this->addinlinejs();

        // Create output object
		$this->view->setCaching(false); // not suitable for caching
        $this->view->add_core_data();
        $this->view->assign('pmtype',       'new');
        $this->view->assign('currentuid',   $currentuid);
        $this->view->assign('allowsmilies', ModUtil::isHooked('BBSmile', 'InterCom'));
        $this->view->assign('allowbbcode',  ModUtil::isHooked('BBCode', 'InterCom'));
        $this->view->assign('allowhtml',    $this->getVar('messages_allowhtml'));
        $this->view->assign('msgtogroups',  SecurityUtil::checkPermission('InterCom::', 'MsgToGroups::', ACCESS_COMMENT));
        $this->view->assign('msg_preview',  $msg_preview);
        $this->view->assign('to_user',      $to_user_array);
        $this->view->assign('to_user_string',$to_user);
        $this->view->assign('to_group',     $to_group_array);
        $this->view->assign('cl_buddies',   $cl_buddies);
        $this->view->assign('message',      $message);
        $this->view->assign('ictitle',      DataUtil::formatForDisplay($this->__('New message')));

        // Return output object
        return $this->view->fetch('user/pm.tpl');
    }

    /**
     * Submit private message -
     * This function stores a private message into the db.
     *
     * @author Chasm
     * @version 1.0
     * @return
     */
    public function submitpm()
    {
        // This is a user only module - redirect everone else
        $notauth = $this->checkuser($uid, ACCESS_COMMENT);
        if ($notauth) return $notauth;

        $from_uid = (int)FormUtil::getPassedValue('from_uid');
        $to_user  = FormUtil::getPassedValue('to_user', '', 'GETPOST');
        $to_group = FormUtil::getPassedValue('to_group', '', 'GETPOST');
        $subject  = FormUtil::getPassedValue('subject', '', 'GETPOST');
        $message  = FormUtil::getPassedValue('message', '', 'GETPOST');
        $msg_id   = FormUtil::getPassedValue('msg_id', 0, 'GETPOST');
        $html     = FormUtil::getPassedValue('html', 0, 'GETPOST');

        if($from_uid <> UserUtil::getVar('uid')) {
            return LogUtil::registerArgsError();
        }
        // Security check for messages to entire groups
        //if (SecurityUtil::checkPermission('InterCom::', 'MsgToGroups::', ACCESS_MODERATE)) {
        //    $to_group = FormUtil::getPassedValue('to_group');
        //} else {
        //    $to_group = '';
        //}
        if (FormUtil::getPassedValue('mail_prev_x', null, 'POST')) {
            //return ModUtil::func('InterCom', 'user', 'newpm',
             return $this->newpm('InterCom', 'user', 'newpm',
                    array ('msg_preview' => '1',
                    'to_user'     => $to_user,
                    'to_group'    => $to_group,
                    'msg_subject' => $subject,
                    'msg_text'    => $message,
                    'html'        => $html));
        }

        // Check the arguments
        if ($to_user == '' && $to_group == '') {
            LogUtil::registerError($this->__('Error! You did not enter a recipient. Please enter an recipient and try again.'), null);
            return $this->newpm(array ('to_user'     => $to_user,
                    'to_group'    => $to_group,
                    'msg_subject' => $subject,
                    'msg_text'    => $message,
                    'html'        => $html));
        }
        if ($subject == '') {
            return LogUtil::registerError($this->__('Error! Could not find the subject line. Please enter a subject line and try again.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
        }
        if ($message == '') {
            return LogUtil::registerError($this->__('Error! Could not find the message text. Please enter message text and and try again.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
        }
        if ($this->getVar('messages_allowhtml') == 0 || (isset ($html))) {
            $message = strip_tags($message);
        }

        $time = date("Y-m-d H:i:s");

        // Get variables for spam protection
        $protection_on     = $this->getVar('messages_protection_on');
        $protection_time   = $this->getVar('messages_protection_time');
        $protection_amount = $this->getVar('messages_protection_amount');
        $protection_mail   = $this->getVar('messages_protection_mail');

        // Initalize spam protection
        $antispam_count = 0;

        // Split $to_user in an array to allow more than one recipient
        $recipients = explode(",", $to_user);

        // count the messages that have been posted, it's better to say 'x messages posted" than sowing this x times
        $post_message_count = 0;

        // Send message to user(s)
        if (!empty ($to_user)) {
            foreach ($recipients as $recipient) {
                $to_uid = UserUtil::getIdFromName($recipient);
                //allow uid in recipient
                if ($to_uid == "" and is_numeric($recipient)) {
                    $to_uid = UserUtil::getVar('uid', $recipient);
                }
                if ($to_uid == "") {
                    LogUtil::registerError($this->__('Error! Unknown user.') . DataUtil::formatForDisplay($uname));
                }
                if ($protection_on == 1) {
                    $antispam_count++;
                    $antispam_arr = SessionUtil::getVar('antispam');
                    if ($antispam_arr == false) {
                        $antispam_arr = array ();
                    }

                    foreach ($antispam_arr as $key => $value) {
                        //delete entries, older then XX minutes
                        if ($key < (mktime() - (60 * $protection_time)))
                            unset ($antispam_arr[$key]);
                    }
                    //don't count messages to the same users more than once
                    if (count(array_count_values($antispam_arr)) > $protection_amount) {
                        $from_uname = UserUtil::getVar('uname', $from_uid);
                        if ($protection_mail == 1) {
                            $email_from = $this->getVar('messages_fromname');
                            $email_fromname = ModUtil::getVar('sitename');
                            $email_to = System::getVar('adminmail');
                            $email_subject = ModUtil::getVar('sitename') . '' . $this->__('Spam alert');
                            $message = $this->__('The user') . ' ' . $from_uname . ' (#' . $from_uid . ') ' . $this->__('tried to send too many private messages in too short a time. The private messaging system\'s spam prevention feature stopped the messages from being sent.');

                            $args = array (
                                    'fromname'    => $email_fromname,
                                    'fromaddress' => $email_from,
                                    'toname'      => $email_address,
                                    'toaddress'   => $email_to,
                                    'subject'     => $email_subject,
                                    'body'        => $message,
                                    'headers'     => array (
                                            'X-Mailer: ' . $modinfo['name'] . ' ' . $modinfo['version']
                                    )
                            );
                            ModUtil::apiFunc('Mailer', 'user', 'sendmessage', $args);
                        }
                        SessionUtil::setVar('antispam', $antispam_arr);
                        return LogUtil::registerError($this->__('Sorry! You have triggered the private messaging system\'s spam prevention feature by trying to send messages to too many users in too short a time.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
                    }
                    $antispam_arr[mktime() + $antispam_count] = $to_uid;
                    SessionUtil::setVar('antispam', $antispam_arr);
                }
                //check if blocked in postBuddy
                if (ModUtil::available('postBuddy')) {
                    $buddy = ModUtil::apiFunc('postBuddy', 'user', 'get',
                            array('uid' => $to_uid,
                            'bid' => $from_uid));
                    if ($buddy['state'] == 'DENIED') {
                        LogUtil::registerStatus($this->__('Sorry! The recipient you entered has blocked the reception of messages from you. Unfortunately, your message will not be sent.'));
                    }
                }
                //check if blocked in ContactList
                if (ModUtil::available('ContactList')) {
                    $isIgnored = ModUtil::apiFunc('ContactList', 'user', 'isIgnored',
                            array('uid'  => $to_uid,
                            'iuid' => $from_uid));
                    if ($isIgnored) {
                        LogUtil::registerStatus($this->__('Sorry! The recipient you entered has blocked the reception of messages from you. Unfortunately, your message will not be sent.'));
                    }
                }
                $from_uid = UserUtil::getVar('uid');
                if (isset ($msg_id)) {
                    ModUtil::apiFunc('InterCom', 'user', 'mark_replied',
                            array ('msg_id'=> $msg_id));
                }
                ModUtil::apiFunc('InterCom', 'user', 'store_message',
                        array ('from_userid' => $from_uid,
                        'to_userid'   => $to_uid,
                        'msg_subject' => $subject,
                        'msg_time'    => $time,
                        'msg_text'    => $message,
                        'msg_inbox'   => '1',
                        'msg_outbox'  => '1',
                        'msg_stored'  => '0'));
                ModUtil::apiFunc('InterCom', 'user', 'autoreply',
                        array ('to_uid'      => $to_uid,
                        'from_uid'    => $from_uid,
                        'subject'     => $subject));
                $post_message_count++;
                LogUtil::registerStatus($this->__('Done! Message sent.'));
            }
        }

        // Split $to_group in an array to allow more than one group
        $grouprecipients = explode(",", $to_group);

        // Send message to group(s)
        if (!empty ($to_group)) {
            foreach ($grouprecipients as $grouprecipient) {
                $to_groupid = ModUtil::apiFunc('Groups', 'admin', 'getgidbyname', array ('name' => $grouprecipient));
                if ($to_groupid == false) {
                    LogUtil::registerError($this->__('Error! Unknown group.') . DataUtil::formatForDisplay($grouprecipient));
                }
                $groupinfo = ModUtil::apiFunc('Groups', 'user', 'get', array('gid' => $to_groupid));
                foreach ($groupinfo['members'] as $to_uid => $dummy) {
                    $from_uid = UserUtil::getVar('uid');
                    ModUtil::apiFunc('InterCom', 'user', 'store_message',
                            array ('from_userid'  => $from_uid,
                            'to_userid'    => $to_uid,
                            'msg_subject'  => $subject,
                            'msg_time'     => $time,
                            'msg_text'     => $message,
                            'msg_inbox'    => '1',
                            'msg_outbox'   => '1',
                            'msg_stored'   => '0'));
                    //ModUtil::apiFunc('InterCom', 'user', 'autoreply', array('to_uid' => $to_uid, 'from_uid' => $from_uid, 'image' => $image, 'subject' => $subject));
                    $post_message_count++;
                }
            }
        }

        if($post_message_count > 0) {
            LogUtil::registerStatus($this->_fn('Done! Sent %s message.', 'Done! Sent %s messages.', $post_message_count, $post_message_count));
        }

        return System::redirect(ModUtil::url('InterCom', 'user', 'inbox'));
    }

    /**
     * Switchaction -
     * Redirects form input to the specific function
     *
     * @author Chasm
     * @version 1.0
     * @return
     */
    public function switchaction()
    {
        // This is a user only module - redirect everone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        // Get parameters from whatever input we need.
        // Chasm: messageid my be an array! no typecasting!
        $msg_id     = FormUtil::getPassedValue('messageid');
        $save       = FormUtil::getPassedValue('save', '', 'GETPOST');
        $delete     = FormUtil::getPassedValue('delete', '', 'GETPOST');

        if ($save != '') {
            return System::redirect(ModUtil::url('InterCom', 'user', 'storepm',
                    array('messageid' => $msg_id)));
        }
        if ($delete != '') {
            return System::redirect(ModUtil::url('InterCom', 'user', 'deletefrominbox',
                    array('messageid' => $msg_id)));
        }

        return System::redirect(ModUtil::url('InterCom', 'user', 'inbox'));
    }

    /**
     * delete a message from the inbox
     *
     * @author Landseer
     * @version 2.0
     * @return
     */
    public function deletefrominbox()
    {
        return $this->deletepm('msg_inbox', 'inbox');
    }

    /**
     * delete a message from the archive
     *
     * @author Landseer
     * @version 2.0
     * @return
     */
    public function deletefromarchive()
    {
        return $this->deletepm('msg_stored', 'archive');
    }

    /**
     * delete a message from the outbox
     *
     * @author Landseer
     * @version 2.0
     * @return
     */
    public function deletefromoutbox()
    {
        return $this->deletepm('msg_outbox', 'outbox');
    }

    /**
     * Delete private message -
     * Marks a private message as deleted
     *
     * @author Chasm
     * @version 1.0
     * @return
     */
    protected function deletepm($msg_type, $forwardfunc)
    {
        // This is a user only module - redirect everone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        // Get parameters from whatever input we need.
        // Chasm: messageid my be an array! no typecasting!
        $msg_id = FormUtil::getPassedValue('messageid');

        if (!is_array($msg_id)) {
            // create a fake array
            $msg_id = array($msg_id);
        }

        $status = false;
        foreach ($msg_id as $single_msg_id) {
            $status = ModUtil::apiFunc('InterCom', 'user', 'delete',
                    array('msg_id'   => $single_msg_id,
                    'msg_type' => $msg_type));
            if (!$status) {
                return LogUtil::registerError($this->__('Error! Could not delete the message. Please check the reason for this, resolve the problem and try again.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
            }
        }
        LogUtil::registerStatus($this->__('Done! Message deleted.'));
        return System::redirect(ModUtil::url('InterCom', 'user', $forwardfunc));
    }

    /**
     * Store as pm in the archive
     *
     * @author Chasm
     * @version 1.0
     * @return
     */
    public function storepm()
    {
        // This is a user only module - redirect everone else
        $notauth = $this->checkuser($uid, ACCESS_READ);
        if ($notauth) return $notauth;

        // Check if archivelimit is reached
        if (!SecurityUtil::checkPermission("InterCom::", "::", ACCESS_ADMIN)) {
            $totalarray = ModUtil::apiFunc('InterCom', 'user', 'getmessagecount', '');
            if ($totalarray['totalarchive'] >= $this->getVar('messages_limitarchive')) {
                return LogUtil::registerError( $this->__('Sorry! There are too many messages in the archive. Please delete some messages from the archive, to enable archiving of further messages.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
            }
        }

        // Get parameters from whatever input we need.
        // Chasm: messageid may be an array! no typecasting!
        $msg_id = FormUtil::getPassedValue('messageid');
        if (!is_array($msg_id)) {
            $msg_id = array($msg_id);
        }
        $status = false;
        foreach ($msg_id as $single_msg_id) {
            $status = ModUtil::apiFunc('InterCom', 'user', 'store',
                    array('msg_id' => $single_msg_id));
            if (!$status) {
                return LogUtil::registerError($this->__('Error! Could not save your message. Please check the reason, resolve the problem and try again.'), null, ModUtil::url('InterCom', 'user', 'inbox'));
            }
        }
        LogUtil::registerStatus($this->__('Done! Message archived.'));
        return System::redirect(ModUtil::url('InterCom', 'user', 'inbox'));
    }

    /**
     * messageinfo
     * used in plugin function.messageinfo.php
     * displays a ajax-window (Control.Modal) if a new message has arrived
     *
     * @author Carsten Volmer
     */

    public function messageinfo()
    {
        $out = '';
        if(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_READ)) {
            $totalarray = ModUtil::apiFunc('InterCom', 'user', 'getmessagecount', '');
            $modname = ModUtil::getName();

            if ($totalarray['popup'] >= 1 && $totalarray['unread'] >= 1 && $modname <> "InterCom") {
                PageUtil::addVar('stylesheet', 'modules/InterCom/style/modal.css');
                PageUtil::addVar('javascript', 'prototype');
                PageUtil::addVar('javascript', 'modules/InterCom/javascript/control.modal.js');

                ModUtil::apiFunc('InterCom', 'user', 'mark_popup', array('to_userid' => UserUtil::getVar('uid')));
                $this->view->setCaching(false);
                $this->view->assign('totalarray', $totalarray);
                $out = $this->view->fetch('user/messageinfo.tpl');
            }
        }
        return $out;
    }

    /**
     * forward a message from the inbox
     *
     *@params msgid int the id of the message to forward
     */

    public function forwardfrominbox()
    {
        // Security check
        $notauth = $this->checkuser($uid, ACCESS_COMMENT);
        if ($notauth) return $notauth;

        $messageid = (int)FormUtil::getPassedValue('messageid', 0, 'GETPOST');
        if ($messageid == 0) {
            return LogUtil::registerArgsError;
        }

        $message = ModUtil::apiFunc('InterCom', 'user', 'getmessages',
                array('boxtype'  => 'msg_inbox',
                'msg_id'   => $messageid));

        // Prepare text of mesage for display
        $message['msg_text'] = ModUtil::apiFunc('InterCom', 'user', 'prepmessage_for_form',
                array('msg_text' => $message['msg_text']));

        // Create output object
        $this->view->setCaching(false); // not suitable for caching
        $this->view->add_core_data();

        $bbcode = ModUtil::isHooked('BBCode', 'InterCom');
        $message['forward_text'] = DataUtil::formatForDisplay($message['msg_text']);
        if ($bbcode == true) {
            $message['forward_text'] = '[quote=' . UserUtil::getVar('uname', $message['from_userid']) . ']' . $message['forward_text'] . '[/quote]';
        }

        $this->view->assign('pmtype',       'forward');
        $this->view->assign('currentuid',   UserUtil::getVar('uid'));
        $this->view->assign('message',      $message);
        $this->view->assign('allowsmilies', ModUtil::isHooked('BBSmile', 'InterCom'));
        $this->view->assign('allowbbcode',  $bbcode);
        $this->view->assign('allowhtml',    $this->getVar('messages_allowhtml'));
        $this->view->assign('msgtogroups',  SecurityUtil::checkPermission('InterCom::', 'MsgToGroups::', ACCESS_COMMENT));
        $this->view->assign('ictitle',      DataUtil::formatForDisplay($this->__('Forward message')));

        return $this->view->fetch('user/pm.tpl');
    }

    /**
     * add inline js with language defines
     *
     */
    protected function addinlinejs()
    {
        // inline js for language defines
        $inlinejs = '<script type="text/javascript">
var loadingReply="' . DataUtil::formatForDisplay($this->__('Preparing the reply. Please be patient...'))  . '";
var storingReply = "' . DataUtil::formatForDisplay($this->__('Saving the reply. Please be patient...')) . '";
var loadingForward="' . DataUtil::formatForDisplay($this->__('Preparing to forward the message. Please be patient...'))  . '";
var storingForward = "' . DataUtil::formatForDisplay($this->__('Forwarding the message. Please be patient...')) . '";
var archivingMessage = "' . DataUtil::formatForDisplay($this->__('Archiving the message. Please be patient...')) . '";
var deletingMessage = "' . DataUtil::formatForDisplay($this->__('Deleting the message. Please be patient...')) . '";
var norecipientfound = "' . DataUtil::formatForDisplay($this->__('Error! Could not find a recipient e-mail address.')) . '";
var nosubjectfound = "' . DataUtil::formatForDisplay($this->__('Error! Could not find a subject line for the message.')) . '";
var nomessagefound = "' . DataUtil::formatForDisplay($this->__('Error! Could not find any message text for the message.')) . '";
var messageposted = "' . DataUtil::formatfordisplay($this->__('Done! Message sent.')) . '";
var messagearchived = "' . DataUtil::formatfordisplay($this->__('Done! Message archived.')) . '";
var messagedeleted = "' . DataUtil::formatfordisplay($this->__('Done! Message deleted.')) . '";
var userdeleted = "' . DataUtil::formatfordisplay($this->__('Sorry! You cannot reply to the message from this user because the person\'s user account has been deleted.')) . '";
</script>';
        PageUtil::addVar('header', $inlinejs); // rawtext => header
        return true;
    }
    
    /***
     * Do all user checks in one method:
     * Check if logged in, has correct access, and if site is disabled
     * Returns the appropriate error/return value if failed, which can be
     *          returned by calling method.
     * Returns false if use has permissions.
     * On exit, $uid has the user's UID if logged in.
     */

    protected function checkuser(&$uid, $access = ACCESS_READ)
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