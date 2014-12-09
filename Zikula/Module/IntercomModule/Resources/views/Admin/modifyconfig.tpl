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
        
        
        <label class="control-label" for="messages_userprompt">{gt text="Content"}</label>
        <textarea class="form-control" id="messages_userprompt" name="messages_userprompt" type="textarea" value="{*$messages_userprompt*}" rows="3"></textarea>
        <p class="help-block">{gt text="Notice: This message will be displayed above each user's inbox. You can post all kinds of information intended for your users."}</p>        
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
        <label class="control-label" for="messages_allow_emailnotification">{gt text="Allow e-mail notifications"}</label>
        <input id="messages_allow_emailnotification" name="messages_allow_emailnotification" type="checkbox" value="{*$messages_allow_emailnotification*}" />
        <label class="control-label" for="messages_force_emailnotification">{gt text="Activate e-mail notifications for new users"}</label>
        <input id="messages_force_emailnotification" name="messages_force_emailnotification" type="checkbox" value="{*$messages_force_emailnotification*}" />        
        <p class="help-block">{gt text="Notice: To activate the sending of e-mail notifications for new users, the 'InterCom' module hook has to be enabled for the 'Users' module. This also activates the sending of a welcome message to new users (refer to the setting below)."}</p>       
        
        
        <label class="control-label" for="messages_userprompt">{gt text="Content"}</label>
        <textarea class="form-control" id="messages_userprompt" name="messages_userprompt" type="textarea" value="{*$messages_userprompt*}" rows="3"></textarea>
        <p class="help-block">{gt text="Notice: This message will be displayed above each user's inbox. You can post all kinds of information intended for your users."}</p>        
        </div>
    </div>
    </div>
    </div>
    <div class="col-lg-6">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Spam prevention settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">
        <label class="control-label" for="messages_allow_emailnotification">{gt text="Allow e-mail notifications"}</label>
        <input id="messages_allow_emailnotification" name="messages_allow_emailnotification" type="checkbox" value="{*$messages_allow_emailnotification*}" />
        <label class="control-label" for="messages_force_emailnotification">{gt text="Activate e-mail notifications for new users"}</label>
        <input id="messages_force_emailnotification" name="messages_force_emailnotification" type="checkbox" value="{*$messages_force_emailnotification*}" />        
        <p class="help-block">{gt text="Notice: To activate the sending of e-mail notifications for new users, the 'InterCom' module hook has to be enabled for the 'Users' module. This also activates the sending of a welcome message to new users (refer to the setting below)."}</p>       
        
        
        <label class="control-label" for="messages_userprompt">{gt text="Content"}</label>
        <textarea class="form-control" id="messages_userprompt" name="messages_userprompt" type="textarea" value="{*$messages_userprompt*}" rows="3"></textarea>
        <p class="help-block">{gt text="Notice: This message will be displayed above each user's inbox. You can post all kinds of information intended for your users."}</p>        
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