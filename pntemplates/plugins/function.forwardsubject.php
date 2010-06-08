<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Postnuke
 *
 */

function smarty_function_forwardsubject($params, &$smarty)
{
    $dom = ZLanguage::getModuleDomain('InterCom');
    $out = '';
    $test = __('Fw', $dom) . ':';
    if ($params['subject'] == $test) {
        $out = $params['subjectclean'];
    } else {
        $out = __('Fw', $dom) . ': ' . $params['subjectclean'];
    }

    return DataUtil::formatForDisplay($out);
}
