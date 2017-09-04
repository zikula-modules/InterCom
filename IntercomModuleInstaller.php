<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule;

use Zikula\Core\AbstractExtensionInstaller;
use Zikula\IntercomModule\Entity\Label\LabelEntity;
use Zikula\IntercomModule\Entity\Message\AbstractMessageEntity;
use Zikula\IntercomModule\Entity\Message\NormalEntity;
use Zikula\IntercomModule\Entity\Message\NotificationEntity;
use Zikula\IntercomModule\Entity\Message\SystemEntity;
use Zikula\IntercomModule\Entity\MessageDetails\MessageUserDetailsEntity;
use Zikula\IntercomModule\Entity\Recipient\GroupRecipientEntity;
use Zikula\IntercomModule\Entity\Recipient\UserRecipientEntity;

/**
 * Intercom module installer.
 */
class IntercomModuleInstaller extends AbstractExtensionInstaller
{
    /**
     * Module name
     * (needed for static methods).
     *
     * @var string
     */
    const MODULENAME = 'ZikulaIntercomModule';

    private $entities = [
        LabelEntity::class,
        AbstractMessageEntity::class,
        NormalEntity::class,
        NotificationEntity::class,
        SystemEntity::class,
        MessageUserDetailsEntity::class,
        GroupRecipientEntity::class,
        UserRecipientEntity::class,
    ];

    //import
    private $importTables = [
            'intercom',
    ];

    public function install()
    {
        try {
            $this->schemaTool->create($this->entities);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return false;
        }

        $this->setVars(self::getDefaultVars());
        $this->setUpDefaultLabels();

        return true;
    }

    private function setUpDefaultLabels()
    {
        $labels = [
            ['name'         => 'Important',
                'user'      => null,
                'extraData' => null,
                'sortorder' => 0,
            ],
            ['name'         => 'Friends',
                'user'      => null,
                'extraData' => null,
                'sortorder' => 0,
            ],
            ['name'         => 'Bussiness',
                'user'      => null,
                'extraData' => null,
                'sortorder' => 0,
            ],
            ];
        foreach ($labels as $label) {
            $l = new LabelEntity();
            $l->merge($label);
            $this->entityManager->persist($l);
        }
        $this->entityManager->flush();
    }

    public function upgrade($oldversion)
    {
        // Only support upgrade from version 3.1 and up. Notify users if they have a version below that one.
        if (version_compare($oldversion, '2.3', '<')) {
            // Inform user about error, and how he can upgrade to $modversion
            $upgradeToVersion = $this->bundle->getMetaData()->getVersion();

            $this->addFlash('error', $this->__f('Notice: This version does not support upgrades from versions of InterCom less than 2.3. Please upgrade to 2.3 before upgrading again to version %s.', $upgradeToVersion));

            return false;
        }
        switch ($oldversion) {
            case '2.3.0':
                if (!$this->upgrade_settings()) {
                    return false;
                }

                $connection = $this->entityManager->getConnection();
                $prefix = $this->container->hasParameter('prefix') ? $this->container->getParameter('prefix') : '';
                $schemaManager = $connection->getSchemaManager();
                $schema = $schemaManager->createSchema();
                if (!$schema->hasTable($prefix.'intercom')) {
                    $this->addFlash('error', $e->getMessage().$this->__f('There was a problem recognizing the existing Intercom tables. Please confirm that your settings for prefix in $ZConfig[\'System\'][\'prefix\'] match the actual Intercom tables in the database. (Current prefix loaded as `%s`)', ['%s' => $prefix]));

                    return false;
                }

                if ($prefix != '') {
                    $this->removeTablePrefixes($prefix);
                }
                // mark tables for import
                $upgrade_mark = str_replace('.', '_', $oldversion).'_';
                $this->markTablesForImport($upgrade_mark);
                // add upgrading info for later
                $this->setVar('upgrading', str_replace('.', '_', $oldversion));

                //install module now
                try {
                    $this->schemaTool->create($this->entities);
                } catch (\Exception $e) {
                    $this->addFlash('error', $e->getMessage());

                    return false;
                }

                $this->setVars(self::getDefaultVars());
                $this->setUpDefaultLabels();

                $this->addFlash('status', $this->__('Please go to Intercom admin import to do full data import.'));

                break;
        }

        return true;
    }

