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
use Doctrine\ORM\Tools\Pagination\Paginator;

class MessagesRepository extends EntityRepository
{
    /**
     * Paginator Helper.
     *
     * Pass through a query object, current page & limit
     * the offset is calculated from the page and limit
     * returns an `Paginator` instance, which you can call the following on:
     *
     *     $paginator->getIterator()->count() # Total fetched (ie: `5` posts)
     *     $paginator->count() # Count of ALL posts (ie: `20` posts)
     *     $paginator->getIterator() # ArrayIterator
     *
     * @param Doctrine\ORM\Query $dql   DQL Query Object
     * @param int                $page  Current page (defaults to 1)
     * @param int                $limit The total number per page (defaults to 5)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function paginate($dql, $page = 1, $limit = 15)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    public function getRecivedMessagesByUser($user, $sortby, $sortorder, $limit, $page = 1)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.recipientUsers', 'u')
            ->leftJoin('m.messageUserData', 'd')
            ->where('m.id = u.message')
            ->andWhere('m.id = d.message')
            ->andWhere('m.parent IS NULL')
            ->andWhere('m.sent IS NOT NULL')
            ->andWhere('u.user = :user')
            ->andWhere('d.user = :user')
            ->andWhere('d.deleted IS NULL')
            ->setParameter('user', $user);

        switch ($sortby) {
            case 'sent':
                $qb->orderBy('m.sent', $sortorder);

                break;
            case 'subject':
                $qb->orderBy('m.subject', $sortorder);

                break;
            default:
                $qb->orderBy('m.sent', $sortorder);

                break;
        }

        $query = $qb->getQuery();
        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }

    public function getSentMessagesByUser($user, $sortby, $sortorder, $limit, $page = 1)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.messageUserData', 'd')
            ->where('m.sender = :user')
            ->andWhere('m.id = d.message')
            ->andWhere('m.parent IS NULL')
            ->andWhere('m.sent IS NOT NULL')
            ->andWhere('d.user = :user')
            ->andWhere('d.deleted IS NULL')
            ->setParameter('user', $user);

        switch ($sortby) {
            case 'sent':
                $qb->orderBy('m.sent', $sortorder);

                break;
            case 'subject':
                $qb->orderBy('m.subject', $sortorder);

                break;
            default:
                $qb->orderBy('m.sent', $sortorder);

                break;
        }

        $query = $qb->getQuery();
        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }

    public function getDraftMessagesByUser($user, $sortby, $sortorder, $limit, $page = 1)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.sender = :user')
            ->setParameter('user', $user)
            ->andWhere('m.parent IS NULL')
            ->andWhere('m.sent IS NULL');

        switch ($sortby) {
            case 'created':
                $qb->orderBy('m.createdAt', $sortorder);

                break;
            case 'subject':
                $qb->orderBy('m.subject', $sortorder);

                break;
            default:
                $qb->orderBy('m.createdAt', $sortorder);

                break;
        }

        $query = $qb->getQuery();
        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }

    public function getStoredMessagesByUser($user, $sortby, $sortorder, $limit, $page = 1)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.recipientUsers', 'u')
            ->leftJoin('m.messageUserData', 'd')
            ->where('m.id = u.message')
            ->andWhere('m.id = d.message')
            ->andWhere('m.parent IS NULL')
//            ->andWhere('m.sent IS NOT NULL')
            ->andWhere('u.user = :user')
            ->andWhere('d.user = :user')
            ->andWhere('d.stored IS NOT NULL')
            ->andWhere('d.deleted IS NULL')
            ->setParameter('user', $user);

        switch ($sortby) {
            case 'created':
                $qb->orderBy('m.createdAt', $sortorder);

                break;
            case 'subject':
                $qb->orderBy('m.subject', $sortorder);

                break;
            default:
                $qb->orderBy('m.createdAt', $sortorder);

                break;
        }

        $query = $qb->getQuery();
        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }

    public function getDeletedMessagesByUser($user, $sortby, $sortorder, $limit, $page = 1)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.recipientUsers', 'u')
            ->leftJoin('m.messageUserData', 'd')
            ->where('m.id = u.message')
            ->andWhere('m.id = d.message')
            ->andWhere('m.parent IS NULL')
            ->andWhere('m.sent IS NOT NULL')
            ->andWhere('u.user = :user')
            ->andWhere('d.user = :user')
            ->andWhere('d.deleted IS NOT NULL')
            ->setParameter('user', $user);

        switch ($sortby) {
            case 'created':
                $qb->orderBy('m.createdAt', $sortorder);

                break;
            case 'subject':
                $qb->orderBy('m.subject', $sortorder);

                break;
            default:
                $qb->orderBy('m.createdAt', $sortorder);

                break;
        }

        $query = $qb->getQuery();
        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }

    public function getLabeledMessagesByUser($user, $sortby, $sortorder, $limit, $page = 1, $label = 1)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.recipientUsers', 'u')
            ->leftJoin('m.messageUserData', 'd')
            ->where('m.id = u.message')
            ->andWhere('m.id = d.message')
            ->andWhere('m.parent IS NULL')
//            ->andWhere('m.sent IS NOT NULL')
            ->andWhere('u.user = :user')
            ->andWhere('d.user = :user')
            ->andWhere('d.deleted IS NULL')
            ->andWhere('d.label IS NOT NULL')
            ->setParameter('user', $user);
        dump($label);
        if ($label) {
            $qb->andWhere('d.label = :label')
            ->setParameter('label', $label);
        }

        switch ($sortby) {
            case 'created':
                $qb->orderBy('m.createdAt', $sortorder);

                break;
            case 'subject':
                $qb->orderBy('m.subject', $sortorder);

                break;
            default:
                $qb->orderBy('m.createdAt', $sortorder);

                break;
        }

        $query = $qb->getQuery();
        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }

    public function getMessagesCountByUser($user, $notSeen = false)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->leftJoin('m.recipientUsers', 'u')
            ->leftJoin('m.messageUserData', 'd')
            ->where('m.id = u.message')
            ->andWhere('m.id = d.message')
            ->andWhere('m.parent IS NULL') // only parents
            ->andWhere('m.sent IS NOT NULL') // only inbox recived pessages
            ->andWhere('u.user = :user')
            ->andWhere('d.user = :user')
            ->andWhere('d.deleted IS NULL') // not deleted
            ->setParameter('user', $user);

        if ($notSeen) {
            $qb->andWhere('d.seen IS NULL'); // not seen
        }

        return $qb->getQuery()
                ->useQueryCache(true)
                ->useResultCache(true, 3600)
                ->getSingleScalarResult();
    }
}
