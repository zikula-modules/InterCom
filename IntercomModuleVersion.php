<?php
/**
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

namespace Zikula\IntercomModule;

use ModUtil;

class IntercomModuleVersion extends \Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();
        $meta['version']        = '3.0.0';
        $meta['description']    = $this->__('Provides a private messaging system with an individual mailbox for each user, incorporating integration with the user account panel and with various other modules and blocks.');
        $meta['displayname']    = $this->__('InterCom private messaging');
        $meta['url']            = $this->__('intercom');
        $meta['contact']        = 'InterCom Development Team - http://code.zikula.org/intercom/';
        $meta['core_min']       =   '1.4.0';
        $meta['oldnames']       = array('InterCom');
        $meta['capabilities']   = array('message' => array('version' => '1.0'));

        // module dependencies
        $meta['dependencies'] = array(
                array( 'modname'    => 'ContactList',
                        'minversion' => '1.0.0', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED), //'reason' => $this->__('Contact list allows to organize users in contacts'),
                array( 'modname'    => 'BBCode',
                        'minversion' => '3.0.0', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED), // 'reason' => $this->__('BBCode allows bracket-tag markup in post text.'),            
                array( 'modname'    => 'BBSmile',
                        'minversion' => '3.0.0', 'maxversion' => '',
                        'status'     => ModUtil::DEPENDENCY_RECOMMENDED), // 'reason' => $this->__('BBSmile allows addition of smilies to post text.')            
        );

        // This one adds the info to the DB, so that users can click on the
        // headings in the permission module
        $meta['securityschema'] = array('InterCom::' => '::');
        return $meta;
    }
}