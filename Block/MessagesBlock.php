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
namespace Zikula\Module\IntercomModule\Block;

use SecurityUtil;
use ModUtil;
use BlockUtil;
use UserUtil;

class MessagesBlock extends \Zikula_Controller_AbstractBlock
{
    public function init()
    {
        SecurityUtil::registerPermissionSchema('InterCom:messagesblock:', 'Block title::');
    }

    public function info()
    {
        return array ('text_type'      => 'Private messages',
                'module'         => 'InterCom',
                'text_type_long' => $this->__('Private messages block'),
                'allow_multiple' => true,
                'form_content'   => false,
                'form_refresh'   => false,
                'show_preview'   => true,
                'admin_tableless' => true);
    }

    public function display($blockinfo)
    {
        if (!SecurityUtil::checkPermission('InterCom:messagesblock:', '::', ACCESS_OVERVIEW)) {
            return false;
        }

        if (!ModUtil::available('InterCom')) {
            return false;
        }

        if (!UserUtil::isLoggedIn()) {
            return false;
        }

        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        $this->view->add_core_data()->setCaching(false);

        if(empty($vars['pn_template'])) {
            $vars['pn_template'] = 'block/messages.tpl';
        }

        $blockinfo['content'] = $this->view->fetch(trim($vars['pn_template']));

        return BlockUtil::themesideblock($blockinfo);
    }

    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        if(!isset($vars['pn_template']) || empty($vars['pn_template']))   {
            $vars['pn_template']   = 'block/messages.tpl';
        }
        $this->view->add_core_data()->setCaching(false);
        $this->view->assign('vars', $vars);
        return $this->view->fetch('block/messages_modify.tpl');
    }

    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        $vars['pn_template'] = FormUtil::getPassedValue('pn_template', 'block/messages.tpl', 'POST');
        $blockinfo['content'] = BlockUtil::varsToContent($vars);
        return $blockinfo;
    }
}