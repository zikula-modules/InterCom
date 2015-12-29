/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Zikula = Zikula || {};
Zikula.Intercom = Zikula.Intercom || {};
Zikula.Intercom.settings = {};
Zikula.Intercom.settings.data = Zikula.Intercom.settings.data || {};

(function ($) {

    Zikula.Intercom.settings.set = function ($settings)
    {
        Zikula.Intercom.settings.data = $settings;
        console.log('Intercom:init:0: module set settings');
    };
    
    Zikula.Intercom.settings.get = function (var_name)
    {
         return Zikula.Intercom.settings.data[var_name];
    };   

})(jQuery);