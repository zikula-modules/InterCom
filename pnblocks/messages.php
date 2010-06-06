<?php
/**
 * $Id$
 *
 * InterCom-Block
 *
 */

function InterCom_messagesblock_init()
{
    SecurityUtil::registerPermissionSchema('InterCom:messagesblock:', 'Block title::');
}

function InterCom_messagesblock_info()
{
    
    $dom = ZLanguage::getModuleDomain('InterCom');
    return array ('text_type'      => 'Private messages',
                  'module'         => 'InterCom',
                  'text_type_long' => __('Private messages block', $dom),
                  'allow_multiple' => true,
                  'form_content'   => false,
                  'form_refresh'   => false,
                  'show_preview'   => true,
                  'admin_tableless' => true);
}

function InterCom_messagesblock_display($blockinfo)
{
    if (!SecurityUtil::checkPermission('InterCom:messagesblock:', '::', ACCESS_OVERVIEW)) {
        return false;
    }
    
    if (!pnModAvailable('InterCom')) {
        return false;
    }    

    if (!pnUserloggedin()) {
        return false;
    }

    $vars = pnBlockVarsFromContent($blockinfo['content']);

    $pnRender = & pnRender::getInstance('InterCom', false, null, true);

    if(empty($vars['pn_template'])) {
        $vars['pn_template'] = 'intercom_block_messages.htm';
    }

    $blockinfo['content'] = $pnRender->fetch(trim($vars['pn_template']));

    return themesideblock($blockinfo);
}

function InterCom_messagesblock_modify($blockinfo)
{
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    if(!isset($vars['pn_template']) || empty($vars['pn_template']))   { 
        $vars['pn_template']   = 'intercom_block_messages.htm'; 
    }
    $pnRender = & pnRender::getInstance('InterCom', false, null, true);
    $pnRender->assign('vars', $vars);    
    return $pnRender->fetch('intercom_block_messages_modify.htm');
}

function InterCom_messagesblock_update($blockinfo)
{
  $vars = pnBlockVarsFromContent($blockinfo['content']);
  $vars['pn_template'] = FormUtil::getPassedValue('pn_template', 'intercom_block_messages.htm', 'POST');
  $blockinfo['content'] = pnBlockVarsToContent($vars);
  return $blockinfo;
}
