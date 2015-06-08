<?php

/**
 * Intercom
 *
 * @copyright (c) 2001-now, Intercom Development Team
 * @link https://github.com/zikula-modules/Intercom
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Intercom
 */

namespace Zikula\IntercomModule\Entity;

use UserUtil;
use Doctrine\ORM\QueryBuilder;

/**
 * Query builder with own filter definitions
 *
 */
class MessagesQueryBuilder extends QueryBuilder {
    
    /**
     * Filter by id
     * @param $id false/userid
     */    
    public function filterId($id) {
        if ($id !== false) {
            return $this
                            ->andWhere('m.id = :id')
                            ->setParameter('id', $id);
        }
    }    

    /**
     * Filter deleted
     * @param $deleted string
     * 
     * all
     * bysender
     * byrecipient 
     */     
    public function filterDeleted($deleted) {
        if ($deleted == false) {
            return $this;
        }
        switch ($deleted){
            case 'all':
            return $this
                             ->andWhere($this->expr()->orx(
                                        $this->expr()->eq('m.deletedbysender', 0),
                                        $this->expr()->eq('m.deletedbyrecipient', 0)));
            case 'bysender':
            return $this
                            ->andWhere('m.deletedbysender = :deletedbysender')
                            ->setParameter('deletedbysender', 0);
            case 'byrecipient':
            return $this
                            ->andWhere('m.deletedbyrecipient = :deletedbyrecipient')
                            ->setParameter('deletedbyrecipient', 0);
        }
    }
    
    /**
     * Filter stored
     * @param $stored string
     * 
     * all
     * bysender
     * byrecipient 
     */     
    public function filterStored($stored) {
        if ($stored == false) {
            return $this;
        }
        switch ($stored){
            case 'all':
            return $this
                             ->andWhere($this->expr()->orx(
                                        $this->expr()->eq('m.storedbysender', 1),
                                        $this->expr()->eq('m.storedbyrecipient', 1)));
            case 'bysender':
            return $this
                            ->andWhere('m.storedbysender = :storedbysender')
                            ->setParameter('storedbysender', 1);
            case 'byrecipient':
            return $this
                            ->andWhere('m.storedbyrecipient = :storedbyrecipient')
                            ->setParameter('storedbyrecipient', 1);
        }
    }
    
    /**
     * Filter mtype
     * @param $mtype false/mtype
     *
     */
    public function filterMtype($mtype) {
        if ($mtype !== false) {
            return $this
            ->andWhere('m.mtype = :mtype')
            ->setParameter('mtype', $mtype);
        }
    }    
    
    /**
     * Filter sender
     * @param $sender false/userid
     * 
     */     
    public function filterSender($sender) {
        if ($sender !== false) {
            return $this
                            ->andWhere('m.sender = :sender')
                            ->setParameter('sender', $sender);
        }
    }
    
    /**
     * Filter recipient
     * @param $recipient false/userid
     * 
     */     
    public function filterRecipient($recipient) {
        if ($recipient !== false) {
            return $this
                            ->andWhere('m.recipient = :recipient')
                            ->setParameter('recipient', $recipient);
        }
    }    
    
    /**
     * Filter subject
     * @param $subject false/string
     * 
     */ 
    public function filterSubject($subject) {
        if ($subject !== false) {
            return $this
                            ->andWhere('m.subject = :subject')
                            ->setParameter('subject', $subject);
        }
    }

    /**
     * Filter send
     * @param $send false/string
     *  
     */     
    public function filterSend($send) {
        if ($send !== false){
            return $this->andWhere($this->expr()->isNull('m.send'));
        }        
    }

    /**
     * Filter text
     * @param $text false/string
     * 
     */     
    public function filterText($text) {
        if ($text !== false) {
            return $this
                            ->andWhere('m.text = :text')
                            ->setParameter('text', $text);
        }
    }

    /**
     * Filter seen
     * @param $seen false/string
     * 
     * seen
     * unseen
     */     
    public function filterSeen($seen) {
        if ($seen !== false) {
        switch ($seen){
            case 'seen':
            return $this->andWhere($this->expr()->isNotNull('m.seen'));
            case 'unseen':
            return $this->andWhere($this->expr()->isNull('m.seen'));           
        }
        }
    }

    /**
     * Filter replied
     * @param $replied string
     *  
     */     
    public function filterReplied($replied) {
        if ($replied !== false){
            return $this->andWhere($this->expr()->isNull('m.replied'));
        }        
    }
    
    /**
     * Filter conversations
     * @param $conversations false/conversationid
     * 
     */     
    public function filterConversations($conversations) {
        if ($conversations !== false){
            return $this->andWhere($this->expr()->isNull('m.conversationid'));
        }        
    }    
    
    /**
     * Filter notified
     * @param $notified string
     * 
     * notified
     * notnotified
     */     
    public function filterNotified($notified) {
        if ($notified !== false){
        switch ($notified){
            case 'notified':
            return $this->andWhere($this->expr()->isNotNull('m.notified'));
            break;
            case 'notnotified':
            return $this->andWhere($this->expr()->isNull('m.notified'));
            break;             
        }
        }        
    } 
    
    /**
     * Add single filter
     * @param string $field Field name to filter
     * @param mix $filter Mixed filter data
     * 
     */     
    public function addFilter($field, $filter) {  
        $fn = 'filter'.ucfirst($field);
        if (method_exists($this,$fn)) { 
            $this->$fn($filter);
        }    
    }
 
    /**
     * Add multiple filters
     * @param array $f Array of filters
     * 
     */     
    public function addFilters($f) {
        foreach ($f as $field => $filter) {
            $this->addFilter($field, $filter);
        }
    }

    /**
     * Add search
     * @param array $s
     * 
     * sender
     * recipient
     * subject
     * text
     */     
    public function addSearch($s) {
        
        $search = $s['search'];
        $search_field = $s['search_field'];
        
        if ($search === false || $search_field === false){
            return;    
        }
        
        switch ($search_field){
            case 'sender':
                if(is_numeric($search)){
                    return $this->filterSender($search);    
                }elseif (is_string($search)){
                $uid = UserUtil::getIdFromName($search);
                $uid = $uid !== false ? $uid : 0;           
                    return $this->filterSender($uid);    
                }
            break;
            case 'recipient':
                if(is_numeric($search)){
                    return $this->filterRecipient($search);    
                }elseif (is_string($search)){
                $uid = UserUtil::getIdFromName($search);
                $uid = $uid !== false ? $uid : 0;           
                    return $this->filterRecipient($uid);    
                }
            break;            
            case 'subject':
                    return $this
                            ->andWhere('m.subject LIKE :search')
                            ->setParameter('search', '%'.$search.'%');   
            case 'text':
                    return $this
                            ->andWhere('m.text LIKE :search')
                            ->setParameter('search', '%'.$search.'%');   
        }
    }

    /**
     * Add sort
     * @param string $sortBy Field name to sort by
     * @param string $sortOrder sort order ASC/DESC
     */     
    public function sort($sortBy, $sortOrder) {
        return $this->orderBy('m.' . $sortBy, $sortOrder);
    }    
    
}
