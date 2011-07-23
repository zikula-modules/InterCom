<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

class InterCom_Api_Account extends Zikula_AbstractApi
{
    /**
     * Return an array of items to show in the your account panel
     *
     * @return   array   array of items, or false on failure
     */
    public function getall($args)
    {
        // the array that will hold the options
        $items = null;

        // show link for users only
        if(!UserUtil::isLoggedIn()) {
            // not logged in
            return $items;
        }

        // Create an array of links to return
        if(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_OVERVIEW)) {
            $items = array(array('url'     => ModUtil::url('InterCom', 'user', 'settings'),
                            'title'   => $this->__('Private messaging settings'),
                            'icon'    => 'userconfig.png'),
                    array('url'     => ModUtil::url('InterCom', 'user', 'main'),
                            'title'   => $this->__('Private messaging mailbox'),
                            'icon'    => 'viewinbox.gif'));
        }

        // Return the items
        return $items;
    }
}