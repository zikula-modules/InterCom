/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Zikula = Zikula || {};
Zikula.Intercom = Zikula.Intercom || {};
Zikula.Intercom.settings = {};


(function ($) {

    var settings = [];

    Zikula.Intercom.settings.set = function ($settings)
    {
        settings = $settings;
        console.log('Intercom:init:0: module set settings');
    };
    
    Zikula.Intercom.settings.get = function ()
    {
         return settings;
    };   

})(jQuery);