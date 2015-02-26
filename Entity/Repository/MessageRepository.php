<?php

/**
 * Intercom
 *
 * @copyright (c) 2001-now, Intercom Development Team
 * @link https://github.com/zikula-modules/Intercom
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Intercom
 */

namespace Zikula\IntercomModule\Entity\Repository;

use ServiceUtil;
use Doctrine\ORM\EntityRepository;
use Zikula\IntercomModule\Entity\MessagesQueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MessageRepository extends EntityRepository
{
    public function build()
    {
       $em = ServiceUtil::getService('doctrine.entitymanager'); 
       $qb = new MessagesQueryBuilder($em); 
       return $qb;
    }
    
    /**
     * Get all or count all or get one
     *
     * @param integer $id
     */
    public function getOrCountAll($onlyone = false, $countonly = false, $f, $s, $sortby, $sortorder, $startnum = 1, $itemsperpage = 15)
    {           
    
        $qb = $this->build();    
        $qb->select('m');  
        $qb->from('Zikula\IntercomModule\Entity\MessageEntity', 'm');
        //filters
        $qb->addFilters($f);
        //search
        $qb->addSearch($s);
        //sort
        $qb->sort($sortby,$sortorder);   
        
        $query = $qb->getQuery();
        
        if ($onlyone){
            $item = $query->getOneOrNullResult();  
            return $item;          
        }

        $query->setFirstResult($startnum -1)->setMaxResults($itemsperpage);        
        
        if ($countonly){
            $paginator = new Paginator($query, false);
            $items = $paginator->count(); 
        }else{
            $items = $query->getResult();  
        }
        return $items;
    }
    
    public function getAll($args) {
        
        //internal switch
        $countonly = isset($args['countonly']) ? $args['countonly'] : false;
        $onlyone = isset($args['onlyone']) ? $args['onlyone'] : false;
        //pager
        $startnum = isset($args['startnum']) ? $args['startnum'] : 1;
        $startnum      = $startnum < 1 ? 1 : $startnum;
        $itemsperpage = isset($args['perpage']) ? $args['perpage'] : 25;    
        //sort
        $sortby = isset($args['sortby']) ? $args['sortby'] : 'send';    
        $sortorder = isset($args['sortorder']) ? $args['sortorder'] : 'DESC';     
        //filter's
        $f['deleted'] = isset($args['deleted']) && $args['deleted'] !== '' ? $args['deleted'] : false;
        $f['stored'] = isset($args['stored']) && $args['stored'] !== '' ? $args['stored'] : false;        
        $f['conversations'] = isset($args['conversations']) && $args['conversations'] !== '' ? $args['conversations'] : false;        
        $f['notified'] = isset($args['notified']) && $args['notified'] !== '' ? $args['notified'] : false;
        $f['replied'] = isset($args['replied']) && $args['replied'] !== '' ? $args['replied'] : false;
        $f['seen'] = isset($args['seen']) && $args['seen'] !== '' ? $args['seen'] : false;
        $f['sender'] = isset($args['sender']) && $args['sender'] !== '' ? $args['sender'] : false;
        $f['recipient'] = isset($args['recipient']) && $args['recipient'] !== '' ? $args['recipient'] : false;
        $f['id'] = isset($args['id']) && $args['id'] !== '' ? $args['id'] : false;
        $f['subject'] = isset($args['subject']) && $args['subject'] !== '' ? $args['subject'] : false;
        $f['text'] = isset($args['text']) && $args['text'] !== '' ? $args['text'] : false;
        $f['send'] = isset($args['send']) && $args['send'] !== '' ? $args['send'] : false;        
       //search
        $s['search'] = isset($args['search']) && $args['search'] !== '' ? $args['search'] : false;
        $s['search_field'] = isset($args['search_field']) && $args['search_field'] !== '' ? $args['search_field'] : false;      
       
        return $this
          ->getOrCountAll($onlyone, $countonly, $f, $s, $sortby, $sortorder, $startnum, $itemsperpage);
    }
    
    public function getCount($a) {
       
       $a['countonly'] = true;
       return $this
          ->getAll($a); 
    }
    
    public function getOneBy($a){ 
        
        $a['onlyone'] = true;
        return $this
          ->getAll($a);        
    }
    
}