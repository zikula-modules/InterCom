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
 * Populate pntables array for InterCom module
 *
 * @author       Alexander Bergmann
 * @version
 * @return       array       The table information.
 */
function InterCom_pntables()
{
    // Initialise table array
    $pntable = array();
    // Set the table name
    $pntable['intercom'] = DBUtil::getLimitedTablename('intercom');
    // Set the column names.
    $pntable['intercom_column'] = array ('msg_id'          => 'pn_msg_id',
                                         'from_userid'     => 'pn_from_userid',
                                         'to_userid'       => 'pn_to_userid',
                                         'msg_subject'     => 'pn_msg_subject',
                                         'msg_time'        => 'pn_msg_time',
                                         'msg_text'        => 'pn_msg_text',
                                         'msg_read'        => 'pn_msg_read',
                                         'msg_replied'     => 'pn_msg_replied',
                                         'msg_popup'       => 'pn_msg_popup',
                                         'msg_inbox'       => 'pn_msg_inbox',
                                         'msg_outbox'      => 'pn_msg_outbox',
                                         'msg_stored'      => 'pn_msg_stored');

    $pntable['intercom_column_def'] = array ('msg_id'          => 'I AUTO PRIMARY',
                                             'from_userid'     => 'I NOTNULL DEFAULT 0',
                                             'to_userid'       => 'I NOTNULL DEFAULT 0',
                                             'msg_subject'     => 'C(100) NOTNULL DEFAULT \'\'',
                                             'msg_time'        => 'C(20) NOTNULL DEFAULT \'\'',
                                             'msg_text'        => 'X2 DEFAULT \'\'',
                                             'msg_read'        => 'L NOTNULL DEFAULT 0',
                                             'msg_replied'     => 'L NOTNULL DEFAULT 0',
                                             'msg_popup'       => 'L NOTNULL DEFAULT 0',
                                             'msg_inbox'       => 'L NOTNULL DEFAULT 0',
                                             'msg_outbox'      => 'L NOTNULL DEFAULT 0',
                                             'msg_stored'      => 'L NOTNULL DEFAULT 0');

    $pntable['intercom_column_idx'] = array ('FromUserID'  => 'from_userid',
                                             'ToUserID'    => 'to_userid');



    // to do: remove this table in version 3.0 or 4.0 latest
    // Set up the InterCom table.
    $pntable['intercom_userprefs'] = DBUtil::getLimitedTablename('intercom_userprefs');
    // Set the column names.
    $pntable['intercom_userprefs_column'] = array ('user_id'            => 'pn_user_id',
                                                   'email_notification' => 'pn_email_notification',
                                                   'autoreply'          => 'pn_autoreply',
                                                   'autoreply_text'     => 'pn_autoreply_text');
    $pntable['intercom_userprefs_column_def'] = array ('user_id'            => 'I PRIMARY DEFAULT 0',
                                                       'email_notification' => 'L NOTNULL DEFAULT 0',
                                                       'autoreply'          => 'L NOTNULL DEFAULT 0',
                                                       'autoreply_text'     => 'X2 DEFAULT \'\'');

    // fake the old priv_msgs tsble structure for upgrade form old core Messages module
    // this will be removed in 3.0
    $pntable['priv_msgs'] = DBUtil::getLimitedTablename('priv_msgs');
    $pntable['priv_msgs_column'] = array ('msg_id'      => 'pn_msg_id',
                                          'msg_image'   => 'pn_msg_image',
                                          'subject'     => 'pn_subject',
                                          'from_userid' => 'pn_from_userid',
                                          'to_userid'   => 'pn_to_userid',
                                          'msg_time'    => 'pn_msg_time',
                                          'msg_text'    => 'pn_msg_text',
                                          'read_msg'    => 'pn_read_msg');
    $pntable['priv_msgs_column_def'] = array ('msg_id'      => 'I AUTO PRIMARY',
                                              'msg_image'   => 'C(100) NOTNULL DEFAULT\'\'',
                                              'subject'     => 'C(100) NOTNULL DEFAULT\'\'',
                                              'from_userid' => 'I NOTNULL DEFAULT 0',
                                              'to_userid'   => 'I NOTNULL DEFAULT 0',
                                              'msg_time'    => 'C(20) NOTNULL DEFAULT\'\'',
                                              'msg_text'    => 'X2 NOTNULL DEFAULT\'\'',
                                              'read_msg'    => 'I NOTNULL');

    // Return the table information
    return $pntable;
}
