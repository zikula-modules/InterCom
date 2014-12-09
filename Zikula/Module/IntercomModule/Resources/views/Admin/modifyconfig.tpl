{adminheader}
<h3>
    <span class="fa fa-wrench"></span>
    {gt text="Settings"}
</h3>
<div>
<form class="form-horizontal" role="form" action="{route name='zikulaintercommodule_admin_preferences'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
    <div class="row">
    <div class="col-lg-6">        
    <div class="panel panel-success">
        <div class="panel-heading">{gt text="General settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">
        <label class="control-label" for="active">{gt text="Enable private messaging"}</label>
        <input id="active" name="active" type="checkbox" value="{*$active*}" />        
        <label class="control-label" for="maintain">{gt text="Information message"}</label>
        <textarea class="form-control" id="maintain" name="maintain" type="textarea" value="{*$maintain*}" rows="3"></textarea>
        <p class="help-block">{gt text="Message to display when private messaging is disabled"}</p>
        </div>
    </div>
    </div>
    </div>
    <div class="col-lg-6">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Limitations"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">

        <label class="control-label" for="limitinbox">{gt text="Maximum number of messages in inbox"}</label>
        <input type="text" id="limitinbox" />

        <label class="control-label" for="limitoutbox">{gt text="Maximum number of messages in outbox"}</label>
        <input type="text" id="limitoutbox"/>

        <label class="control-label" for="limitarchive">{gt text="Maximum number of messages in archive"}</label>
        <input type="text" id="limitarchive"/>

        <label class="control-label" for="perpage">{gt text="Messages per page"}</label>
        <input type="text" id="perpage"/>
        
        <label class="control-label" for="allowhtml">{gt text="Allow HTML mark-up in messages"}</label>
        <input id="allowhtml" name="allowhtml" type="checkbox" value="{*$allowhtml*}" /> 
        
        <label class="control-label" for="allowsmilies">{gt text="Allow Smilies in Messages?"}</label>
        <input id="allowsmilies" name="allowsmilies" type="checkbox" value="{*$allowsmilies*}" /> 
        
        <label class="control-label" for="disable_ajax">{gt text="Disable ajax"}</label>
        <input id="active" name="disable_ajax" type="checkbox" value="{*$disable_ajax*}" /> 
        </div>
    </div>
    </div>
    </div>
    <div class="col-lg-6">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Announcement settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">
        <label class="control-label" for="userprompt_display">{gt text="Display announcement"}</label>
        <input id="userprompt_display" name="userprompt_display" type="checkbox" value="{*$userprompt_display*}" />
        <label class="control-label" for="userprompt">{gt text="Content"}</label>
        <textarea class="form-control" id="userprompt" name="userprompt" type="textarea" value="{*$userprompt*}" rows="3"></textarea>
        <p class="help-block">{gt text="Notice: This message will be displayed above each user's inbox. You can post all kinds of information intended for your users."}</p>        
        </div>
    </div>
    </div>
    </div>
    <div class="col-lg-6">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Notification settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">
        <label class="control-label" for="allow_emailnotification">{gt text="Allow e-mail notifications"}</label>
        <input id="allow_emailnotification" name="allow_emailnotification" type="checkbox" value="{*$allow_emailnotification*}" />
        <label class="control-label" for="force_emailnotification">{gt text="Activate e-mail notifications for new users"}</label>
        <input id="force_emailnotification" name="force_emailnotification" type="checkbox" value="{*$force_emailnotification*}" />        
        <p class="help-block">{gt text="Notice: To activate the sending of e-mail notifications for new users, the 'InterCom' module hook has to be enabled for the 'Users' module. This also activates the sending of a welcome message to new users (refer to the setting below)."}</p>       
        <label class="control-label" for="mailsubject">{gt text="Subject line"}</label> 
        <input id="mailsubject" name="mailsubject" type="text" value="{*$mailsubject*}"/> 
        <label class="control-label" for="fromname">{gt text="Sender"}</label>
         <input id="fromname" name="fromname" type="text" value="{*$fromname*}"/>  
         <p class="help-block">{gt text="Notice: If you leave the 'Sender' box blank then the site name will be used automatically."}</p>
        <label class="control-label" for="from_email">{gt text="Sender address"} </label>
        <input id="from_email" name="from_email" type="text" value="{*$from_email*}"/> 
        <p class="help-block">{gt text="Notice: If you leave the 'Sender address' box blank then the administrator's address will be used automatically."}</p>     
        </div>
    </div>
    </div>
    </div>
    <div class="col-lg-6">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Automatic response settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">
        <label class="control-label" for="allow_autoreply">{gt text="Enable automatic responses"}</label>
        <input id="allow_autoreply" name="allow_autoreply" type="checkbox" value="{*$allow_autoreply*}" />
        <p class="help-block">{gt text="Notice: When the automatic response feature, users can enter a message to be sent as an automatic response to all incoming private messages."}</p>        
        </div>
    </div>
    </div>
    </div>
    <div class="col-lg-6">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Welcome message settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">                          
        <label class="control-label" for="welcomemessage_send">{gt text="Send a welcome message to new users"}</label>
        <input id="welcomemessage_send" name="welcomemessage_send" type="checkbox" value="{*$welcomemessage_send*}" />
        <label class="control-label" for="welcomemessagesender">{gt text="Sender of welcome message"}</label>
        <input id="welcomemessagesender" type="text" value="{*$mailsubject*}"/>
        <div class="z-formnote z-informationmsg">{gt text="Notice: The welcome message sender must be one of the site's registered users."}</div>        
        <label class="control-label" for="welcomemessagesubject">{gt text="Welcome message subject line"}</label>
        <input id="welcomemessagesubject" type="text" value="{*$mailsubject*}"/>
        <label class="control-label" for="welcomemessage">{gt text="Welcome message text"}</label>
        <textarea class="form-control" id="welcomemessage" name="welcomemessage" type="textarea" value="{*$welcomemessage*}" rows="3"></textarea>        
        {*if $intlwelcomemessage neq ""*}
        <label class="control-label" for="intlwelcomemessage">{gt text="Welcome message for selected language"}</label>
        <textarea class="form-control" id="intlwelcomemessage" name="intlwelcomemessage" type="textarea" value="{*$intlwelcomemessage*}" rows="3"></textarea>        
        <div class="z-formnote z-informationmsg">{gt text="Notice: The following place holders are supported:<ul><li>%username% for the person's user name</li><li>%realname% for the person's real name</li><li>%sitename% for the site name</li></ul>If the text begins with an underscore ('_'), it will be processed like a language define. The language define should be placed in 'modules/InterCom/pnlang/xxx/welcome.php' (where 'xxx' is the language code)."}</p>            
        {*/if*}
        <label class="control-label" for="savewelcomemessage">{gt text="Save welcome message in user outbox"}</label>
        <input id="savewelcomemessage" name="savewelcomemessage" type="checkbox" value="{*$savewelcomemessage*}" />
        </div>
    </div>
    </div>
    </div>
    <div class="col-lg-6">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Spam prevention settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">
        <label class="control-label" for="protection_on">{gt text="Enable spam prevention"}</label>
        <input id="protection_on" name="protection_on" type="checkbox" value="{*$protection_on*}" />
        <label class="control-label" for="protection_time">{gt text="Measured time span (in minutes)"}</label>
        <input id="protection_time" name="protection_time" type="text" value="{*$protection_time*}"/>

        <label class="control-label" for="protection_amount">{gt text="Measured number of messages"}</label>
        <input id="protection_amount" name="protection_amount" type="text" value="{*$protection_amount*}"/>

        <label class="control-label" for="protection_mail">{gt text="Send admin notification of spam messaging via e-mail"}</label>
        <input id="protection_mail" name="protection_mail" type="checkbox" value="{*$protection_mail*}" />
            {gt text="Notice: With the spam prevention feature, you can specify the number of messages that a user can send within a certain time span before the spam prevention feature is triggered. When a message is send to multiple recipients, each recipient is counted as one message."}
    
        </div>
    </div>
    </div>
    </div>        
    </div>         
    <div class="row">            
        <div class="form-group pull-right">
                <div class="col-lg-12">
                    <button class="btn btn-default" title="{gt text="Save"}">
                        <span class="fa fa-save"></span> {gt text="Save"}
                    </button>
                    <a class="btn btn-link" href="{*route name='kaikmediazikulainformatormodule_admin_index' *}" title="{gt text="Cancel"}">
                    <span class="fa fa-remove"></span> {gt text="Cancel"}
                    </a>
                </div>
        </div>
    </div>
</form>
</div>
<script type="text/javascript">
    /* <![CDATA[ */
    $('configmaintenance').hide();
    $('active').observe('click', icCheckMaintenanceEntry);
    $('active').observe('keyup', icCheckMaintenanceEntry);
    icCheckMaintenanceEntry();

    $('configuserprompt').hide();
    $('userprompt_display').observe('click', icCheckUserpromptEntry);
    $('userprompt_display').observe('keyup', icCheckUserpromptEntry);
    icCheckUserpromptEntry();

    $('configemailnotification').hide();
    $('allow_emailnotification').observe('click', icCheckMailNotificationEntry);
    $('allow_emailnotification').observe('keyup', icCheckMailNotificationEntry);
    icCheckMailNotificationEntry();

    $('configwelcome').hide();
    $('welcomemessage_send').observe('click', icCheckWelcomeEntry);
    $('welcomemessage_send').observe('keyup', icCheckWelcomeEntry);
    icCheckWelcomeEntry();

    $('configspam').hide();
    $('protection_on').observe('click', icCheckSpamEntry);
    $('protection_on').observe('keyup', icCheckSpamEntry);
    icCheckSpamEntry();

    function icCheckMaintenanceEntry() {
        if ($('active').checked == false) {
            Effect.BlindDown('configmaintenance');
        } else {
            Effect.BlindUp('configmaintenance');
        }
    }

    function icCheckUserpromptEntry() {
        if ($('userprompt_display').checked == true) {
            Effect.BlindDown('configuserprompt');
        } else {
            Effect.BlindUp('configuserprompt');
        }
    }

    function icCheckMailNotificationEntry() {
        if ($('allow_emailnotification').checked == true) {
            Effect.BlindDown('configemailnotification');
        } else {
            Effect.BlindUp('configemailnotification');
        }
    }

    function icCheckWelcomeEntry() {
        if ($('welcomemessage_send').checked == true) {
            Effect.BlindDown('configwelcome');
        } else {
            Effect.BlindUp('configwelcome');
        }
    }

    function icCheckSpamEntry() {
        if ($('protection_on').checked == true) {
            Effect.BlindDown('configspam');
        } else {
            Effect.BlindUp('configspam');
        }
    }


    /* ]]> */
</script>                   
{adminfooter}