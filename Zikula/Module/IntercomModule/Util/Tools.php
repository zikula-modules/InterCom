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
namespace Zikula\Module\IntercomModule\Util;

use DataUtil;
use ServiceUtil;

use Zikula\Module\IntercomModule\Util\Settings;

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
    public function deleteAll()
    {
        return true;
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
    public function deleteStored()
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
    public function optimize_db()
    {
        return true;
    }    
    
    /**
     */
    public function checkIntegrity()
    {
        
        $connection = $this->entityManager->getConnection();
        
        $sql = 'UPDATE intercom m
                LEFT JOIN users u
                ON m.recipient = u.uid
                SET m.recipient = null
        WHERE u.uid is null AND m.recipient is not null';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        
        $sql = 'UPDATE intercom m
                LEFT JOIN users u
                ON m.sender = u.uid
                SET m.sender = null
        WHERE u.uid is null AND m.sender is not null';
        $stmt = $connection->prepare($sql);
        $stmt->execute();        

        return true;
    }   
  
}
