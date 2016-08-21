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

namespace Zikula\IntercomModule\Manager;

class Messages {

    private $name;
    public $entityManager;
    private $messages;
    private $filters;

    /**
     * construct
     */
    public function __construct($entityManager) {
        $this->name = 'ZikulaIntercomModule';
        $this->entityManager = $entityManager;
    }

    /**
     *  load messages
     */
    public function load($box, $filters) {

        switch ($box) {
            case 'inbox':
                $filters['recipient'] = \UserUtil::getVar('uid');
                $filters['deleted'] = 'byrecipient';
                break;
            case 'outbox':
                $filters['sender'] = \UserUtil::getVar('uid');
                $filters['deleted'] = 'bysender';
                break;
            case 'archive':
                $filters['recipient'] = \UserUtil::getVar('uid');
                $filters['deleted'] = 'byrecipient';
                $filters['stored'] = 'byrecipient';
                break;
            case 'admin':

                break;
            default:
                break;
        }


        $this->messages = $this->entityManager
                ->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
                ->getAll($filters);

        $this->filters = $filters;

        return $this;
    }

    /**
     *  get messages
     */
    public function getmessages() {
        return $this->messages;
    }

    /**
     *  get messages
     */
    public function getmessages_array() {
        $messages_array = array();
        foreach ($this->messages as $key => $message) {
            $messages_array[$key] = $message;
        }
        return $messages_array;
    }

    /**
     *  get messages count
     */
    public function getmessages_count() {
        return $this->messages->count();
    }

    /**
     *  get messages count
     */
    public function getPager() {
        return ['page' => $this->filters['page'],
            'total' => ceil($this->getmessages_count() / $this->filters['limit'])];
    }

    /**
     *  get user messages counts
     */
    public function count() {
        return $this->messages->count();
    }

}
