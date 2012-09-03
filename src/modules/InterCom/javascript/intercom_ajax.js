/**
 *
 * $Id$
 *
 * InterCom javascript class
 *
 * based on the accordion.js by Lucas van Dijk
 * Accordion Effect for Script.aculo.us
 * Created by Lucas van Dijk
 * http://www.return1.net
 * http://www.opensource.org/licenses/mit-license.php
 *
 */

// remove the no ajax links
document.observe("dom:loaded", function() {
    // initially hide all containers for tab content
    $$('.noajax').each(function(el) {
        el.insert ({'after': el.innerHTML} );
        el.remove();
    });
});

var InterCom = Class.create();

InterCom.prototype = 
{
    initialize: function(listid, handles, bodies, boxtype, options) 
    {
        this.options = this._set_options(options);
        this.headers = $$(handles);
        this.bodies = $$(bodies);
        this.boxtype = boxtype;
        
        this.CLEAR = 0;
        this.ERROR    = 1;
        this.INFO     = 2;
        this.WORKING  = 3;

        $$('.ic_texpand').each(function(el){
          new Texpand(el, {autoShrink: true, shrinkOnBlur:false, expandOnFocus: false, expandOnLoad: true });
        });

        if($(listid)) { 
        
            if(this.bodies.length != this.headers.length) {
                throw Error('Error! There is a mismatch between the number of message headers and the number of message bodies.');
            }
            
            this.headers.each(function(h) {
                $$("#" + h.id + " .clickable").each(function(el) {
                    Event.observe(el, this.options.event_trigger, this.toggleview.bind(this, el.parentNode.id));
                    el.style.cursor = "pointer";
                }.bind(this));
            }.bind(this));
            
            this.bodies.each(function(b) {
                b.style.display = "none";
                
            });
            
            if (this.options.default_open != '') {
                this.bodies[this.options.default_open].addClassName('accordeon_open');
            
                this.toggleview(this.options.default_open, true);
            }
            
            // add event handlers for mail replies
            this.mailreplybuttons = $$('.mailreply');
            this.mailreplybuttons.each(function(el) {
                el.href = "javascript:void(0);";
                Event.observe(el, 'click', this.msgreply.bind(this, el.id));
            }.bind(this));
            
            // add event handlers for mail forwards
            this.mailforwardbuttons = $$('.mailforward');
            this.mailforwardbuttons.each(function(el) {
                el.href = "javascript:void(0);";
                Event.observe(el, 'click', this.msgforward.bind(this, el.id));
            }.bind(this));
            
            // add event handlers for mail deletion
            this.maildeletebuttons = $$('.maildelete');
            this.maildeletebuttons.each(function(el) {
                el.href = "javascript:void(0);";
                Event.observe(el, 'click', this.msgdelete.bind(this, el.id));
            }.bind(this));
            
            // add event handlers for mail storage
            this.mailstorebuttons = $$('.mailsave');
            this.mailstorebuttons.each(function(el) {
                if(!el.hasClassName('navigationonly')) {
                    el.href = "javascript:void(0);";
                    Event.observe(el, 'click', this.msgstore.bind(this, el.id));
                }
            }.bind(this));
        
        }
    },

    toggleview: function(headerid) 
    {   
        msgid = headerid.split('-')[1];
        msgbody = $('msgbody-' + msgid);
        if(msgbody.hasClassName('accordeon_open')) {
            new Effect.Parallel(
                [
                    new Effect.Fade(msgbody),
                    new Effect.BlindUp(msgbody)
                ], {
                    duration: this.options.duration
                }
            );
            msgbody.removeClassName('accordeon_open');
        } else {
            new Effect.Parallel(
                [
                    new Effect.BlindDown(msgbody),
                    new Effect.Appear(msgbody)
                ], {
                    duration: this.options.duration
                }
            );
            msgbody.addClassName('accordeon_open');
            // mark message as read or not if you are in the outbox
			if ( this.boxtype != 'msg.outbox' ) {}
			else{
            this.markmsgread(msgid);
			}
        }	
    },
    
    _default_options: 
    {
        duration: 0.3,
        event_trigger: 'click',
        OnStart: function() { },
        OnFinish: function() { },
        default_open: '',
        defaultRows: 3
    },
    
    _set_options: function(options)
    {
        if(typeof options != "undefined") {
            var result = [];
            for(option in this._default_options) {
                if(typeof options[option] == "undefined") {
                    result[option] = this._default_options[option];
                } else {
                    result[option] = options[option];
                }
            }
            return result;
        }
        else
        {
            return this._default_options;
        }
    },
    
    markmsgread: function(msgid)
    {
        pars = 'module=InterCom&func=markmsgread&msgid=' + msgid;
        myAjax = new Ajax.Request(
                    document.location.pnbaseURL + "ajax.php",
                    {
                        method: 'post',
                        parameters: pars,
                        onSuccess: function (transport) {
                            if($('msg-unread-' + msgid).hasClassName('invisible') == false) {
                                $('msg-unread-' + msgid).addClassName('invisible');
                                $('msg-read-' + msgid).removeClassName('invisible');
                            }
                        }.bind(this)
                    }
                    );
        return;
    },

    msgdelete: function(buttonid)
    {
        msgid   = buttonid.split('-')[1];

        this.showInformation(msgid, deletingMessage, 0, this.WORKING);
        
        pars = 'module=InterCom&func=deletefrom' + this.boxtype + '&msgid=' + msgid;
        myAjax = new Ajax.Request(
                    document.location.pnbaseURL + "ajax.php",
                    {
                        method: 'post',
                        parameters: pars,
                        onSuccess: function (transport) {
                            this.clearInformation(msgid);
                            this.showInformation(0, messagearchived, 3000, this.INFO);
                            this.removefromview(msgid, transport);
                        }.bind(this)
                    }
                    );
        return;
    },

    msgstore: function(buttonid)
    {
        msgid   = buttonid.split('-')[1];
        this.showInformation(msgid, archivingMessage, 0, this.WORKING);
        
        pars = 'module=InterCom&func=store&msgid=' + msgid;
        myAjax = new Ajax.Request(
                    document.location.pnbaseURL + "ajax.php",
                    {
                        method: 'post',
                        parameters: pars,
                        onSuccess: function (transport) {
                            this.showInformation(0, messagearchived, 3000, this.INFO);
                            this.removefromview(msgid, transport);
                        }.bind(this)
                    }
                    );
        return;
    },
    
    msgprint: function(buttonid) 
    {
        msgid   = buttonid.split('-')[1];
        
        pars = 'module=InterCom&func=read' + this.boxtype + '&msgid=' + msgid + '&theme=Printer';
        myAjax = new Ajax.Request(
                    document.location.pnbaseURL + "ajax.php",
                    {
                        method: 'post',
                        parameters: pars,
                        onSuccess: function (transport) {
                            this.removefromview(msgid);
                        }.bind(this)
                    }
                    );
        return;
    },
    
    msgreply: function(buttonid)
    {
        msgid   = buttonid.split('-')[1];

        this.showInformation(msgid, loadingReply, 0, this.WORKING);

        pars = 'module=InterCom&func=replyfrom' + this.boxtype + '&msgid=' + msgid;
        myAjax = new Ajax.Request(
                    document.location.pnbaseURL + "ajax.php",
                    {
                        method: 'post',
                        parameters: pars,
                        onSuccess: function (transport) {
                            this.clearInformation(msgid);
                            // we cannot use transport.headerJSON.data here as prototype has a bug, 
                            // it cannot deal with looooong jsonized string like compiled templates. 
                            // this sucks.
                            json = pndejsonize(transport.responseText);
                            $('msgaction-' + msgid).update(json.data);
                            $('msgaction-' + msgid).removeClassName('invisible');
                            
                            // make bbcode and bbsmile visible if they exist
                            $$('.bbcode').each(function(el) { el.removeClassName('hidden'); });
                            $$('.bbsmile_smilies').each(function(el) {
                                el.removeClassName('bbsmile_smilies');
                            });
                            if($('smiliemodal')) {
                                new Control.Modal($('smiliemodal'), {});
                            }

                            ta = $('ic-ajaxreplymessage-' + msgid);
                            new Texpand(ta, {autoShrink: true, shrinkOnBlur:false, expandOnFocus: false, expandOnLoad: true });

                            // set focus on textarea when return is pressed in the subject
                            $('ic-ajaxreplysubject-' + msgid).observe('keypress', function(event) {
                                if (event.keyCode == Event.KEY_RETURN) {
                                    event.stop();
                                    $('ic-ajaxreplymessage-' + msgid).focus();
                                }
                                return false;
                            });

                            Event.observe($('msg-sendreply-'+msgid), 'click', this.sendreply.bindAsEventListener(this, msgid, this.boxtype)); 
                            Event.observe($('msg-cancelreply-'+msgid), 'click', this.cancelreply.bindAsEventListener(this, msgid)); 
                        }.bind(this),
                        onFailure: function (transport) {
                            this.clearInformation(msgid);
                            this.showInformation(msgid, userdeleted, 0, this.ERROR);
                        }.bind(this)
                    }
                    );
        return;
    },
    
    sendreply: function(el, msgid, boxtype)
    {
        message = $('ic-ajaxreplymessage-' + msgid).value;
        if(message == '') {
            this.showInformation(msgid, normessagefound, 0, this.ERROR);
            return false;
        }

        subject = $('ic-ajaxreplysubject-' + msgid).value;
        if(subject == '') {
            this.showInformation(msgid, nosubjectfound, 0, this.ERROR);
            return false;
        }

        this.showInformation(msgid, storingReply, 0, this.WORKING);

        pars = 'module=InterCom&func=sendreply&replyto=' + msgid + '&boxtype=' + boxtype + '&message=' + encodeURIComponent(message) + '&subject=' + encodeURIComponent(subject);
        myAjax = new Ajax.Request(
                    document.location.pnbaseURL + "ajax.php",
                    {
                        method: 'post',
                        parameters: pars,
                        onSuccess: function (transport) {
                            this.clearInformation(msgid);
                            $('msgaction-' + msgid).update('&nbsp;');
                            $('msgaction-' + msgid).addClassName('invisible');
                            $('msg-read-' + msgid).addClassName('invisible');
                            $('msg-answered-' + msgid).removeClassName('invisible');
                            this.showInformation(msgid, messageposted, 5000, this.INFO);
                        }.bind(this)
                    }
                    );
        return;

    },
    
    cancelreply: function(el, msgid)
    {
       msgaction = $('msgaction-' + msgid);
       msgaction.addClassName('invisible');
       msgaction.update('&nbsp;');
       this.clearInformation(msgid);
    },

    msgforward: function(buttonid)
    {
        msgid   = buttonid.split('-')[1];
        
        this.showInformation(msgid, loadingForward, 0, this.WORKING);
        
        pars = 'module=InterCom&func=forwardfrom' + this.boxtype + '&msgid=' + msgid;
        myAjax = new Ajax.Request(
                    document.location.pnbaseURL + "ajax.php",
                    {
                        method: 'post',
                        parameters: pars,
                        onSuccess: function (transport) {
                            this.clearInformation(msgid);

                            // we cannot use transport.headerJSON.data here as prototype has a bug, 
                            // it cannot deal with looooong jsonized string like compiled templates. 
                            // this sucks.
                            json = pndejsonize(transport.responseText);
                            $('msgaction-' + msgid).update(json.data);
                            $('msgaction-' + msgid).removeClassName('invisible');


                            // make bbcode and bbsmile visible if they exist
                            $$('.bbcode').each(function(el) { el.removeClassName('hidden'); });
                            $$('.bbsmile_smilies').each(function(el) {
                                el.removeClassName('bbsmile_smilies');
                            });
                            if($('smiliemodal')) {
                                new Control.Modal($('smiliemodal'), {});
                            }

                            var ta = $('ic-ajaxforwardmessage-' + msgid);
                            new Texpand(ta, {autoShrink: true, shrinkOnBlur:false, expandOnFocus: false, expandOnLoad: true });

                            // set focus on textarea when return is pressed in the subject
                            $('ic-ajaxforwardsubject-' + msgid).observe('keypress', function(event) {
                                if (event.keyCode == Event.KEY_RETURN) {
                                    event.stop();
                                    $('ic-ajaxforwardmessage-' + msgid).focus();
                                }
                                return false;
                            });

                            Event.observe($('msg-sendforward-'+msgid), 'click', this.sendforward.bindAsEventListener(this, msgid)); 
                            Event.observe($('msg-cancelforward-'+msgid), 'click', this.cancelforward.bindAsEventListener(this, msgid)); 

                        }.bind(this)
                    }
                    );
        return;
    },

    sendforward: function(el, msgid)
    {
        message = $('ic-ajaxforwardmessage-' + msgid).value;
        if(message == '') {
            this.showInformation(msgid, normessagefound, 0, this.ERROR);
            return false;
        }

        subject = $('ic-ajaxforwardsubject-' + msgid).value;
        if(subject == '') {
            this.showInformation(msgid, nosubjectfound, 0, this.ERROR);
            return false;
        }

        forwardto = $('username').value;
        if(forwardto == '') {
            this.showInformation(msgid, norecipientfound, 0, this.ERROR);
            return false;
        }

        this.showInformation(msgid, storingForward, 0, this.WORKING);

        pars = 'module=InterCom&func=sendforward&forwardto=' + forwardto + '&message=' + encodeURIComponent(message) + '&subject=' + encodeURIComponent(subject);
        myAjax = new Ajax.Request(
                    document.location.pnbaseURL + "ajax.php",
                    {
                        method: 'post',
                        parameters: pars,
                        onSuccess: function (transport) {
                            this.clearInformation(msgid);
                            $('msgaction-' + msgid).update('&nbsp;');
                            $('msgaction-' + msgid).addClassName('invisible');
                            this.showInformation(msgid, messageposted, 5000, this.INFO);
                            /*
                            $('msg-read-' + msgid).addClassName('invisible');
                            $('msg-answered-' + msgid).removeClassName('invisible');
                            */
                        }.bind(this)
                    }
                    );
        return;

    },
    
    cancelforward: function(el, msgid)
    {
       msgaction = $('msgaction-' + msgid);
       msgaction.addClassName('invisible');
       msgaction.update('&nbsp;');
       this.clearInformation(msgid);
    },

    removefromview: function(msgid, transport)
    {
        // remove the message body
        $('msgbody-' + msgid).remove(); 
        // remove the message header
        $('msgheader-' + msgid).remove(); 
        this.odd = true;
        $$('.msg_line').each(
            function(node)
            {
                node.removeClassName('odd');
                node.removeClassName('even');
            
                if(this.odd == true) {
                    node.addClassName('odd');
                } else {
                    node.addClassName('even');
                }
                this.odd = !this.odd;
            }.bind(this)
        );
        if($('ic-totalin')) {
            $('ic-totalin').update(transport.headerJSON.totalin);
        }
        if($('ic-totalout')) {
            $('ic-totalout').update(transport.headerJSON.totalout);
        }
        if($('ic-totalarchive')) {
            $('ic-totalarchive').update(transport.headerJSON.totalarchive);
        }
    },

    showInformation: function(msgid, infotext, showdelay, msgtype)
    {
        if($('information-' + msgid)) {
            infelement = $('information-' + msgid);
            infelement.removeClassName('ic-informationtext-working').removeClassName('ic-informationtext-error').removeClassName('ic-informationtext-info');
            if (msgtype == this.ERROR) {
                infelement.addClassName('ic-informationtext-error');
                infelement.update(infotext);
                infelement.show();
                new Effect.Pulsate(inftextelement, { pulses: 10, duration: 3 });
            } else if (msgtype == this.INFO) {
                infelement.addClassName('ic-informationtext-info');
                infelement.update(infotext);
                infelement.show();
            } else if (msgtype == this.WORKING) {
                infelement.addClassName('ic-informationtext-working');
                infelement.update(infotext);
                infelement.show();
            } 
            if (showdelay != 0) {
                window.setTimeout(
                    function() {
                        new Effect.Fade(infelement);
                    },
                    showdelay
                );
            }
            return true;
        }
    },
    
    clearInformation: function(msgid)
    {
        if($('information-' + msgid)) {
            $('information-' + msgid).update("&nbsp;");
            $('information-' + msgid).hide();
        }
    }
    
};

function CheckAll() {
    $$('.msg_check input[type="checkbox"]').each(function(el) { el.checked = $('allbox').checked; });
}

function CheckCheckAll() {
    $('allbox').checked = ($$('.msg_check input:checked').length == $$('.msg_check input[type="checkbox"]').length -1) & !$('allbox').checked;
}

function base64_decode( data ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Tyler Akins (http://rumkin.com)
    // +   improved by: Thunder.m
    // +      input by: Aman Gupta
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // -    depends on: utf8_decode
    // *     example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
    // *     returns 1: 'Kevin van Zonneveld'
 
    // mozilla has this native
    // - but breaks in 2.0.0.12!
    //if (typeof window['btoa'] == 'function') {
    //    return btoa(data);
    //}
 
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = ac = 0, dec = "", tmp_arr = [];
 
    data += '';
 
    do {  // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));
 
        bits = h1<<18 | h2<<12 | h3<<6 | h4;
 
        o1 = bits>>16 & 0xff;
        o2 = bits>>8 & 0xff;
        o3 = bits & 0xff;
 
        if (h3 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1);
        } else if (h4 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1, o2);
        } else {
            tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
        }
    } while (i < data.length);
 
    dec = tmp_arr.join('');

    return dec;
}
