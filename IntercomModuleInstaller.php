<?php
/**
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

use DoctrineHelper;
//use EventUtil;
use System;
use ZLanguage;
use Zikula\IntercomModule\Entity\MessageEntity;
use Zikula\IntercomModule\Util\Settings;

class IntercomModuleInstaller extends \Zikula_AbstractInstaller
{

    const MODULENAME = 'ZikulaIntercomModule';    
    
    /**
     * get the default module var values
     *
     * @return array
     */
    public static function getDefaultVars()
    {
     return Settings::getDefault();
    }   
    
    public function install()
    {
        try {
            DoctrineHelper::createSchema($this->entityManager, array('Zikula\IntercomModule\Entity\MessageEntity'));
        } catch (\Exception $e) {
            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());
            return false;
        }
        $this->setVars(self::getDefaultVars());

        return true;
    }

    public function upgrade($oldversion)
    {
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
    
    /**
     * upgrade to 4.0.0
     */
    private function upgrade_to_3_0_0()
    {
        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT * FROM intercom';
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage() . $this->__('Intercom table not found'));
            return false;
        }
        // remove the legacy hooks
        //$sql = "DELETE FROM hooks WHERE tmodule='InterCom' OR smodule='InterCom'";
        //$stmt = $connection->prepare($sql);
        //$stmt->execute();
        
        //clean user fields
        //sender
        $sql = 'ALTER TABLE intercom MODIFY from_userid INT DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET from_userid = NULL WHERE from_userid = 0';
        $stmt = $connection->prepare($sql);
        $stmt->execute();        
        //recipient
        $sql = 'ALTER TABLE intercom MODIFY to_userid INT DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET to_userid = NULL WHERE to_userid = 0';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        
        //clean date fields default date for upgrade is 1999-01-01 12:12:21
        $mark_time = '1999-01-01 12:12:21';
        //msg_time
        $sql = 'ALTER TABLE intercom MODIFY msg_time DATETIME NOT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET msg_time = '.$connection->quote($mark_time).' WHERE msg_time = 0';
        $stmt = $connection->prepare($sql);
        $stmt->execute();        
        //msg_read
        $sql = 'ALTER TABLE intercom MODIFY msg_read VARCHAR(30) DEFAULT NULL';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql = 'UPDATE intercom SET msg_read = '.$connection->quote($mark_time).' WHERE msg_read = 1';
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
        $sql = 'UPDATE intercom SET msg_replied = '.$connection->quote($mark_time).' WHERE msg_replied = 1';
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
        $sql = 'UPDATE intercom SET msg_popup = '.$connection->quote($mark_time).' WHERE msg_popup = 1';
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
        
        
        if (!$this->upgrade_to_3_0_0_renameModuleVars()) {
            return false;
        }        
        if (!$this->upgrade_to_3_0_0_renameColumns()) {
            return false;
        }
        // update all the tables to 3.0.0
        try {
            DoctrineHelper::updateSchema($this->entityManager, array('Zikula\IntercomModule\Entity\MessageEntity'));
            sleep(1);
        } catch (Exception $e) {
            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());
            return false;
        }    
        return true;
    }
    
    /**
     * rename some table columns
     * This must be done before updateSchema takes place
     */
    private function upgrade_to_3_0_0_renameColumns()
    {
        $connection = $this->entityManager->getConnection();
        $sqls = array();
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
        $sqls[] = 'ALTER TABLE intercom CHANGE msg_stored stored TINYINT(1) DEFAULT 0';        
        foreach ($sqls as $sql) {
            $stmt = $connection->prepare($sql);
            try {
                $stmt->execute();
            } catch (Exception $e) {
                $this->request->getSession()->getFlashBag()->add('error', $e);
                return false;
            }
        }
        return true;
    }
    
    /**
     * rename some table columns
     * This must be done before updateSchema takes place
     */
    private function upgrade_to_3_0_0_renameModuleVars()
    {
        $connection = $this->entityManager->getConnection();
        // sql UPDATE `module_vars` SET `modname`='ZikulaIntercomModule' WHERE `modname`='InterCom'
        $sql = 'UPDATE module_vars SET modname = ZikulaIntercomModule WHERE modname = InterCom';
        $stmt = $connection->prepare($sql);
        $stmt->execute();   
        $old_vars = $this->getVars();
        $this->delVars();        
        $vars = Settings::getDefault();
        $key = array();
        if(is_array($old_vars)){
        foreach($vars as $var_name => $var){
        $old_var_key[$varname] = 'messages_'.$var_name;    
        $this->setVar($var_name, $old_vars[$old_var_key[$varname]]);
        }    
        }
        return true;
    } 
    
    public function uninstall()
    {
        try {
            DoctrineHelper::dropSchema($this->entityManager, array('Zikula\IntercomModule\Entity\MessageEntity'));
        } catch (\PDOException $e) {
            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());
            return false;
        }
        // Delete any module variables
        $this->delVars();
        return true;
    }
}
