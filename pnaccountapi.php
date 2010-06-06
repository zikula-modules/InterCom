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

/**
 * Return an array of items to show in the your account panel
 *
 * @return   array   array of items, or false on failure
 */
function InterCom_accountapi_getall($args)
{
    $dom = ZLanguage::getModuleDomain('InterCom');

    // the array that will hold the options
    $items = null;

    // show link for users only
    if(!pnUserLoggedIn()) {
        // not logged in
        return $items;
    }

    // Create an array of links to return
    if(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_OVERVIEW)) {
        pnModLangLoad('InterCom', 'user');
        $items = array(array('url'     => pnModURL('InterCom', 'user', 'settings'),
                             'title'   => __('Private messaging settings', $dom),
                             'icon'    => 'userconfig.png'),
                       array('url'     => pnModURL('InterCom', 'user', 'main'),
                             'title'   => __('Private messaging mailbox', $dom),
                             'icon'    => 'viewinbox.gif'));
    }

    // Return the items
    return $items;
}
