<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 * License
 * -------
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @author InterCom development team
 * @link http://code.zikula.org/intercom/ Support and documentation
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 *
 */

class InterCom_Form_Handler_Admin_ModifyConfig extends Form_Handler
{

    function initialize(&$view)
    {
        $view->caching = false;
        $view->add_core_data();
        $view->assign('createhookactive', ModUtil::isHooked('InterCom', 'Users'));
        $welcomemessage = ModUtil::getVar('InterCom', 'messages_welcomemessage');
        $welcomemessagesubject = ModUtil::getVar('InterCom', 'messages_welcomemessagesubject');
        $intlwelcomemessage = '';
        if (StringUtil::left($welcomemessagesubject, 1) == '_') {
            $intlwelcomemessage = constant($welcomemessagesubject) . "\n\n";
        }
        if (StringUtil::left($welcomemessage, 1) == '_') {
            $intlwelcomemessage .= constant($welcomemessage);
        }
        $view->assign('intlwelcomemessage', $intlwelcomemessage);
        return true;
    }


    function handleCommand(&$view, &$args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError(ModUtil::url('InterCom', 'admin', 'main'));
        }
        if ($args['commandName'] == 'submit') {
            if (!$view->isValid()) {
                return false;
            }

            $ok = true;
            $data = $view->getValues();

            if(is_null($data['messages_limitinbox'])) {
                $ifield = & $view->getPluginById('messages_limitinbox');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The inbox maximum capacity is missing.', $dom)));
                $ok = false;
            } else {
                ModUtil::setVar('InterCom', 'messages_limitinbox', $data['messages_limitinbox']);
            }

            if(is_null($data['messages_limitoutbox'])) {
                $ifield = & $view->getPluginById('messages_limitoutbox');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The outbox maximum capacity is missing.', $dom)));
                $ok = false;
            } else {
                ModUtil::setVar('InterCom', 'messages_limitoutbox', $data['messages_limitoutbox']);
            }

            if(is_null($data['messages_limitarchive'])) {
                $ifield = & $view->getPluginById('messages_limitarchive');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The archive maximum capacity is missing.', $dom)));
                $ok = false;
            } else {
                ModUtil::setVar('InterCom', 'messages_limitarchive', $data['messages_limitarchive']);
            }

            ModUtil::setVar('InterCom', 'messages_allowhtml', $data['messages_allowhtml']);
            ModUtil::setVar('InterCom', 'messages_allowsmilies', $data['messages_allowsmilies']);

            if(is_null($data['messages_perpage'])) {
                $ifield = & $view->getPluginById('messages_perpage');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The number of messages to display per page is missing.', $dom)));
                $ok = false;
            } else {
                ModUtil::setVar('InterCom', 'messages_perpage', $data['messages_perpage']);
            }

            ModUtil::setVar('InterCom', 'messages_allow_emailnotification', $data['messages_allow_emailnotification']);
            if ($data['messages_allow_emailnotification'] == true) {
                if(empty($data['messages_mailsubject'])) {
                    $ifield = & $view->getPluginById('messages_mailsubject');
                    $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The subject line for notification e-mail message is missing.', $dom)));
                    $ok = false;
                } else {
                    ModUtil::setVar('InterCom', 'messages_mailsubject', $data['messages_mailsubject']);
                }
            }

            ModUtil::setVar('InterCom', 'messages_force_emailnotification', $data['messages_force_emailnotification']);
            ModUtil::setVar('InterCom', 'messages_fromname', $data['messages_fromname']);
            ModUtil::setVar('InterCom', 'messages_from_email', $data['messages_from_email']);

            // turn the create hook on/off

//            TODO: Fix hooks for Zikula 1.3
//            if ($data['messages_createhookactive']==true) {
//                ModUtil::apiFunc('Modules', 'admin', 'enablehooks', array('callermodname' => 'Users', 'hookmodname' => 'InterCom'));
//            } else {
//                ModUtil::apiFunc('Modules', 'admin', 'disablehooks', array('callermodname' => 'Users', 'hookmodname' => 'InterCom'));
//            }
            if(empty($data['messages_welcomemessage'])) {
                if ($data['messages_createhookactive'] == true) {
                    $ifield = & $view->getPluginById('messages_welcomemessage');
                    $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The welcome message text is missing.', $dom)));
                    $ok = false;
                }
            } else {
                ModUtil::setVar('InterCom', 'messages_welcomemessage', $data['messages_welcomemessage']);
            }
            if(empty($data['messages_welcomemessagesender'])) {
                if ($data['messages_createhookactive'] == true) {
                    $ifield = & $view->getPluginById('messages_welcomemessagesender');
                    $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The sender for the welcome message is missing.', $dom)));
                    $ok = false;
                }
            } else if (UserUtil::getIdFromName($data['messages_welcomemessagesender'])==false) {
                    $ifield = & $view->getPluginById('messages_welcomemessagesender');
                    $ifield->setError(DataUtil::formatForDisplay(__('Error! Could not find this user.', $dom)));
                    $ok = false;
            } else {
                ModUtil::setVar('InterCom', 'messages_welcomemessagesender', $data['messages_welcomemessagesender']);
            }
            if(empty($data['messages_welcomemessagesubject'])) {
                if ($data['messages_createhookactive'] == true) {
                    $ifield = & $view->getPluginById('messages_welcomemessagesubject');
                    $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The subject line for the welcome message is missing.', $dom)));
                    $ok = false;
                }
            } else {
                ModUtil::setVar('InterCom', 'messages_welcomemessagesubject', $data['messages_welcomemessagesubject']);
            }

            ModUtil::setVar('InterCom', 'messages_allow_autoreply', $data['messages_allow_autoreply']);

            ModUtil::setVar('InterCom', 'messages_active', $data['messages_active']);
            if(empty($data['messages_maintain'])) {
                if ($data['messages_active'] == true) {
                    $ifield = & $view->getPluginById('messages_maintain');
                    $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The maintenance notice text is missing.', $dom)));
                    $ok = false;
                }
            } else {
                ModUtil::setVar('InterCom', 'messages_maintain', $data['messages_maintain']);
            }

            ModUtil::setVar('InterCom', 'messages_userprompt_display', $data['messages_userprompt_display']);
            if(empty($data['messages_userprompt'])) {
                if ($data['messages_userprompt_display'] == true) {
                    $ifield = & $view->getPluginById('messages_userprompt');
                    $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The user information is missing.', $dom)));
                    $ok = false;
                }
            } else {
                ModUtil::setVar('InterCom', 'messages_userprompt', $data['messages_userprompt']);
            }

            ModUtil::setVar('InterCom', 'messages_protection_on', $data['messages_protection_on']);
            ModUtil::setVar('InterCom', 'messages_protection_time', $data['messages_protection_time']);
            ModUtil::setVar('InterCom', 'messages_protection_amount', $data['messages_protection_amount']);
            ModUtil::setVar('InterCom', 'messages_protection_mail', $data['messages_protection_mail']);

            if($ok == false) {
                return false;
            }

            LogUtil::registerStatus($this->__('Done! Saved your settings changes.', $dom));
        }
        return true;
    }

}
