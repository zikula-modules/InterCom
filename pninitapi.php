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

function InterCom_initapi_import_messages()
{
    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }
    
    $old_msg_count = DBUtil::selectObjectCount('priv_msgs');
    // we convert messages in packages of 100. THis can be changed by adjusting the next line:
    $packagesize = 100;
    for ($cnt=0; $cnt <= $old_msg_count; $cnt=$cnt + $packagesize) {
        $msgs = DBUtil::selectObjectArray('priv_msgs', '', '', $cnt, $packagesize);
        //prayer($msgs);
        $ak = array_keys($msgs);
        foreach($ak as $key) {
            $msgs[$key]['msg_subject'] = str_replace(array('<', '>'), array('&lt;', '&gt;'), $msgs[$key]['subject']);
            $msgs[$key]['msg_text']    = str_replace(array('<br />', '<br>', '<BR>'), "\n", $msgs[$key]['msg_text']);
            $msgs[$key]['msg_replied'] = '1';
            $msgs[$key]['msg_popup']   = '1';
            $msgs[$key]['msg_inbox']   = '1';
            $msgs[$key]['msg_outbox']  = '1';
            $msgs[$key]['msg_stored']  = '0';
        }
        //prayer($msgs);
        DBUtil::insertObjectArray($msgs, 'intercom', 'msg_id');
    }
    return true;
}

