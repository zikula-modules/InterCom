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
 * message regarding copyright.
 */

namespace Zikula\IntercomModule\Util;

use ModUtil;
use ServiceUtil;
use SecurityUtil;
use DataUtil;
use UserUtil;
use Zikula\IntercomModule\Util\Validator;


class Messages {

  private $name;
  public  $entityManager;
  private $messages;

    /**
     * construct
     */
    public function __construct()
    {
        $this->name = 'ZikulaIntercomModule';
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
                
    }
    
    /**
     *  load messages
     */
    public function load($args)
    {       
        $this->messages = $this->entityManager
                    ->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
                    ->getAll($args);
    }    
    
    /**
     *  get messages
     */
    public function getmessages()
    {       
        return $this->messages;
    } 
    
    /**
     *  get messages
     */
    public function getmessages_array()
    {       
        $messages_array = array();
        foreach ($this->messages as $key => $message) {
            $messages_array[$key] = $message;    
        }        
        return $messages_array;
    }
    
    /**
     *  get messages count
     */
    public function getmessages_count()
    {       
        return $this->messages->count();
    }
    
    /**
     *  get user messages counts
     */
    public function getmessages_counts()
    {     
        $uid = UserUtil::getVar('uid');
        $total = array();
        
        $inbox = $this->entityManager
                    ->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
                    ->getAll(array('deleted' => 'byrecipient', 'recipient' => $uid));
        $total['inbox']['count'] = $inbox->count();        
        $total['inbox']['limit'] = 50;
        
        $outbox = $this->entityManager
                    ->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
                    ->getAll(array('deleted' => 'bysender', 'sender' => $uid));       
        $total['outbox']['count'] = $outbox->count();        
        $total['outbox']['limit'] = 50;   
        
        $stored = $this->entityManager
                    ->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
                    ->getAll(array('stored' => 'all', 'recipient' => $uid, 'sender' => $uid));
        
        $total['archive']['count'] = $stored->count();
        $total['archive']['limit'] = 50;     
        
        return $total;
    }    
    
}