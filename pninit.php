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
 * interactive installation procedure
 * This function starts the interactive module installation procedure. We can have
 * as many steps here as necessary and go forwards or backwards as needed.
 *
 * This function may exist in the pninit file for a module
 *
 * @author Frank Schummertz
 * @return pnRender output
 */
function InterCom_init_interactiveinit()
{
    // We start the interactive installation process now.
    // This function is called automatically if present.
    // In this case we simply show a welcome screen.

    // Check permissions
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // prepare the output
    $pnr = & pnRender::getInstance('InterCom', false);
    return $pnr->fetch('intercom_init_interactive.htm');
}

/**
 * step 2 of the interactive installation procedure
 *
 *   - create table and ask for configuration details -
 *
 * @author Frank Schummertz
 * @return pnRender output
 */
function InterCom_init_step2()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // This is part two of the interactive installation procedure. We will ask the user
    // for some basic data now. After collecting the data, we store them session vars.

    // Check permissions
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // check if table exist.
    // this might happen if InterCom has been removed before *without* removing the tables
    $pntable = pnDBGetTables();
    $messagestable = $pntable['intercom'];
    $sql = "SHOW TABLES LIKE '$messagestable'";
    $res = DBUtil::executeSQL($sql);
    if($res->EOF == true) {
        // table does not exist
        if (!DBUtil::createTable('intercom')) {
            return LogUtil::registerError(__('Error! Could not create table.', $dom));
        }
    }

    // Force api load
    pnModAPILoad('InterCom', 'admin', true);
    // Set up initial values all module variables
    // Chasm: we set default values in this step so that we are able use the
    // Chasm: update process written for the admin section in the next init step.
    pnModAPIFunc('InterCom', 'admin', 'default_config');

    if (!pnModRegisterHook('item',
                           'create',
                           'API',
                           'InterCom',
                           'user',
                           'createhook')) {
        return LogUtil::registerError(__('Error! Could not create the creation hook.', $dom));
    }
    // enable the create hook for the Users module
    pnModAPIFunc('Modules', 'admin', 'enablehooks',
                 array('callermodname' => 'Users',
                       'hookmodname' => 'InterCom'));

    $pnr = & pnRender::getInstance('InterCom', false);

    // check if old Messages module table is available, if yes, go to step 2 and offer to import
    // them, if not, continue to the final step
    $oldmessagestable = $pntable['priv_msgs'];
    $sql = "SHOW TABLES LIKE '$oldmessagestable'";
    $res = DBUtil::executeSQL($sql);
    if($res->EOF == true) {
        // table does not exist
        $pnr->assign('authid', SecurityUtil::generateAuthKey('Modules'));
        return $pnr->fetch('intercom_init_step_final.htm');
    }

    // prepare the output
    $pnr->assign('authid', SecurityUtil::generateAuthKey('InterCom'));
    return $pnr->fetch('intercom_init_step2.htm');
}

/**
 * step_final - the last step
 * We just say "Thank you" and continue
 *
 * @author Frank Schummertz
 * @return pnRender output
 */
function InterCom_init_step3()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Check permissions
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $import = FormUtil::getPassedValue('import', 0, 'GETPOST');
    if($import == 1) {
    // Force api load
        pnModAPILoad('InterCom', 'init', true);
        pnModAPIFunc('InterCom', 'init', 'import_messages');
    }

    $pnr = & pnRender::getInstance('InterCom', false);
    $pnr->assign('authid', SecurityUtil::generateAuthKey('Modules'));
    return $pnr->fetch('intercom_init_step_final.htm');
}

/**
 * interactive delete
 *
 * Get a confirmation from the user that the module should really be removed
 *
 * @author Frank Schummertz
 * @return pnRender output
 */
function InterCom_init_interactivedelete()
{
    // Check permissions
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $pnr = & pnRender::getInstance('InterCom', false);
    $pnr->assign('authid', SecurityUtil::generateAuthKey('InterCom'));
    return $pnr->fetch('intercom_init_delete.htm');
}

