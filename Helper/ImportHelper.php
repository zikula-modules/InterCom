<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Helper;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\IntercomModule\Entity\Message\AbstractMessageEntity;
use Zikula\IntercomModule\Entity\Message\NormalEntity;
use Zikula\IntercomModule\Entity\MessageDetails\MessageUserDetailsEntity;
use Zikula\IntercomModule\Entity\Recipient\UserRecipientEntity;
use Zikula\UsersModule\Entity\UserEntity;

/**
 * ImportHelper.
 *
 * @author Kaik
 */
class ImportHelper
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var VariableApi
     */
    private $variableApi;

    public function __construct(
            RequestStack $requestStack,
            EntityManager $entityManager,
            VariableApi $variableApi
         ) {
        $this->name = 'ZikulaIntercomModule';
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();
        $this->entityManager = $entityManager;
        $this->variableApi = $variableApi;
    }

    public function isUpgrading()
    {
        return $this->variableApi->get('ZikulaIntercomModule', 'upgrading', false);
    }

    /*
     * Check current tables.
     */
    public function getCurrentData()
    {
        if (!$this->isUpgrading()) {
            return 0;
        }

        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT count(*) AS total FROM intercom_messages';
        $statement = $connection->prepare($sql);
        $statement->execute();
        //we could add all content count but lets react only for messages
        $content_count = (int) $statement->fetchColumn();

        return $content_count;
    }

    /*
     * Remove current data.
     *
     * @todo create remove all data functionality maybe in repository?
     */
    public function removeCurrentData()
    {
        if (!$this->isUpgrading()) {
            return false;
        }

        return true;
    }

    /*
     * Get total messages count to import.
     */
    public function getTotal()
    {
        if (!$this->isUpgrading()) {
            return 0;
        }

        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT count(*) AS total FROM '.$this->isUpgrading().'_intercom';
        $statement = $connection->prepare($sql);
        $statement->execute();

        return $statement->fetchColumn();
    }

    public function importData($data)
    {
        $connection = $this->entityManager->getConnection();
        $limit = $data['pageSize'];
        $offset = $data['page'] == 0 ? $data['page'] : $data['page'] * $limit;
        $sql = 'SELECT * FROM '.$this->isUpgrading().'_intercom ORDER BY pn_msg_id ASC LIMIT :offset,:limit';
        $statement = $connection->prepare($sql);
        $statement->bindValue('limit', $limit, \PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, \PDO::PARAM_INT);
        $statement->execute();
        $currentPageItems = $statement->fetchAll();
        $data['rejected_items'] = []; // each time new
        foreach ($currentPageItems as $item) {
            // only two reasons for message to be rejected
            if ($item['pn_msg_text'] == '') {
                $data['rejected_items'][(int) $item['pn_msg_id']] = $item;
                $data['rejected_items'][(int) $item['pn_msg_id']]['reason'] = 0;
                $data['rejected']++;
                continue;
            }
            $itemExists = $this->entityManager->find(AbstractMessageEntity::class, (int) $item['pn_msg_id']);
            if ($itemExists) {
                $data['rejected_items'][(int) $item['pn_msg_id']] = $item;
                $data['rejected_items'][(int) $item['pn_msg_id']]['reason'] = 1;
                $data['rejected']++;
                continue;
            }
            // previous versions had only normal entity equivalent messages
            $message = new NormalEntity();
            // basics
            $message->setId((int) $item['pn_msg_id']);
            $message->setSubject($item['pn_msg_subject']);
            $message->setText($item['pn_msg_text']);
            if ($item['pn_msg_time'] != '') {
                $date = new \DateTime($item['pn_msg_time']);
                $message->setCreatedAt($date);
                $message->setSent($date);
            } else {
                $date = new \DateTime('now');
            }
            // sender
            if (is_int((int) $item['pn_from_userid'])) {
                $senderExists = $this->entityManager->find(UserEntity::class, $item['pn_from_userid']);
                if ($senderExists) {
                    $message->setSender($senderExists);
                }
            }
            // recipient - unfortunatley there is no direct info about previous group messages
            if (is_int((int) $item['pn_to_userid'])) {
                $recipientExists = $this->entityManager->find(UserEntity::class, $item['pn_to_userid']);
                if ($recipientExists) {
                    $recipient = new UserRecipientEntity();
                    $recipient->setUser($recipientExists);
                    $recipient->setMessage($message);
                    $message->addRecipientUser($recipient);
                }
            }
            if ($senderExists) {
                $senderDetails = new MessageUserDetailsEntity();
                $create = false;
                if (!(bool) $item['pn_msg_outbox']) {
                    $senderDetails->setDeleted($date);
                    $create = true;
                }
                if ($create) {
                    $senderDetails->setUser($senderExists);
                    $senderDetails->setMessage($message);
                    $message->getMessageUserData()->add($senderDetails);
                }
            }
            if ($recipientExists) {
                $recipientDetails = new MessageUserDetailsEntity();
                $create = false;
                if ((bool) $item['pn_msg_popup']) {
                    $recipientDetails->setNotified($date);
                    $create = true;
                }
                if ((bool) $item['pn_msg_read']) {
                    $recipientDetails->setSeen($date);
                    $create = true;
                }
                if ((bool) $item['pn_msg_replied']) {
                    $recipientDetails->setReplied($date);
                    $create = true;
                }
                if ((bool) $item['pn_msg_stored']) {
                    $recipientDetails->setStored($date);
                    $create = true;
                }
                if (!(bool) $item['pn_msg_inbox']) {
                    $recipientDetails->setDeleted($date);
                    $create = true;
                }
                if ($create) {
                    $recipientDetails->setUser($recipientExists);
                    $recipientDetails->setMessage($message);
                    $message->getMessageUserData()->add($recipientDetails);
                }
            }
            //store object
            $metadata = $this->entityManager->getClassMetadata(get_class($message));
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManager->persist($message);
            $data['imported']++;
        }
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $data;
    }
}
