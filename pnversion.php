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
$dom = ZLanguage::getModuleDomain('InterCom');
// The following information is used by the Modules module
// for display and upgrade purposes
$modversion['name']           = 'InterCom';
$modversion['oldnames']       = array('pnMessages');
// the version string must not exceed 10 characters!
$modversion['version']        = '2.3';
$modversion['description']    = __('Provides a private messaging system with an individual mailbox for each user, incorporating integration with the user account panel and with various other modules and blocks.', $dom);
$modversion['displayname']    = __('InterCom private messaging', $dom);
//! module url must be different from displayname, even if just in casing
$modversion['url']            = __('intercom', $dom);

// The following in formation is used by the credits module
// to display the correct credits
$modversion['changelog']      = 'pndocs/changelog.txt';
$modversion['credits']        = 'pndocs/credits.txt';
$modversion['help']           = 'pndocs/install.txt';
$modversion['license']        = 'pndocs/license.txt';
$modversion['official']       = 0;
$modversion['author']         = 'InterCom Development Team';
$modversion['contact']        = 'http://code.zikula.org/intercom/';

// The following information tells the Zikula core that this
// module has an admin option.
$modversion['admin']          = 1;
// The next information tells the Zikula core that this
// module offers private messaging.
$modversion['message']        = 1;

// module dependencies
$modversion['dependencies'] = array(
    array( 'modname'    => 'ContactList',
           'minversion' => '1.0', 'maxversion' => '',
           'status'     => PNMODULE_DEPENDENCY_RECOMMENDED),
    array( 'modname'    => 'bbcode',
           'minversion' => '2.0', 'maxversion' => '',
           'status'     => PNMODULE_DEPENDENCY_RECOMMENDED),
    array( 'modname'    => 'bbsmile',
           'minversion' => '2.1', 'maxversion' => '',
           'status'     => PNMODULE_DEPENDENCY_RECOMMENDED)
);

// This one adds the info to the DB, so that users can click on the
// headings in the permission module
$modversion['securityschema'] = array('InterCom::' => '::');
