<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

class InterCom_Installer extends Zikula_Installer
{
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
        switch ($oldversion) {
            case '2.1':
            /* in a new installation of InterCom 2.1 the createhook has not been added, we will do this now
             if necessary */
                if (ModUtil::registerHook('item',
                'create',
                'API',
                'InterCom',
                'user',
                'createhook')) {
                    // enable the create hook for the Users module
                    ModUtil::apiFunc('Modules', 'admin', 'enablehooks',
                            array('callermodname' => 'Users',
                            'hookmodname' => 'InterCom'));
                }
            case '2.2':
                $this->setVar('messages_force_emailnotification', true);
            case '2.2.0':
        }

        return true;
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
    function install()
    {
        if (!DBUtil::createTable('intercom')) {
            return LogUtil::registerError($this->__('Error! Could not create table.'));
        }
        // Force api load
        ModUtil::loadApi('InterCom', 'admin', true);
        // Set up initial values all module variables
        ModUtil::apiFunc('InterCom', 'admin', 'default_config');

        if (ModUtil::registerHook('item', 'create', 'API', 'InterCom', 'user', 'createhook')) {
            // enable the create hook for the Users module
            ModUtil::apiFunc('Modules', 'admin', 'enablehooks', array('callermodname' => 'Users', 'hookmodname' => 'InterCom'));
        }

        return true;
    }

}