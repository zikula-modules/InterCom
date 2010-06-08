<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
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
