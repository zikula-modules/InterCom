<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class LabelsRepository extends EntityRepository
{
}

//
//
//use ServiceUtil;
//use Doctrine\ORM\EntityRepository;
//use Zikula\IntercomModule\Entity\MessagesQueryBuilder;
//use Doctrine\ORM\Tools\Pagination\Paginator;
//
//class MessageRepository extends EntityRepository
//{
//
//    /**
//     * Helper function to build custom query builder
//     *
//     * @param MessagesQueryBuilder $qb
//     */
//    public function build()
//    {
//       $em = ServiceUtil::getService('doctrine.entitymanager');
//       $qb = new MessagesQueryBuilder($em);
//       return $qb;
//    }
//
//    /**
//     * Get all or count all function
//     *
//     * @param array
//     */
//    public function getOneOrAll($onlyone = false, $f, $s, $sortby, $sortorder, $page = 1, $limit)
//    {
//
//        $qb = $this->build();
//        $qb->select('m');
//        $qb->from('Zikula\IntercomModule\Entity\MessageEntity', 'm');
//        //filters
//        $qb->addFilters($f);
//        //search
//        $qb->addSearch($s);
//        //sort
//        $qb->sort($sortby,$sortorder);
//
//        $query = $qb->getQuery();
//
//        if ($onlyone){
//            $item = $query->getOneOrNullResult();
//            return $item;
//        }
//        $paginator = $this->paginate($query, $page, $limit);
//
//        return $paginator;
//    }
//
//    /**
//     * Paginator Helper
//     *
//     * Pass through a query object, current page & limit
//     * the offset is calculated from the page and limit
//     * returns an `Paginator` instance, which you can call the following on:
//     *
//     *     $paginator->getIterator()->count() # Total fetched (ie: `5` posts)
//     *     $paginator->count() # Count of ALL posts (ie: `20` posts)
//     *     $paginator->getIterator() # ArrayIterator
//     *
//     * @param Doctrine\ORM\Query $dql   DQL Query Object
//     * @param integer            $page  Current page (defaults to 1)
//     * @param integer            $limit The total number per page (defaults to 5)
//     *
//     * @return \Doctrine\ORM\Tools\Pagination\Paginator
//     */
//    public function paginate($dql, $page = 1, $limit = 15)
//    {
//        $paginator = new Paginator($dql);
//
//        $paginator->getQuery()
//            ->setFirstResult($limit * ($page - 1)) // Offset
//            ->setMaxResults($limit); // Limit
//
//        return $paginator;
//    }
//
//    /**
//     * Get all in one function
//     * @param array              $args
//     * @param integer            $onlyone  Internal switch
//     * @param integer            $page  Current page
//     * @param integer            $limit The total number per page
//     *
//     * @return \Doctrine\ORM\Tools\Pagination\Paginator
//     * or
//     * object
//     */
//    public function getAll($args = array())
//    {
//        //internal
//        $onlyone = isset($args['onlyone']) ? $args['onlyone'] : false;
//        //pager
//        $page = isset($args['page']) ? $args['page'] : 1;
//        $page      = $page < 1 ? 1 : $page;
//        $limit = isset($args['limit']) ? $args['limit'] : 25;
//        //sort
//        $sortby = isset($args['sortby']) ? $args['sortby'] : 'send';
//        $sortorder = isset($args['sortorder']) ? $args['sortorder'] : 'DESC';
//        //filter's
//        $f['deleted'] = isset($args['deleted']) && $args['deleted'] !== '' ? $args['deleted'] : false;
//        $f['stored'] = isset($args['stored']) && $args['stored'] !== '' ? $args['stored'] : false;
//        $f['conversations'] = isset($args['conversations']) && $args['conversations'] !== '' ? $args['conversations'] : false;
//        $f['notified'] = isset($args['notified']) && $args['notified'] !== '' ? $args['notified'] : false;
//        $f['replied'] = isset($args['replied']) && $args['replied'] !== '' ? $args['replied'] : false;
//        $f['seen'] = isset($args['seen']) && $args['seen'] !== '' ? $args['seen'] : false;
//        $f['sender'] = isset($args['sender']) && $args['sender'] !== '' ? $args['sender'] : false;
//        $f['recipient'] = isset($args['recipient']) && $args['recipient'] !== '' ? $args['recipient'] : false;
//        $f['id'] = isset($args['id']) && $args['id'] !== '' ? $args['id'] : false;
//        $f['subject'] = isset($args['subject']) && $args['subject'] !== '' ? $args['subject'] : false;
//        $f['text'] = isset($args['text']) && $args['text'] !== '' ? $args['text'] : false;
//        $f['mtype'] = isset($args['mtype']) && $args['mtype'] !== '' ? $args['mtype'] : 'normal';
//        $f['send'] = isset($args['send']) && $args['send'] !== '' ? $args['send'] : false;
//        //search
//        $s['search'] = isset($args['search']) && $args['search'] !== '' ? $args['search'] : false;
//        $s['search_field'] = isset($args['search_field']) && $args['search_field'] !== '' ? $args['search_field'] : false;
//
//        return $this
//          ->getOneOrAll($onlyone, $f, $s, $sortby, $sortorder, $page, $limit);
//    }
//
//    /**
//     * Shortcut to get one item
//     *
//     * @param array              $args
//     * @param integer            $onlyone  Internal switch
//     * @param integer            $page  Current page
//     * @param integer            $limit The total number per page
//     *
//     * @return \Doctrine\ORM\Tools\Pagination\Paginator
//     * or
//     * object
//     */
//    public function getOneBy($a){
//        //set internal
//        $a['onlyone'] = true;
//        return $this
//          ->getAll($a);
//    }
//}