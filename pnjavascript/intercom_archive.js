/**
 *
 * $Id: intercom.js 401 2008-11-19 18:42:03Z Landseer $
 *
 * init javscript for archive
 *
 *
 */

Event.observe(window, 'load', function() {
    new InterCom("msg_listing", "#msg_listing dl", "#msg_listing .msg_body", "archive"); 
});
