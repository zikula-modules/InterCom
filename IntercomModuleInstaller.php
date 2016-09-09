<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

/**
 * Intercom module installer.
 */

namespace Zikula\IntercomModule;

use Zikula\Core\AbstractExtensionInstaller;

class IntercomModuleInstaller extends AbstractExtensionInstaller {

    public function install() {
        try {
            $this->schemaTool->create(['Zikula\IntercomModule\Entity\MessageEntity']);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return false;
        }
        $this->setVars(self::getDefaultVars());
        return true;
    }

    public function upgrade($oldversion) {
        switch ($oldversion) {
            case '2.3.0':
                //ini_set('memory_limit', '194M');
                //ini_set('max_execution_time', 86400);
                if (!$this->upgrade_to_3_0_0()) {
                    return false;
                }
                break;
        }
        return true;
    }

    public function uninstall() {
        try {
            $this->schemaTool->drop(['Zikula\IntercomModule\Entity\MessageEntity']);
        } catch (\PDOException $e) {
            $this->addFlash('error', $e->getMessage());
            return false;
        }
        // Delete any module variables
        $this->delVars();
        return true;
    }

    /**
     * upgrade to 4.0.0
     */
    private function upgrade_to_3_0_0() {
        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT * FROM intercom';
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage() . $this->__('Intercom table not found'));
            return false;
        }
        // remove the legacy hooks
        //$sql = "DELETE FROM hooks WHERE tmodule='InterCom' OR smodule='InterCom'";
        //$stmt = $connection->prepare($sql);
        //$stmt->execute();
        //we need to add this colum now so we can move admin messages to it
        $sql = "ALTER TABLE intercom ADD mtype VARCHAR(30) NOT NULL DEFAULT 'normal' AFTER to_userid";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = "UPDATE intercom SET mtype = 'admin' WHERE from_userid = 0";
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        //clean user fields
        //sender
        $sql = 'ALTER TABLE intercom MODIFY from_userid INT DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET from_userid = 2 WHERE from_userid = 0';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        //recipient
        $sql = 'ALTER TABLE intercom MODIFY to_userid INT DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET to_userid = 2 WHERE to_userid = 0';
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        //clean date fields default date for upgrade is 1999-01-01 12:12:21
        $mark_time = '1999-01-01 12:12:21';
        //msg_time
        $sql = 'ALTER TABLE intercom MODIFY msg_time DATETIME NOT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET msg_time = ' . $connection->quote($mark_time) . ' WHERE msg_time = 0';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        //msg_read
        $sql = 'ALTER TABLE intercom MODIFY msg_read VARCHAR(30) DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET msg_read = ' . $connection->quote($mark_time) . ' WHERE msg_read = 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET msg_read = NULL WHERE msg_read = 0';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'ALTER TABLE intercom MODIFY msg_read DATETIME DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        //msg_replied
        $sql = 'ALTER TABLE intercom MODIFY msg_replied VARCHAR(30) DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET msg_replied = ' . $connection->quote($mark_time) . ' WHERE msg_replied = 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET msg_replied = NULL WHERE msg_replied = 0';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'ALTER TABLE intercom MODIFY msg_replied DATETIME DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        //msg_popup
        $sql = 'ALTER TABLE intercom MODIFY msg_popup VARCHAR(30) DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET msg_popup = ' . $connection->quote($mark_time) . ' WHERE msg_popup = 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET msg_popup = NULL WHERE msg_popup = 0';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'ALTER TABLE intercom MODIFY msg_popup DATETIME DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        //inbox invert value
        $sql = 'UPDATE intercom SET msg_inbox = NOT msg_inbox';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        //inbox invert value
        $sql = 'UPDATE intercom SET msg_outbox = NOT msg_outbox';
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        if (!$this->upgrade_to_3_0_0_renameColumns()) {
            $this->addFlash('error', 'Renaming columns filed');
            return false;
        }

        if (!$this->upgrade_to_3_0_0_renameModuleVars()) {
            $this->addFlash('error', 'Renaming module vars filed');
            return false;
        }

        // update all the tables to 3.0.0
        try {
            $this->schemaTool->update(['Zikula\IntercomModule\Entity\MessageEntity']);
        } catch (Exception $e) {
            $this->addFlash('error', $e);
            return false;
        }

        return true;
    }

    /**
     * rename some table columns
     * This must be done before updateSchema takes place
     */
    private function upgrade_to_3_0_0_renameColumns() {
        $connection = $this->entityManager->getConnection();
        $sqls = [];
        // a list of column changes
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_id id INT(11) NOT NULL';
        $sqls[] = 'ALTER TABLE intercom CHANGE from_userid sender INT(11) DEFAULT NULL';
        $sqls[] = 'ALTER TABLE intercom CHANGE to_userid recipient INT(11) DEFAULT NULL';
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_subject subject VARCHAR(100) NOT NULL';
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_time send DATETIME NOT NULL';
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_text text TEXT NOT NULL';
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_read seen DATETIME DEFAULT NULL';
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_replied replied DATETIME DEFAULT NULL';
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_popup notified DATETIME DEFAULT NULL';
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_inbox deletedbysender TINYINT(1) DEFAULT 0';
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_outbox deletedbyrecipient TINYINT(1) DEFAULT 0';
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_stored storedbysender TINYINT(1) DEFAULT 0';
        //new collumns
        $sqls[] = 'ALTER TABLE intercom ADD storedbyrecipient TINYINT(1) DEFAULT 0 AFTER storedbysender';
        //copy stored data
        $sqls[] = 'UPDATE intercom SET storedbyrecipient = storedbysender';
        $sqls[] = 'ALTER TABLE intercom ADD conversationid INT(11) DEFAULT NULL AFTER storedbyrecipient';

        foreach ($sqls as $sql) {
            $stmt = $connection->prepare($sql);
            try {
                $stmt->execute();
            } catch (Exception $e) {
                $this->addFlash('error', $e);
                return false;
            }
        }
        return true;
    }

    /**
     * rename some table columns
     * This must be done before updateSchema takes place
     */
    private function upgrade_to_3_0_0_renameModuleVars() {
        /*
         * old 
          $mixed = array();
          // clear old modvars
          // use manual method because getVars() is not available during system upgrade
          $modVarEntities = $this->entityManager->getRepository('Zikula\Core\Doctrine\Entity\ExtensionVarEntity')->findBy(array('modname' => 'InterCom'));
          $old_vars = array();
          foreach ($modVarEntities as $ovar) {
          $old_vars[$ovar['name']] = $ovar['value'];
          }
          $this->delVars();
          $vars =  self::getDefaultVars();
          foreach($vars as $var_name => $var){
          $old_var_key[$var_name] = 'messages_'.$var_name;
          $mixed[$var_name]  = array_key_exists($old_var_key[$var_name], $old_vars) ? $old_vars[$old_var_key[$var_name]] : $var;
          }
          $this->setVars($mixed);
         * 
         */
        $this->setVars(self::getDefaultVars());
        return true;
    }
    
    /**
     * get the default module var values
     *
     * @return array
     */
    public static function getDefaultVars() {
        return [
            //General
            'active'=> true,
            'maintain'=> $this->__('Sorry! The private messaging system is currently off-line for maintenance. Please check again later, or contact the site administrator.'),
            'disable_ajax'=> false,
            'allowhtml'=> false,
            'allowsmilies'=> false,            
            //Limitations
            'limitarchive'=> '50',
            'limitoutbox'=> '50',
            'limitinbox'=> '50',
            'perpage'=> '25',
            //Email
            'allow_emailnotification'=> true,            
            'force_emailnotification'=> false,
            'mailsubject'=> $this->__('You have a new private message'),
            'fromname'=> '',
            'from_email'=> '',            
            'mailsender'=> '',
            //Autoresponder
            'allow_autoreply' => false,
            //Users prompt
            'userprompt'=> $this->__('Welcome to the private messaging system'),
            'userprompt_display'=> false,
            //Welcome
            'welcomemessage_send' => false,
            'welcomemessagesender'=> 'admin',
            'welcomemessagesubject'=> $this->__('Welcome to the private messaging system on %sitename%'),  // quotes are important here!!
            'welcomemessage'=> $this->__("Hello!' .'Welcome to the private messaging system on %sitename%. Please remember that use of the private messaging system is subject to the site\'s terms of use and privacy statement. If you have any questions or encounter any problems, please contact the site administrator. Site admin"), // quotes are important here!!!
            'savewelcomemessage'=> false,
            'intlwelcomemessage'=> '',            
            //Protection
            'protection_on'=> true,
            'protection_time'=> '15',
            'protection_amount'=> '15',
            'protection_mail'=> false,
            //layout
            'layout' => 'classic',
            //Mode
            'mode'=> 0,
            //System notifications
            'system_notifications_enabled' => false,
            //Support messages
            'support_messages_enabled' => false
            ];    
    }

}
