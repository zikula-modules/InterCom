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
        $qb = $this->createQueryBuilder('m');
        $qb->select('m')
            ->leftJoin('m.recipientUsers', 'u')
            ->leftJoin('m.messageUserData', 'd')
            ->andWhere(
                $qb->expr()->andX(
                    'm.id = u.message',
                    'u.user = :user',
                    $qb->expr()->isNull('m.parent'),
                    $qb->expr()->isNotNull('m.sent')
                )
            )
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->andX(
                        'm.id = d.message',
                        'd.user = :user',
                        $qb->expr()->isNull('d.deleted')
                    ),
                    $qb->expr()->isNull('d.message')
                )
            )
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
        $qb = $this->createQueryBuilder('m');
        $qb->select('m')
            ->leftJoin('m.messageUserData', 'd')
            ->andWhere(
                $qb->expr()->andX(
                    'm.sender = :user',
                    $qb->expr()->isNull('m.parent'),
                    $qb->expr()->isNotNull('m.sent')
                )
            )
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->andX(
                            'm.id = d.message',
                            'd.user = :user',
                            $qb->expr()->isNull('d.deleted')
                        ),
                    $qb->expr()->isNull('d.message')
                )
            )
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
        $qb = $this->createQueryBuilder('m');
        $qb->select('m')
            ->andWhere(
                $qb->expr()->andX(
                    'm.sender = :user',
                    $qb->expr()->isNull('m.parent'),
                    $qb->expr()->isNull('m.sent')
                )
            )
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

    public function getStoredMessagesByUser($user, $sortby, $sortorder, $limit, $page = 1)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('m')
            ->leftJoin('m.recipientUsers', 'u')
            ->leftJoin('m.messageUserData', 'd')
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->isNull('m.parent'),
                    $qb->expr()->isNotNull('m.sent'),
                    'm.id = u.message',
                    'u.user = :user',
                    'm.id = d.message',
                    'd.user = :user',
                    $qb->expr()->isNotNull('d.stored'),
                    $qb->expr()->isNull('d.deleted')
                )
            )
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
        $qb = $this->createQueryBuilder('m');
        $qb->select('m')
            ->leftJoin('m.recipientUsers', 'u')
            ->leftJoin('m.messageUserData', 'd')
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->isNull('m.parent'),
                    $qb->expr()->isNotNull('m.sent'),
                    'm.id = u.message',
                    'u.user = :user',
                    'm.id = d.message',
                    'd.user = :user',
                    $qb->expr()->isNotNull('d.deleted')
                )
            )
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

    public function getLabeledMessagesByUser($user, $sortby, $sortorder, $limit, $page = 1, $label = false)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('m')
            ->leftJoin('m.recipientUsers', 'u')
            ->leftJoin('m.messageUserData', 'd')
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->isNull('m.parent'),
                    $qb->expr()->isNotNull('m.sent'),
                    'm.id = u.message',
                    'u.user = :user',
                    'm.id = d.message',
                    'd.user = :user',
                    $qb->expr()->isNotNull('d.label'),
                    $qb->expr()->isNull('d.deleted')
                )
            )
            ->setParameter('user', $user);

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
        $qb = $this->createQueryBuilder('m');
        $qb->select('count(m.id)')
            ->leftJoin('m.recipientUsers', 'u')
            ->leftJoin('m.messageUserData', 'd')
            ->andWhere(
                $qb->expr()->andX(
                    'm.id = u.message',
                    'u.user = :user',
                    $qb->expr()->isNull('m.parent'),
                    $qb->expr()->isNotNull('m.sent')
                )
            )
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->andX(
                        'm.id = d.message',
                        'd.user = :user',
                        $qb->expr()->isNull('d.deleted')
                    ),
                    $qb->expr()->isNull('d.message')
                )
            )
            ->setParameter('user', $user);

        if ($notSeen) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->andX(
                        'm.id = d.message',
                        'd.user = :user',
                        $qb->expr()->isNull('d.seen'),
                        $qb->expr()->isNull('d.deleted')
                    ),
                    $qb->expr()->isNull('d.message')
                )
            );
        }

        return $qb->getQuery()
                ->useQueryCache(true)
                ->useResultCache(true, 3600)
                ->getSingleScalarResult();
    }
}