/**
 * final step of the interactive delete procedure
 *
 * We just say "Thank you" and continue
 *
 * @author Frank Schummertz
 * @return pnRender output
 */
function InterCom_init_deletefinal()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    // Check permissions
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // Delete any module variables
    pnModDelVar('InterCom');

    if (!pnModUnregisterHook('item',
                           'create',
                           'API',
                           'InterCom',
                           'user',
                           'createhook')) {
        LogUtil::registerError(__('Error! Could not unregister hook', $dom));
    }

    $removetables = FormUtil::getPassedValue('removetables', 0, 'GETPOST');
    if($removetables == 1) {
        if (!DBUtil::dropTable('intercom')) {
            LogUtil::registerError(__('Error! Could not drop table.', $dom));
        }
        if (!DBUtil::dropTable('intercom_userprefs')) {
            LogUtil::registerError(__('Error! Could not drop table.', $dom));
        }
    }

    $pnr = & pnRender::getInstance('InterCom', false);
    $pnr->assign('authid', SecurityUtil::generateAuthKey('Modules'));
    return $pnr->fetch('intercom_init_delete_final.htm');
}

/**
 * initialise the InterCom module
 *
 * This function is installing InterCom with some default values. It will be called during a
 * basic Zikula installation when all modules are selected to be installed (non-interactive
 * mode)
 *
 * @author Alexander Bergmann
 * @version
 * @return bool true on success, false otherwise
 */
function InterCom_init()
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    if (defined('_PNINSTALLVER')) {
        if (!DBUtil::createTable('intercom')) {
            return LogUtil::registerError(__('Error! Could not create table.', $dom));
        }
        // Force api load
        pnModAPILoad('InterCom', 'admin', true);
        // Set up initial values all module variables
        pnModAPIFunc('InterCom', 'admin', 'default_config');

        if (pnModRegisterHook('item',
                               'create',
                               'API',
                               'InterCom',
                               'user',
                               'createhook')) {
            // enable the create hook for the Users module
            pnModAPIFunc('Modules', 'admin', 'enablehooks',
                         array('callermodname' => 'Users',
                               'hookmodname' => 'InterCom'));
        }

    }
    return true;
}

/**
 * upgrade the InterCom module from an old version
 *
 * This function is intentionally left empty
 *
 * @author Alexander Bergmann
 * @version
 * @return bool true on success, false otherwise
 */