    public function uninstall()
    {
        try {
            $this->schemaTool->drop($this->entities);
        } catch (\PDOException $e) {
            $this->addFlash('error', $e->getMessage());

            return false;
        }
        // Delete any module variables
        $this->delVars();

        return true;
    }

    /**
     * remove all table prefixes.
     */
    public function removeTablePrefixes($prefix)
    {
        $connection = $this->entityManager->getConnection();
        // remove table prefixes
        foreach ($this->importTables as $value) {
            $sql = 'RENAME TABLE '.$prefix.$value.' TO '.$value;
            $stmt = $connection->prepare($sql);

            try {
                $stmt->execute();
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage().$this->__f('There was a problem recognizing the existing Intercom tables. Please confirm that your prefix match the actual Intercom tables in the database. (Current prefix loaded as `%s`)', ['%s' => $prefix]));

                return false;
            }
        }
    }

    /**
     * Mark tables for import with import_ prefix.
     */
    public function markTablesForImport($prefix)
    {
        $connection = $this->entityManager->getConnection();
        foreach ($this->importTables as $value) {
            $sql = 'RENAME TABLE '.$value.' TO '.$prefix.$value;
            $stmt = $connection->prepare($sql);

            try {
                $stmt->execute();
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage().$this->__f('There was a problem recognizing the existing Intercom tables. Please confirm that your prefix match the actual Intercom tables in the database. (Current prefix loaded as `%s`)', ['%s' => $prefix]));

                return false;
            }
        }
    }

    /**
     * Upgrade settings to current version.
     */
    private function upgrade_settings()
    {
        $currentModVars = $this->getVars();
        $defVars = $this->getDefaultVars();

        foreach ($defVars as $key => $defVar) {
            if (array_key_exists($key, $currentModVars)) {
                $type = gettype($defVar);
                switch ($type) {
                    default:
                        $var = $currentModVars[$key];

                        break;
                }
            }
            $this->setVar($key, $var);
        }

        return true;
    }

    /**
     * get the default module var values.
     *
     * @return array
     */
    public static function getDefaultVars()
    {
        return [
            //General
            'active'        => true,
            'maintain'      => 'Sorry! The private messaging system is currently off-line for maintenance. Please check again later, or contact the site administrator.',
            'disable_ajax'  => false,
            //Display message text settings
            'allowhtml'     => false,
            'allowsmilies'  => false,
            //Limitations
            'limitarchive'  => '50',
            'limitoutbox'   => '50',
            'limitinbox'    => '50',
            'perpage'       => '25',
            //Email
            'allow_emailnotification' => true,
            'force_emailnotification' => false,
            'mailsubject'             => 'You have a new private message',
            'fromname'                => '',
            'from_email'              => '',
            'mailsender'              => '',
            //Autoresponder
            'allow_autoreply' => false,
            //Users prompt
            'userprompt'         => 'Welcome to the private messaging system',
            'userprompt_display' => false,
            //Welcome
            'welcomemessage_send'   => false,
            'welcomemessagesender'  => 'admin',
            'welcomemessagesubject' => 'Welcome to the private messaging system on %sitename%', // quotes are important here!!
            'welcomemessage'        => "Hello!' .'Welcome to the private messaging system on %sitename%. Please remember that use of the private messaging system is subject to the site\'s terms of use and privacy statement. If you have any questions or encounter any problems, please contact the site administrator. Site admin", // quotes are important here!!!
            'savewelcomemessage'    => false,
            'intlwelcomemessage'    => '',
            //Protection
            'protection_on'     => true,
            'protection_time'   => '15',
            'protection_amount' => '15',
            'protection_mail'   => false,
            //layout
            'layout' => 'classic',
            //labels
            'labels_enabled'      => true,
            'user_labels_enabled' => true,
            //System notifications
            'notifications_enabled' => true,
            //Support messages
            'support_enabled' => true,
            //Group sender
            'group_sender_enabled' => true,
            'group_sender_type'    => 'owner',
            //Multiple user recipients
            'multiple_user_recipients_enabled' => true,
            //Group recipients
            'group_recipient_enabled'           => true,
            'multiple_group_recipients_enabled' => true,
            //draft
            'drafts_enabled' => true,
        ];
    }
}
