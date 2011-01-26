<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

class InterCom_Version extends Zikula_Version
{
    public function getMetaData()
    {
        $meta = array();
        $meta['version']        = '2.3.0';
        $meta['description']    = $this->__('Provides a private messaging system with an individual mailbox for each user, incorporating integration with the user account panel and with various other modules and blocks.');
        $meta['displayname']    = $this->__('InterCom private messaging');
        $meta['url']            = $this->__('intercom');
        $meta['contact']        = 'InterCom Development Team - http://code.zikula.org/intercom/';

        $meta['capabilities'] = array('message' => array('version' => '1.0'));

        // module dependencies
        $meta['dependencies'] = array(
                array( 'modname'    => 'ContactList',
                        'minversion' => '1.0.0', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED),
                array( 'modname'    => 'BBCode',
                        'minversion' => '3.0.0', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED),
                array( 'modname'    => 'BBSmile',
                        'minversion' => '3.0.0', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED)
        );

        // This one adds the info to the DB, so that users can click on the
        // headings in the permission module
        $meta['securityschema'] = array('InterCom::' => '::');
        return $meta;
    }
}