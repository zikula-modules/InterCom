<?php
/**
 * $Id$
 *
 * InterCom - an advanced private messaging solution for Zikula
 *
 */

class InterCom_Api_Init extends Zikula_AbstractApi
{
    public function import_messages()
    {
        // Security check - important to do this as early on as possible to
        // avoid potential security holes or just too much wasted processing
        if (!SecurityUtil::checkPermission('InterCom::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $old_msg_count = DBUtil::selectObjectCount('priv_msgs');
        // we convert messages in packages of 100. THis can be changed by adjusting the next line:
        $packagesize = 100;
        for ($cnt=0; $cnt <= $old_msg_count; $cnt=$cnt + $packagesize) {
            $msgs = DBUtil::selectObjectArray('priv_msgs', '', '', $cnt, $packagesize);
            //prayer($msgs);
            $ak = array_keys($msgs);
            foreach($ak as $key) {
                $msgs[$key]['msg_subject'] = str_replace(array('<', '>'), array('&lt;', '&gt;'), $msgs[$key]['subject']);
                $msgs[$key]['msg_text']    = str_replace(array('<br />', '<br>', '<BR>'), "\n", $msgs[$key]['msg_text']);
                $msgs[$key]['msg_replied'] = '1';
                $msgs[$key]['msg_popup']   = '1';
                $msgs[$key]['msg_inbox']   = '1';
                $msgs[$key]['msg_outbox']  = '1';
                $msgs[$key]['msg_stored']  = '0';
            }
            //prayer($msgs);
            DBUtil::insertObjectArray($msgs, 'intercom', 'msg_id');
        }
        return true;
    }
}