<?php
/**
 * $Id$
 *
 * InterCom-Block
 *
 */
class InterCom_Block_Messages extends Zikula_Controller_Block
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
            $vars['pn_template'] = 'intercom_block_messages.htm';
        }

        $blockinfo['content'] = $this->view->fetch(trim($vars['pn_template']));

        return BlockUtil::themesideblock($blockinfo);
    }

    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        if(!isset($vars['pn_template']) || empty($vars['pn_template']))   {
            $vars['pn_template']   = 'intercom_block_messages.htm';
        }
        $this->view->add_core_data()->setCaching(false);
        $this->view->assign('vars', $vars);
        return $this->view->fetch('intercom_block_messages_modify.htm');
    }

    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        $vars['pn_template'] = FormUtil::getPassedValue('pn_template', 'intercom_block_messages.htm', 'POST');
        $blockinfo['content'] = BlockUtil::varsToContent($vars);
        return $blockinfo;
    }
}