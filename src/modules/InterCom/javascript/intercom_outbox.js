// Copyright Zikula Foundation 2010 - license GNU/LGPLv2.1 (or at your option, any later version).

/**
 * init javscript for outbox
 */

Event.observe(window, 'load', function() {
    new InterCom("msg_listing", "#msg_listing dl", "#msg_listing .msg_body", "outbox"); 
});
