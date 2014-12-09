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
        <label class="control-label" for="messages_active">{gt text="Enable private messaging"}</label>
        <input id="messages_active" name="messages_active" type="checkbox" value="{*$messages_active*}" />        
        <label class="control-label" for="messages_maintain">{gt text="Information message"}</label>
        <textarea class="form-control" id="messages_maintain" name="messages_maintain" type="textarea" value="{*$messages_maintain*}" rows="3"></textarea>
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

        <label class="control-label" for="messages_limitinbox">{gt text="Maximum number of messages in inbox"}</label>
        <input type="text" id="messages_limitinbox" />

        <label class="control-label" for="messages_limitoutbox">{gt text="Maximum number of messages in outbox"}</label>
        <input type="text" id="messages_limitoutbox"/>

        <label class="control-label" for="messages_limitarchive">{gt text="Maximum number of messages in archive"}</label>
        <input type="text" id="messages_limitarchive"/>

        <label class="control-label" for="messages_perpage">{gt text="Messages per page"}</label>
        <input type="text" id="messages_perpage"/>
        
        <label class="control-label" for="messages_allowhtml">{gt text="Allow HTML mark-up in messages"}</label>
        <input id="messages_allowhtml" name="messages_allowhtml" type="checkbox" value="{*$messages_allowhtml*}" /> 
        
        <label class="control-label" for="messages_allowsmilies">{gt text="Allow Smilies in Messages?"}</label>
        <input id="messages_allowsmilies" name="messages_allowsmilies" type="checkbox" value="{*$messages_allowsmilies*}" /> 
        
        <label class="control-label" for="disable_ajax">{gt text="Disable ajax"}</label>
        <input id="messages_active" name="disable_ajax" type="checkbox" value="{*$disable_ajax*}" /> 
        </div>
    </div>
    </div>
    </div>
    <div class="col-lg-6">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Announcement settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">
        <label class="control-label" for="messages_userprompt_display">{gt text="Display announcement"}</label>
        <input id="messages_userprompt_display" name="messages_userprompt_display" type="checkbox" value="{*$messages_userprompt_display*}" />
        <label class="control-label" for="messages_userprompt">{gt text="Content"}</label>
        <textarea class="form-control" id="messages_userprompt" name="messages_userprompt" type="textarea" value="{*$messages_userprompt*}" rows="3"></textarea>
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
        <label class="control-label" for="messages_allow_emailnotification">{gt text="Allow e-mail notifications"}</label>
        <input id="messages_allow_emailnotification" name="messages_allow_emailnotification" type="checkbox" value="{*$messages_allow_emailnotification*}" />
        <label class="control-label" for="messages_force_emailnotification">{gt text="Activate e-mail notifications for new users"}</label>
        <input id="messages_force_emailnotification" name="messages_force_emailnotification" type="checkbox" value="{*$messages_force_emailnotification*}" />        
        <p class="help-block">{gt text="Notice: To activate the sending of e-mail notifications for new users, the 'InterCom' module hook has to be enabled for the 'Users' module. This also activates the sending of a welcome message to new users (refer to the setting below)."}</p>       
        <label class="control-label" for="messages_mailsubject">{gt text="Subject line"}</label> 
        <input id="messages_mailsubject" name="messages_mailsubject" type="text" value="{*$messages_mailsubject*}"/> 
        <label class="control-label" for="messages_fromname">{gt text="Sender"}</label>
         <input id="messages_fromname" name="messages_fromname" type="text" value="{*$messages_fromname*}"/>  
         <p class="help-block">{gt text="Notice: If you leave the 'Sender' box blank then the site name will be used automatically."}</p>
        <label class="control-label" for="messages_from_email">{gt text="Sender address"} </label>
        <input id="messages_from_email" name="messages_from_email" type="text" value="{*$messages_from_email*}"/> 
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
        <label class="control-label" for="messages_allow_autoreply">{gt text="Enable automatic responses"}</label>
        <input id="messages_allow_autoreply" name="messages_allow_autoreply" type="checkbox" value="{*$messages_allow_autoreply*}" />
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
        <label class="control-label" for="messages_welcomemessage_send">{gt text="Send a welcome message to new users"}</label>
        <input id="messages_welcomemessage_send" name="messages_welcomemessage_send" type="checkbox" value="{*$messages_welcomemessage_send*}" />
        <label class="control-label" for="messages_welcomemessagesender">{gt text="Sender of welcome message"}</label>
        <input id="messages_welcomemessagesender" type="text" value="{*$messages_mailsubject*}"/>
        <div class="z-formnote z-informationmsg">{gt text="Notice: The welcome message sender must be one of the site's registered users."}</div>        
        <label class="control-label" for="messages_welcomemessagesubject">{gt text="Welcome message subject line"}</label>
        <input id="messages_welcomemessagesubject" type="text" value="{*$messages_mailsubject*}"/>
        <label class="control-label" for="messages_welcomemessage">{gt text="Welcome message text"}</label>
        <textarea class="form-control" id="messages_welcomemessage" name="messages_welcomemessage" type="textarea" value="{*$messages_welcomemessage*}" rows="3"></textarea>        
        {*if $intlwelcomemessage neq ""*}
        <label class="control-label" for="messages_intlwelcomemessage">{gt text="Welcome message for selected language"}</label>
        <textarea class="form-control" id="messages_intlwelcomemessage" name="messages_intlwelcomemessage" type="textarea" value="{*$messages_intlwelcomemessage*}" rows="3"></textarea>        
        <div class="z-formnote z-informationmsg">{gt text="Notice: The following place holders are supported:<ul><li>%username% for the person's user name</li><li>%realname% for the person's real name</li><li>%sitename% for the site name</li></ul>If the text begins with an underscore ('_'), it will be processed like a language define. The language define should be placed in 'modules/InterCom/pnlang/xxx/welcome.php' (where 'xxx' is the language code)."}</p>            
        {*/if*}
        <label class="control-label" for="messages_savewelcomemessage">{gt text="Save welcome message in user outbox"}</label>
        <input id="messages_savewelcomemessage" name="messages_savewelcomemessage" type="checkbox" value="{*$messages_savewelcomemessage*}" />
        </div>
    </div>
    </div>
    </div>
    <div class="col-lg-6">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Spam prevention settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">
        <label class="control-label" for="messages_protection_on">{gt text="Enable spam prevention"}</label>
        <input id="messages_protection_on" name="messages_protection_on" type="checkbox" value="{*$messages_protection_on*}" />
        <label class="control-label" for="messages_protection_time">{gt text="Measured time span (in minutes)"}</label>
        <input id="messages_protection_time" name="messages_protection_time" type="text" value="{*$messages_protection_time*}"/>

        <label class="control-label" for="messages_protection_amount">{gt text="Measured number of messages"}</label>
        <input id="messages_protection_amount" name="messages_protection_amount" type="text" value="{*$messages_protection_amount*}"/>

        <label class="control-label" for="messages_protection_mail">{gt text="Send admin notification of spam messaging via e-mail"}</label>
        <input id="messages_protection_mail" name="messages_protection_mail" type="checkbox" value="{*$messages_protection_mail*}" />
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
    $('messages_active').observe('click', icCheckMaintenanceEntry);
    $('messages_active').observe('keyup', icCheckMaintenanceEntry);
    icCheckMaintenanceEntry();

    $('configuserprompt').hide();
    $('messages_userprompt_display').observe('click', icCheckUserpromptEntry);
    $('messages_userprompt_display').observe('keyup', icCheckUserpromptEntry);
    icCheckUserpromptEntry();

    $('configemailnotification').hide();
    $('messages_allow_emailnotification').observe('click', icCheckMailNotificationEntry);
    $('messages_allow_emailnotification').observe('keyup', icCheckMailNotificationEntry);
    icCheckMailNotificationEntry();

    $('configwelcome').hide();
    $('messages_welcomemessage_send').observe('click', icCheckWelcomeEntry);
    $('messages_welcomemessage_send').observe('keyup', icCheckWelcomeEntry);
    icCheckWelcomeEntry();

    $('configspam').hide();
    $('messages_protection_on').observe('click', icCheckSpamEntry);
    $('messages_protection_on').observe('keyup', icCheckSpamEntry);
    icCheckSpamEntry();

    function icCheckMaintenanceEntry() {
        if ($('messages_active').checked == false) {
            Effect.BlindDown('configmaintenance');
        } else {
            Effect.BlindUp('configmaintenance');
        }
    }

    function icCheckUserpromptEntry() {
        if ($('messages_userprompt_display').checked == true) {
            Effect.BlindDown('configuserprompt');
        } else {
            Effect.BlindUp('configuserprompt');
        }
    }

    function icCheckMailNotificationEntry() {
        if ($('messages_allow_emailnotification').checked == true) {
            Effect.BlindDown('configemailnotification');
        } else {
            Effect.BlindUp('configemailnotification');
        }
    }

    function icCheckWelcomeEntry() {
        if ($('messages_welcomemessage_send').checked == true) {
            Effect.BlindDown('configwelcome');
        } else {
            Effect.BlindUp('configwelcome');
        }
    }

    function icCheckSpamEntry() {
        if ($('messages_protection_on').checked == true) {
            Effect.BlindDown('configspam');
        } else {
            Effect.BlindUp('configspam');
        }
    }


    /* ]]> */
</script>                   
{adminfooter}