<?php
/**
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @subpackage Util
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */
namespace Zikula\IntercomModule\Util;

use DataUtil;
use ServiceUtil;

use Zikula\IntercomModule\Util\Settings;

class Tools {
    
  private $name;
  public  $entityManager;

    /**
     * construct
     */
    public function __construct()
    {
        $this->name = 'ZikulaIntercomModule';
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
        
    }
    /**
     */
    public function checkIntegrityUsers()
    {     
        $connection = $this->entityManager->getConnection();
        //Recipient
        $sql = 'SELECT COUNT(m.id) failcount FROM intercom m
                LEFT JOIN users u
                ON m.recipient = u.uid
        WHERE u.uid is null AND m.recipient is not null';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();        
        $count['recipient'] = $result[0]['failcount'];
        //Sender
        $sql = 'SELECT COUNT(m.id) failcount FROM intercom m
                LEFT JOIN users u
                ON m.sender = u.uid
        WHERE u.uid is null AND m.sender is not null';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();        
        $count['sender'] = $result[0]['failcount'];       
        return $count;
    }     
    /**
     */
    public function fixIntegrityUsers()
    {
        $connection = $this->entityManager->getConnection();
        //Recipient
        $sql = 'UPDATE intercom m
                LEFT JOIN users u
                ON m.recipient = u.uid
                SET m.recipient = null
        WHERE u.uid is null AND m.recipient is not null';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        //Sender
        $sql = 'UPDATE intercom m
                LEFT JOIN users u
                ON m.sender = u.uid
                SET m.sender = null
        WHERE u.uid is null AND m.sender is not null';
        $stmt = $connection->prepare($sql);
        $stmt->execute();        
        return true;
    }    
    /**
     */
    public function checkIntegrityOrphaned()
    {     
        $connection = $this->entityManager->getConnection();
        //Recipient
        $sql = 'SELECT COUNT(m.id) failcount FROM intercom m
                WHERE m.sender is null AND m.recipient is null';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();        
        $count = $result[0]['failcount'];       
        return $count;
    }     
    /**
     */
    public function fixIntegrityOrphaned()
    {      
        return true;
    }    
    /**
     */
    public function checkIntegrityInbox()
    {            
        return 0;
    }     
    /**
     */
    public function fixIntegrityInbox()
    {       
        return true;
    }    
    /**
     */
    public function checkIntegrityOutbox()
    {            
        return 0;
    }     
    /**
     */
    public function fixIntegrityOutbox()
    {        
        return true;
    }    
    /**
     */
    public function checkIntegrityArchive()
    {            
        return 0;
    }     
    /**
     */
    public function fixIntegrityArchive()
    {      
        return true;
    } 
    /**
     */
    public function resetSettings()
    {
        $settings = new Settings();
        return $settings->resetSettings();
    }  
    /**
     */
    public function deleteInboxes()
    {
        return true;
    }   
    /**
     */
    public function deleteOutboxes()
    {
        return true;
    }
    /**
     */
    public function deleteArchive()
    {
        return true;
    }  
    /**
     */
    public function deleteAll()
    {
        return true;
    }
  
}
