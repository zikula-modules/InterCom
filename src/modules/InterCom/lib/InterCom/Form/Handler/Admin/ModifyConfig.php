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

class InterCom_Form_Handler_Admin_ModifyConfig extends Zikula_Form_AbstractHandler
{

    function initialize(Zikula_Form_View $view)
    {
        $view->caching = false;

        $view->assign($this->getVars());

        $welcomemessage = $this->getVar('InterCom', 'messages_welcomemessage');
        $welcomemessagesubject = $this->getVar('InterCom', 'messages_welcomemessagesubject');
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


    function handleCommand(Zikula_Form_View $view, &$args)
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

            if (is_null($data['messages_limitinbox'])) {
                $ifield = & $view->getPluginById('messages_limitinbox');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The inbox maximum capacity is missing.')));
                $ok = false;
            } else {
                $this->setVar('messages_limitinbox', $data['messages_limitinbox']);
            }

            if(is_null($data['messages_limitoutbox'])) {
                $ifield = & $view->getPluginById('messages_limitoutbox');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The outbox maximum capacity is missing.')));
                $ok = false;
            } else {
                $this->setVar('messages_limitoutbox', $data['messages_limitoutbox']);
            }

            if(is_null($data['messages_limitarchive'])) {
                $ifield = & $view->getPluginById('messages_limitarchive');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The archive maximum capacity is missing.')));
                $ok = false;
            } else {
                $this->setVar('messages_limitarchive', $data['messages_limitarchive']);
            }

            $this->setVar('messages_allowhtml', $data['messages_allowhtml']);
            $this->setVar('messages_allowsmilies', $data['messages_allowsmilies']);

            if (is_null($data['messages_perpage'])) {
                $ifield = & $view->getPluginById('messages_perpage');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The number of messages to display per page is missing.')));
                $ok = false;
            } else {
                $this->setVar('messages_perpage', $data['messages_perpage']);
            }

            $this->setVar('messages_allow_emailnotification', $data['messages_allow_emailnotification']);
            if ($data['messages_allow_emailnotification'] == true) {
                if(empty($data['messages_mailsubject'])) {
                    $ifield = & $view->getPluginById('messages_mailsubject');
                    $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The subject line for notification e-mail message is missing.')));
                    $ok = false;
                } else {
                    $this->setVar('messages_mailsubject', $data['messages_mailsubject']);
                }
            }

            $this->setVar('messages_force_emailnotification', $data['messages_force_emailnotification']);
            $this->setVar('messages_fromname', $data['messages_fromname']);
            $this->setVar('messages_from_email', $data['messages_from_email']);

            $this->setVar('messages_welcomemessage_send', $data['messages_welcomemessage_send'] );
            // Save values if we are turning it on.

            if ($data['messages_welcomemessage_send']==true) {
                if (empty($data['messages_welcomemessage'])) {
                    $ifield = & $view->getPluginById('messages_welcomemessage');
                    $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The welcome message text is missing.')));
                    $ok = false;
                } else {
                    $this->setVar('messages_welcomemessage', $data['messages_welcomemessage']);
                }
                if (empty($data['messages_welcomemessagesender'])) {
                    if ($data['messages_createhookactive'] == true) {
                        $ifield = & $view->getPluginById('messages_welcomemessagesender');
                        $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The sender for the welcome message is missing.')));
                        $ok = false;
                    }
                } else if (UserUtil::getIdFromName($data['messages_welcomemessagesender'])==false) {
                        $ifield = & $view->getPluginById('messages_welcomemessagesender');
                        $ifield->setError(DataUtil::formatForDisplay(__('Error! Could not find this user.')));
                        $ok = false;
                } else {
                    $this->setVar('messages_welcomemessagesender', $data['messages_welcomemessagesender']);
                }
                if(empty($data['messages_welcomemessagesubject'])) {
                    if ($data['messages_createhookactive'] == true) {
                        $ifield = & $view->getPluginById('messages_welcomemessagesubject');
                        $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The subject line for the welcome message is missing.')));
                        $ok = false;
                    }
                } else {
                    $this->setVar('messages_welcomemessagesubject', $data['messages_welcomemessagesubject']);
                }
                $this->setVar('messages_savewelcomemessage', $data['messages_savewelcomemessage']);
            }

            $this->setVar('messages_allow_autoreply', $data['messages_allow_autoreply']);

            $this->setVar('messages_active', $data['messages_active']);
            if (empty($data['messages_maintain'])) {
                if ($data['messages_active'] == true) {
                    $ifield = & $view->getPluginById('messages_maintain');
                    $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The maintenance notice text is missing.')));
                    $ok = false;
                }
            } else {
                $this->setVar('messages_maintain', $data['messages_maintain']);
            }

            $this->setVar('messages_userprompt_display', $data['messages_userprompt_display']);
            if (empty($data['messages_userprompt'])) {
                if ($data['messages_userprompt_display'] == true) {
                    $ifield = & $view->getPluginById('messages_userprompt');
                    $ifield->setError(DataUtil::formatForDisplay($this->__('Error! The user information is missing.')));
                    $ok = false;
                }
            } else {
                $this->setVar('messages_userprompt', $data['messages_userprompt']);
            }

            $this->setVar('messages_protection_on', $data['messages_protection_on']);
            $this->setVar('messages_protection_time', $data['messages_protection_time']);
            $this->setVar('messages_protection_amount', $data['messages_protection_amount']);
            $this->setVar('messages_protection_mail', $data['messages_protection_mail']);
            $this->setVar('disable_ajax', $data['disable_ajax']);

            if ($ok === false) {
                return false;
            }

            LogUtil::registerStatus($this->__('Done! Saved your settings changes.'));
        }

        return true;
    }

}
