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

class InterCom_Form_Handler_User_Preferences extends Zikula_Form_AbstractHandler
{

    private $_uid = null;

    function initialize(Zikula_Form_View $view)
    {
        $view->caching = false;

        // Security check
        if (!UserUtil::isLoggedIn()) {
            return LogUtil::registerPermissionError();
        }

        // Security check
        if (SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            $this->_uid = (int)FormUtil::getPassedValue('uid', null);

            if ( is_null($this->_uid) || $this->_uid < 1) {
                $this->_uid = UserUtil::getVar('uid');
            }
        } else {
            $this->_uid = UserUtil::getVar('uid');
        }


        // and read the user data incl. the attributes
        $attr = UserUtil::getVar('__ATTRIBUTES__', $this->_uid);

        // Create output object
        $view->assign($this->getVars());
        $view->assign($attr);

        return true;
    }


    function handleCommand(Zikula_Form_View $view, &$args)
    {

        if ($args['commandName'] == 'cancel') {
            return true;
        }


        if (!$view->isValid()) {
            return false;
        }

        $data = $view->getValues();


        // Get parameters from environment
        // ic_note: email notifiaction yes/no
        // ic_ar  : autoreply yes/no
        // ic_art  : autoreply text
        // store attributes
        UserUtil::setVar('ic_note', $data['ic_note'], $this->_uid);
        UserUtil::setVar('ic_ar', $data['ic_ar'], $this->_uid);
        UserUtil::setVar('ic_art', $data['ic_art'], $this->_uid);

        // delete entry in the old intercom_userprefs table if the table exists
        $tbls = DBUtil::metaTables();
        // if old intercom_userprefs table exists, try to delete the values for user $uid
        if (in_array('intercom_userprefs', $tbls)) {
            DBUtil::deleteObjectByID('intercom_userprefs', $this->_uid, 'user_id');
        }


        LogUtil::registerStatus($this->__('Done! Saved your settings changes.'));

        return true;
    }

}