function InterCom_upgrade($oldversion)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    switch ($oldversion) {
        case '1.5':
            // register the create hook
            if (!pnModRegisterHook('item',
                                   'create',
                                   'API',
                                   'InterCom',
                                   'user',
                                   'createhook')) {
                return LogUtil::registerError(__('Error! Could not create the creation hook.', $dom));
            }
            // enable the create hook for the Users module
            pnModAPIFunc('Modules', 'admin', 'enablehooks',
                         array('callermodname' => 'Users',
                               'hookmodname' => 'InterCom'));

            // convert 1/0 settings to true/false
            pnModSetVar('InterCom', 'messages_allowhtml', (pnModGetVar('InterCom', 'messages_allowhtml') == '1') ? true : false);
			pnModSetVar('InterCom', 'messages_allowsmilies', (pnModGetVar('InterCom', 'messages_allowsmilies') == '1') ? true : false);			
            pnModSetVar('InterCom', 'messages_allow_emailnotification', (pnModGetVar('InterCom', 'messages_allow_emailnotification') == '1') ? true : false);
            pnModSetVar('InterCom', 'messages_allow_autoreply', (pnModGetVar('InterCom', 'messages_allow_autoreply') == '1') ? true : false);
            pnModSetVar('InterCom', 'messages_userprompt_display', (pnModGetVar('InterCom', 'messages_userprompt_display') == '1') ? true : false);
            pnModSetVar('InterCom', 'messages_active', (pnModGetVar('InterCom', 'messages_active') == '1') ? true : false);
            pnModSetVar('InterCom', 'messages_protection_on', (pnModGetVar('InterCom', 'messages_protection_on') == '1') ? true : false);
            pnModSetVar('InterCom', 'messages_protection_mail', (pnModGetVar('InterCom', 'messages_protection_mail') == '1') ? true : false);

            // new mod vars
            pnModSetVar('InterCom', 'messages_welcomemessagesender', 'Admin');
            pnModSetVar('InterCom', 'messages_welcomemessagesubject', '_IC_INTL_WELCOME_MESSAGESUBJECT');  // quotes are important here!!
            pnModSetVar('InterCom', 'messages_welcomemessage', '_IC_INTL_WELCOME_MESSAGE');  // quotes are important here!!
            pnModSetVar('InterCom', 'messages_savewelcomemessage', false);

        case '2.0': // rename to InterCom in 2.1
            // rename tables
            $tables = array('pnmessages'           => 'intercom',
                            'pnmessages_userprefs' => 'intercom_userprefs',);
            $dbconn = DBConnectionStack::getConnection();
            $dict   = NewDataDictionary($dbconn);
            $prefix = pnConfigGetVar('prefix');
            foreach($tables as $oldtable => $newtable) {
                $sqlarray = $dict->RenameTableSQL($prefix.'_'.$oldtable, $prefix.'_'.$newtable);
                $result   = $dict->ExecuteSQLArray($sqlarray);
            }

            // rename mod vars
            $oldvars = pnModGetVar('pnMessages');
            foreach ($oldvars as $varname => $oldvar) {
                pnModSetVar('InterCom', $varname, $oldvar);
            }
            pnModDelVar('pnMessages');

            // rename hook
            $pntables = pnDBGetTables();
            $hookstable  = $pntables['hooks'];
            $hookscolumn = $pntables['hooks_column'];
            $sql = 'UPDATE ' . $hookstable . ' SET ' . $hookscolumn['smodule'] . '=\'InterCom\' WHERE ' . $hookscolumn['smodule'] . '=\'pnMessages\'';
            $res   = DBUtil::executeSQL ($sql);
            if ($res === false) {
                return LogUtil::registerError(__('Error! Could not upgrade hook (smodule)', $dom));
            }

            $sql = 'UPDATE ' . $hookstable . ' SET ' . $hookscolumn['tmodule'] . '=\'InterCom\' WHERE ' . $hookscolumn['tmodule'] . '=\'pnMessages\'';
            $res   = DBUtil::executeSQL ($sql);
            if ($res === false) {
                return LogUtil::registerError(__('Error! Could not upgrade hook (tmodule)', $dom));
            }

            // rename permissions
            $pntable = pnDBGetTables();
            $permcolumn = $pntable['group_perms_column'];
            $where = "WHERE LEFT($permcolumn[component], 10) ='pnMessages'";
            $orderby = "ORDER BY $permcolumn[sequence]";
            $permarray = DBUtil::selectObjectArray('group_perms', $where, $orderby, -1, -1, false);
            $permcount = count($permarray);

            for($cnt=0; $cnt<$permcount; $cnt++) {
                $permarray[$cnt]['component'] = str_replace('pnMessages', 'InterCom', $permarray[$cnt]['component']);
            }
            DBUtil::updateObjectArray($permarray, 'group_perms', 'pid');
        case '2.1':
            /* in a new installation of InterCom 2.1 the createhook has not been added, we will do this now
               if necessary */
            if (pnModRegisterHook('item',
                                   'create',
                                   'API',
                                   'InterCom',
                                   'user',
                                   'createhook')) {
                // enable the create hook for the Users module
                pnModAPIFunc('Modules', 'admin', 'enablehooks',
                             array('callermodname' => 'Users',
                                   'hookmodname' => 'InterCom'));
            }
        case '2.2':
            pnModSetVar('InterCom', 'messages_force_emailnotification', false);
    }

    return true;
}

/**
 * delete the InterCom module
 *
 * This function is intentionally left empty
 *
 * @author Alexander Bergmann
 * @version
 * @return bool true on success, false otherwise
 */
function InterCom_delete()
{
    return true;
}
