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
 * information regarding copyright.
 */
namespace Zikula\Module\IntercomModule\Util;

use DataUtil;
use ServiceUtil;

use Zikula\Module\IntercomModule\Util\Settings;

class Tools {

    /**
     */
    public function deleteAll()
    {
        return true;
    }
    
    /**
     */
    public function deleteInboxes()
    {
        return true;
    } 
    
    /**
     */
    public function deleteOutboxes()
    {
        return true;
    }
    /**
     */
    public function deleteStored()
    {
        return true;
    }
    
    /**
     */
    public function resetSettings()
    {
        $settings = new Settings();
        return $settings->resetSettings();
    }
    
    /**
     */
    public function optimize_db()
    {
        return true;
    }    
  
}
