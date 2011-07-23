<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

function smarty_function_replysubject($params, &$smarty)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    $out = '';
    $test = __('Re', $dom) . ':';
    if ($params['subject'] == $test) {
        $out = $params['subjectclean'];
    }
    else {
        $out = __('Re', $dom) . ': ' . $params['subjectclean'];
    }

    return DataUtil::formatForDisplay($out);
}
