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
 * 
 *
 */
class MessagesQueryBuilder extends QueryBuilder {
    
    public function filterId($id) {
        if ($id !== false) {
            return $this
                            ->andWhere('m.id = :id')
                            ->setParameter('id', $id);
        }
    }    

    public function filterInbox($inbox) {
        if ($inbox !== false) {
            return $this
                            ->andWhere('m.inbox = :inbox')
                            ->setParameter('inbox', $inbox);
        }
    }
    
    public function filterOutbox($outbox) {
        if ($outbox !== false) {
            return $this
                            ->andWhere('m.outbox = :outbox')
                            ->setParameter('outbox', $outbox);
        }
    }
    
    public function filterStored($stored) {
        if ($stored !== false) {
            return $this
                            ->andWhere('m.stored = :stored')
                            ->setParameter('stored', $stored);
        }
    }    

    public function filterSender($sender) {
        if ($sender !== false) {
            return $this
                            ->andWhere('m.sender = :sender')
                            ->setParameter('sender', $sender);
        }
    }
    
    public function filterRecipient($recipient) {
        if ($recipient !== false) {
            return $this
                            ->andWhere('m.recipient = :recipient')
                            ->setParameter('recipient', $recipient);
        }
    }    
    

    public function filterSubject($subject) {
        if ($subject !== false) {
            return $this
                            ->andWhere('m.subject = :subject')
                            ->setParameter('subject', $subject);
        }
    }
    
    public function filterSend($send) {
        if ($send !== false){
            return $this->andWhere($this->expr()->isNull('m.send'));
        }        
    }
    
    public function filterText($text) {
        if ($text !== false) {
            return $this
                            ->andWhere('m.text = :text')
                            ->setParameter('text', $text);
        }
    }

    public function filterSeen($seen) {
        if ($seen !== false) {
        switch ($seen){
            case 'seen':
            return $this->andWhere($this->expr()->isNotNull('m.seen'));
            break;
            case 'unseen':
            return $this->andWhere($this->expr()->isNull('m.seen'));
            break;             
        }
        }
    }
    
    public function filterReplied($replied) {
        if ($replied !== false){
            return $this->andWhere($this->expr()->isNull('m.replied'));
        }        
    }
    
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
    
    public function addFilter($field, $filter) {  
        $fn = 'filter'.ucfirst($field);
        if (method_exists($this,$fn)) { 
            $this->$fn($filter);
        }    
    }
    
    public function addFilters($f) {
        foreach ($f as $field => $filter) {
            $this->addFilter($field, $filter);
        }
    }
    
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
 
    public function sort($sortBy, $sortOrder) {
        return $this->orderBy('m.' . $sortBy, $sortOrder);
    }    
    
}
