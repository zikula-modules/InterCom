<?php
/**
 * $Id: Installer.php -1   $
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

class InterCom_Controller_Interactiveinstaller extends Zikula_Controller_AbstractInteractiveInstaller
{
    /**
     * interactive installation procedure
     * This function starts the interactive module installation procedure. We can have
     * as many steps here as necessary and go forwards or backwards as needed.
     *
     * This function may exist in the pninit file for a module
     *
     * @author Frank Schummertz
     * @return view output
     */
    public function install()
    {
        // We start the interactive installation process now.
        // This function is called automatically if present.
        // In this case we simply show a welcome screen.

        // Check permissions
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN));


        return $this->view->fetch('install/interactive.tpl');
    }

    /**
     * step 2 of the interactive installation procedure
     *
     *   - create table and ask for configuration details -
     *
     * @author Frank Schummertz
     * @return view output
     */
    public function step2()
    {
        // This is part two of the interactive installation procedure. We will ask the user
        // for some basic data now. After collecting the data, we store them session vars.

        // Check permissions
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN));

        // check if table exist.
        // this might happen if InterCom has been removed before *without* removing the tables
        if (!DBUtil::createTable('intercom')) {
            return false;
        }

        // Force api load
        ModUtil::loadApi('InterCom', 'admin', true);
        // Set up initial values all module variables
        // Chasm: we set default values in this step so that we are able use the
        // Chasm: update process written for the admin section in the next init step.
        ModUtil::apiFunc('InterCom', 'admin', 'default_config');

//      TODO: Fix hooks for Zikula 1.3
//        if (!ModUtil::registerHook('item', 'create', 'API', 'InterCom', 'user', 'createhook')) {
//            return LogUtil::registerError($this->__('Error! Could not create the creation hook.'));
//        }
        // enable the create hook for the Users module
//        ModUtil::apiFunc('Modules', 'admin', 'enablehooks', array('callermodname' => 'Users', 'hookmodname' => 'InterCom'));

        // check if old Messages module table is available, if yes, go to step 2 and offer to import
        // them, if not, continue to the final step
        $oldmessagestable = $pntable['priv_msgs'];
        $sql = "SHOW TABLES LIKE '$oldmessagestable'";
        $res = DBUtil::executeSQL($sql);
        if($res->EOF == true) {
            // table does not exist
            return $this->view->fetch('install/step_final.tpl');
        }

        // prepare the output
        return $this->view->fetch('install/step2.tpl');
    }

    /**
     * step_final - the last step
     * We just say "Thank you" and continue
     *
     * @author Frank Schummertz
     * @return view output
     */
    public function step3()
    {
        // Check permissions
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN));

        $import = FormUtil::getPassedValue('import', 0, 'GETPOST');
        if($import == 1) {
            // Force api load
            ModUtil::loadApi('InterCom', 'init', true);
            ModUtil::apiFunc('InterCom', 'init', 'import_messages');
        }

        $this->view->setCaching(false);
        return $this->view->fetch('install/final.tpl');
    }

    /**
     * interactive delete
     *
     * Get a confirmation from the user that the module should really be removed
     *
     * @author Frank Schummertz
     * @return view output
     */
    public function uninstall()
    {
        // Check permissions
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN));
        return $this->view->fetch('install/delete.tpl');
    }

    /**
     * final step of the interactive delete procedure
     *
     * We just say "Thank you" and continue
     *
     * @author Frank Schummertz
     * @return view output
     */
    public function deletefinal()
    {
        // Check permissions
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN));

        // Delete any module variables
        $this->delVars();

//      TODO: Fix hooks for Zikula 1.3
//        if (!ModUtil::unregisterHook('item', 'create', 'API', 'InterCom', 'user', 'createhook')) {
//            LogUtil::registerError($this->__('Error! Could not unregister hook'));
//        }

        $removetables = FormUtil::getPassedValue('removetables', 0, 'GETPOST');
        if($removetables == 1) {
            if (!DBUtil::dropTable('intercom')) {
                LogUtil::registerError($this->__('Error! Could not drop table.'));
            }
            if (!DBUtil::dropTable('intercom_userprefs')) {
                LogUtil::registerError($this->__('Error! Could not drop table.'));
            }
        }

        return $this->view->fetch('install/delete_final.tpl');
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
        if (!DBUtil::createTable('intercom')) {
            return LogUtil::registerError($this->__('Error! Could not create table.'));
        }
        // Force api load
        ModUtil::loadApi('InterCom', 'admin', true);
        // Set up initial values all module variables
        ModUtil::apiFunc('InterCom', 'admin', 'default_config');

//        TODO: Fix hooks for Zikula 1.3
//        if (ModUtil::registerHook('item', 'create', 'API', 'InterCom', 'user', 'createhook')) {
//            // enable the create hook for the Users module
//            ModUtil::apiFunc('Modules', 'admin', 'enablehooks', array('callermodname' => 'Users', 'hookmodname'   => 'InterCom'));
//        }

        return true;
    }
}