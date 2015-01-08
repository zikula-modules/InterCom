{adminheader}
<h3>
    <span class="fa fa-wrench"></span>
    {gt text="Settings"}
</h3>
<div>
<form class="form-horizontal" role="form" action="{route name='zikulaintercommodule_admin_preferences'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
    <div class="row">
    <div class="{if $active eq "0" } col-lg-12{else} col-lg-6{/if}">        
    <div class="panel {if $active eq "0" } panel-warning{else} panel-success{/if} ">
        <div class="panel-heading">{gt text="General settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12">    
        <label class="control-label col-lg-6" for="active">{gt text="Enable private messaging"}</label>      
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $active eq "1" } active{else}{/if}">
        <input id="active" name="active" type="radio" value="1" {if $active eq "1" } checked="checked"{else}{/if}> {gt text="On"}
         </label>
        <label class="btn btn-default btn-sm {if $active eq "0" } active{else}{/if}">
        <input id="active" name="active" type="radio" value="0" {if $active eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>      
        </div>
        </div>   
        <div class="form-group col-lg-12{if $active eq "1" } hide{else}{/if} {if isset($errors.maintain)}has-error{/if}">
        <label class="control-label col-lg-6" for="maintain">{gt text="Reason message"}</label>
        <textarea class="form-control col-lg-6" id="maintain" name="maintain" type="textarea" value="{$maintain}" rows="3"></textarea>
        {if isset($errors.layout)}<p class="help-block">{$errors.layout}</p>{/if} 
        <p class="help-block col-lg-12">{gt text="Message to display when private messaging is disabled"}</p>
        </div> 
        <div class="form-group col-lg-12 {if $active eq "0" } hide{else}{/if}"> 
        <label class="control-label col-lg-6" for="allowhtml">{gt text="Allow HTML mark-up in messages"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $allowhtml eq "1" } active{else}{/if}">
        <input id="allowhtml" name="allowhtml" type="radio" value="1" {if $allowhtml eq "1" } checked="checked"{else}{/if}> {gt text="On"}
         </label>
        <label class="btn btn-default btn-sm {if $allowhtml eq "0" } active{else}{/if}">
        <input id="allowhtml" name="allowhtml" type="radio" value="0" {if $allowhtml eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>
        </div>
        <div class="form-group col-lg-12 {if $active eq "0" } hide{else}{/if}"> 
        <label class="control-label col-lg-6" for="allowsmilies">{gt text="Allow Smilies in Messages?"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $allowsmilies eq "1" } active{else}{/if}">
        <input id="allowsmilies" name="allowsmilies" type="radio" value="1" {if $allowsmilies eq "1" } checked="checked"{else}{/if}> {gt text="On"}
         </label>
        <label class="btn btn-default btn-sm {if $allowsmilies eq "0" } active{else}{/if}">
        <input id="allowsmilies" name="allowsmilies" type="radio" value="0" {if $allowsmilies eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>        
        </div>
        <div class="form-group col-lg-12 {if $active eq "0" } hide{else}{/if}"> 
        <label class="control-label col-lg-6" for="disable_ajax">{gt text="Disable ajax"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $disable_ajax eq "1" } active{else}{/if}">
        <input id="disable_ajax" name="disable_ajax" type="radio" value="1" {if $disable_ajax eq "1" } checked="checked"{else}{/if}> {gt text="On"}
         </label>
        <label class="btn btn-default btn-sm {if $disable_ajax eq "0" } active{else}{/if}">
        <input id="disable_ajax" name="disable_ajax" type="radio" value="0" {if $disable_ajax eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>
        </div>
    </div>
    </div>
    </div>     
    <div class="col-lg-6 {if $active eq "0" } hide{else}{/if}">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Limitations"}</div>  
        <div class="panel-body">       
        <div class="form-group col-lg-12 {if isset($errors.limitinbox)}has-error{/if}">
        <label class="control-label col-lg-8" for="limitinbox">{gt text="Maximum number of messages in inbox"}</label>
        <div class="col-lg-4">
        <input class="form-control input-sm" type="text" id="limitinbox" name="limitinbox"  size="5" type="text" value="{$limitinbox}"/>
        </div>
        {if isset($errors.limitinbox)}<p class="help-block col-lg-12">{$errors.limitinbox}</p>{/if} 
        </div> 
        <div class="form-group col-lg-12 {if isset($errors.limitoutbox)}has-error{/if}">
        <label class="control-label col-lg-8" for="limitoutbox">{gt text="Maximum number of messages in outbox"}</label>
        <div class="col-lg-4">
        <input class="form-control input-sm" type="text" id="limitoutbox" name="limitoutbox" value="{$limitoutbox}"/>
        </div>
        {if isset($errors.limitoutbox)}<p class="help-block col-lg-12">{$errors.limitoutbox}</p>{/if} 
        </div>
        <div class="form-group col-lg-12 {if isset($errors.limitarchive)}has-error{/if}">
        <label class="control-label col-lg-8" for="limitarchive">{gt text="Maximum number of messages in archive"}</label>
        <div class="col-lg-4">
        <input class="form-control  input-sm" type="text" id="limitarchive" name="limitarchive" value="{$limitarchive}"/>
        </div>
        {if isset($errors.limitarchive)}<p class="help-block col-lg-12">{$errors.limitarchive}</p>{/if} 
        </div>
        <div class="form-group col-lg-12 {if isset($errors.perpage)}has-error{/if}">
        <label class="control-label col-lg-8" for="perpage">{gt text="Messages per page"}</label>
        <div class="col-lg-4">        
        <input class="form-control  input-sm" type="text" id="perpage"     name="perpage" value="{$perpage}"/>
        </div>
        {if isset($errors.perpage)}<p class="help-block col-lg-12">{$errors.perpage}</p>{/if} 
        </div>   
    </div>
    </div>
    </div>      
    <div class="col-lg-6 {if $active eq "0" } hide{else}{/if}">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Notification settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12"> 
        <label class="control-label col-lg-6" for="allow_emailnotification">{gt text="Allow e-mail notifications"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $allow_emailnotification eq "1" } active{else}{/if}">
        <input id="allow_emailnotification" name="allow_emailnotification" type="radio" value="1" {if $allow_emailnotification eq "1" } checked="checked"{else}{/if}> {gt text="On"}
         </label>
        <label class="btn btn-default btn-sm {if $allow_emailnotification eq "0" } active{else}{/if}">
        <input id="allow_emailnotification" name="allow_emailnotification" type="radio" value="0" {if $allow_emailnotification eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>
        </div>
        <div class="form-group col-lg-12 {if $allow_emailnotification eq "0" } hide{else}{/if}"> 
        <label class="control-label col-lg-6" for="force_emailnotification">{gt text="Activate e-mail notifications for new users"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $force_emailnotification eq "1" } active{else}{/if}">
        <input id="force_emailnotification" name="force_emailnotification" type="radio" value="1" {if $force_emailnotification eq "1" } checked="checked"{else}{/if}> {gt text="On"}
        </label>
        <label class="btn btn-default btn-sm {if $force_emailnotification eq "0" } active{else}{/if}">
        <input id="force_emailnotification" name="force_emailnotification" type="radio" value="0" {if $force_emailnotification eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>
        <p class="help-block col-lg-12">{gt text="Notice: To activate the sending of e-mail notifications for new users, the 'InterCom' module hook has to be enabled for the 'Users' module. This also activates the sending of a welcome message to new users (refer to the setting below)."}</p>
        </div>        
        <div class="form-group col-lg-12 {if isset($errors.mailsubject)}has-error{/if} {if $allow_emailnotification eq "0" } hide{else}{/if}">
        <label class="control-label col-lg-4" for="mailsubject">{gt text="Subject line"}</label>
        <div class="col-lg-8">        
        <input class="form-control  input-sm" type="text" id="mailsubject"     name="mailsubject" value="{$mailsubject}"/>
        </div>
        {if isset($errors.mailsubject)}<p class="help-block col-lg-12">{$errors.mailsubject}</p>{/if} 
        </div>  
        <div class="form-group col-lg-12 {if isset($errors.fromname)}has-error{/if} {if $allow_emailnotification eq "0" } hide{else}{/if}">
        <label class="control-label col-lg-4" for="fromname">{gt text="Sender"}</label>
        <div class="col-lg-8">        
        <input class="form-control  input-sm" type="text" id="fromname"     name="fromname" value="{$fromname}"/>
        </div>
        {if isset($errors.fromname)}<p class="help-block col-lg-12">{$errors.fromname}</p>{/if}
        <p class="help-block col-lg-12">{gt text="Notice: If you leave the 'Sender' box blank then the site name will be used automatically."}</p>           
        </div>       
        <div class="form-group col-lg-12 {if isset($errors.fromemail)}has-error{/if} {if $allow_emailnotification eq "0" } hide{else}{/if}">
        <label class="control-label col-lg-4" for="from_email">{gt text="Sender address"}</label>
        <div class="col-lg-8">        
        <input class="form-control  input-sm" type="text" id="from_email"     name="from_emaile" value="{$from_email}"/>
        </div>
        {if isset($errors.fromemail)}<p class="help-block col-lg-12">{$errors.fromemail}</p>{/if}
        <p class="help-block col-lg-12">{gt text="Notice: If you leave the 'Sender address' box blank then the administrator's address will be used automatically."}</p>     
        </div>     
    </div>
    </div>
    </div>
    <div class="col-lg-6 pull-right {if $active eq "0" } hide{else}{/if}">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Welcome message settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12"> 
        <label class="control-label col-lg-6" for="welcomemessage_send">{gt text="Send a welcome message to new users"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $welcomemessage_send eq "1" } active{else}{/if}">
        <input id="welcomemessage_send" name="welcomemessage_send" type="radio" value="1" {if $welcomemessage_send eq "1" } checked="checked"{else}{/if}> {gt text="On"}
        </label>
        <label class="btn btn-default btn-sm {if $welcomemessage_send eq "0" } active{else}{/if}">
        <input id="welcomemessage_send" name="welcomemessage_send" type="radio" value="0" {if $welcomemessage_send eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>
        </div>                          
        <div class="form-group col-lg-12 {if isset($errors.welcomemessagesender)}has-error{/if} {if $welcomemessage_send eq "0" } hide{else}{/if}">
        <label class="control-label col-lg-4" for="welcomemessagesender">{gt text="Sender of welcome message"}</label>
        <div class="col-lg-8">        
        <input class="form-control  input-sm" type="text" id="welcomemessagesender"     name="welcomemessagesender" value="{$welcomemessagesender}"/>
        </div>
        {if isset($errors.welcomemessagesender)}<p class="help-block col-lg-12">{$errors.welcomemessagesender}</p>{/if}
        <p class="help-block col-lg-12">{gt text="Notice: The welcome message sender must be one of the site's registered users."}</p>     
        </div>               
        <div class="form-group col-lg-12 {if isset($errors.welcomemessagesubject)}has-error{/if} {if $welcomemessage_send eq "0" } hide{else}{/if}">
        <label class="control-label col-lg-4" for="welcomemessagesubject">{gt text="Welcome message subject line"}</label>
        <div class="col-lg-8">        
        <input class="form-control  input-sm" type="text" id="welcomemessagesubject"     name="welcomemessagesubject" value="{$welcomemessagesubject}"/>
        </div>
        {if isset($errors.welcomemessagesubject)}<p class="help-block col-lg-12">{$errors.welcomemessagesubject}</p>{/if}
        </div>        
        <div class="form-group col-lg-12 {if isset($errors.welcomemessage)}has-error{/if} {if $welcomemessage_send eq "0" } hide{else}{/if}">
        <label class="control-label col-lg-4" for="welcomemessage">{gt text="Welcome message text"}</label>
        <div class="col-lg-8">
        <textarea class="form-control" id="welcomemessage" name="welcomemessage" type="textarea">{$welcomemessage}</textarea>
        </div>
        {if isset($errors.welcomemessage)}<p class="help-block col-lg-12">{$errors.welcomemessage}</p>{/if}
        </div>  
       <div class="form-group col-lg-12 {if $welcomemessage_send eq "0" } hide{else}{/if}">
        <label class="control-label col-lg-4" for="intlwelcomemessage">{gt text="Welcome message for selected language"}</label>
        <div class="col-lg-8">
        <textarea class="form-control" id="intlwelcomemessage" name="intlwelcomemessage" type="textarea">{$intlwelcomemessage}</textarea>
        </div>
        <p class="help-block col-lg-12">{gt text="Notice: The following place holders are supported:<ul><li>%username% for the person's user name</li><li>%realname% for the person's real name</li><li>%sitename% for the site name</li></ul>If the text begins with an underscore ('_'), it will be processed like a language define. The language define should be placed in 'modules/InterCom/pnlang/xxx/welcome.php' (where 'xxx' is the language code)."}</p>            
        </div>                     
        <div class="form-group col-lg-12 {if $welcomemessage_send eq "0" } hide{else}{/if}"> 
        <label class="control-label col-lg-6" for="savewelcomemessage">{gt text="Save welcome message in user outbox"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $savewelcomemessage eq "1" } active{else}{/if}">
        <input id="savewelcomemessage" name="savewelcomemessage" type="radio" value="1" {if $savewelcomemessage eq "1" } checked="checked"{else}{/if}> {gt text="On"}
        </label>
        <label class="btn btn-default btn-sm {if $savewelcomemessage eq "0" } active{else}{/if}">
        <input id="savewelcomemessage" name="savewelcomemessage" type="radio" value="0" {if $savewelcomemessage eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>
        </div>         
    </div>
    </div>
    </div>      
    <div class="col-lg-6 {if $active eq "0" } hide{else}{/if}">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Spam prevention settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12"> 
        <label class="control-label col-lg-6" for="protection_on">{gt text="Enable spam prevention"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $protection_on eq "1" } active{else}{/if}">
        <input id="protection_on" name="protection_on" type="radio" value="1" {if $protection_on eq "1" } checked="checked"{else}{/if}> {gt text="On"}
        </label>
        <label class="btn btn-default btn-sm {if $protection_on eq "0" } active{else}{/if}">
        <input id="protection_on" name="protection_on" type="radio" value="0" {if $protection_on eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>
        </div>             
        <div class="form-group col-lg-12 {if isset($errors.protection_time)}has-error{/if} {if $protection_on eq "0" } hide{else}{/if}">
        <label class="control-label col-lg-6" for="protection_time">{gt text="Measured time span (in minutes)"}</label>
        <div class="col-lg-4">        
        <input class="form-control  input-sm" type="text" id="protection_time"     name="protection_time" value="{$protection_time}"/>
        </div>
        {if isset($errors.protection_time)}<p class="help-block col-lg-12">{$errors.protection_time}</p>{/if}
        </div>         
        <div class="form-group col-lg-12 {if isset($errors.protection_amount)}has-error{/if} {if $protection_on eq "0" } hide{else}{/if}"">
        <label class="control-label col-lg-6" for="protection_amount">{gt text="Measured number of messages"}</label>
        <div class="col-lg-4">        
        <input class="form-control  input-sm" type="text" id="protection_amount"     name="protection_amount" value="{$protection_amount}"/>
        </div>
        {if isset($errors.protection_amount)}<p class="help-block col-lg-12">{$errors.protection_amount}</p>{/if}
        </div>
        <div class="form-group col-lg-12 {if $protection_on eq "0" } hide{else}{/if}"">
        <label class="control-label col-lg-6" for="protection_mail">{gt text="Send admin notification of spam messaging via e-mail"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $protection_mail eq "1" } hide{else}{/if}">
        <input id="protection_mail" name="protection_mail" type="radio" value="1" {if $protection_mail eq "1" } checked="checked"{else}{/if}> {gt text="On"}
        </label>
        <label class="btn btn-default btn-sm {if $protection_mail eq "0" } hide{else}{/if}">
        <input id="protection_mail" name="protection_mail" type="radio" value="0" {if $protection_mail eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>
        <p class="help-block col-lg-12">{gt text="Notice: With the spam prevention feature, you can specify the number of messages that a user can send within a certain time span before the spam prevention feature is triggered. When a message is send to multiple recipients, each recipient is counted as one message."}</p>
        </div>         
    </div>
    </div>
    </div>
    <div class="col-lg-6 {if $active eq "0" } hide{else}{/if}">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Announcement settings"}</div>  
        <div class="panel-body">           
        <div class="form-group col-lg-12"> 
        <label class="control-label col-lg-6" for="userprompt_display">{gt text="Display announcement"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $userprompt_display eq "1" } active{else}{/if}">
        <input id="userprompt_display" name="userprompt_display" type="radio" value="1" {if $userprompt_display eq "1" } checked="checked"{else}{/if}> {gt text="On"}
         </label>
        <label class="btn btn-default btn-sm {if $userprompt_display eq "0" } active{else}{/if}">
        <input id="userprompt_display" name="userprompt_display" type="radio" value="0" {if $userprompt_display eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>
        </div>
        <div class="form-group col-lg-12{if $userprompt_display eq "0" } hide{else}{/if}">
        <label class="control-label col-lg-4" for="userprompt">{gt text="Enter message"}</label>
        <div class="col-lg-8">
        <textarea class="form-control" id="userprompt" name="userprompt" type="textarea">{$userprompt}</textarea>
        </div>
        <p class="help-block col-lg-12">{gt text="Notice: This message will be displayed above each user's inbox. You can post all kinds of information intended for your users."}</p>
        </div>      
    </div>
    </div>
    </div>
    <div class="col-lg-6 {if $active eq "0" } hide{else}{/if}">        
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Automatic response settings"}</div>  
        <div class="panel-body">
        <div class="form-group col-lg-12"> 
        <label class="control-label col-lg-6" for="allow_autoreply">{gt text="Enable automatic responses"}</label>
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $allow_autoreply eq "1" } active{else}{/if}">
        <input id="allow_autoreply" name="allow_autoreply" type="radio" value="1" {if $allow_autoreply eq "1" } checked="checked"{else}{/if}> {gt text="On"}
        </label>
        <label class="btn btn-default btn-sm {if $allow_autoreply eq "0" } active{else}{/if}">
        <input id="allow_autoreply" name="allow_autoreply" type="radio" value="0" {if $allow_autoreply eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>
        </div>
        <p class="help-block col-lg-12">{gt text="Notice: When the automatic response feature, users can enter a message to be sent as an automatic response to all incoming private messages."}</p>
        </div>             
    </div>
    </div>
    </div>
    <div class="col-lg-6 {if $active eq "0" } hide{else}{/if}">         
    <div class="panel panel-default">
        <div class="panel-heading">{gt text="Mode"}</div>  
        <div class="panel-body"> 
        <div class="form-group col-lg-12">    
        <label class="control-label col-lg-6" for="mode">{gt text="Switch beetween modes"}</label>      
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $mode eq "0" } active{else}{/if}">
        <input id="mode" name="mode" type="radio" value="0" {if $mode eq "0" } checked="checked"{else}{/if}> {gt text="Generic"}
         </label>
        <label class="btn btn-default btn-sm {if $mode eq "1" } active{else}{/if}">
        <input id="mode" name="mode" type="radio" value="1" {if $mode eq "1" } checked="checked"{else}{/if}> {gt text="Conversation"}
        </label>      
        </div>
        <p class="help-block col-lg-12">{gt text="Here you can switch beetween InterCom working modes. Warning Conversation mode its still in beta stage"}</p>
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
                    <a class="btn btn-link" href="{route name='zikulaintercommodule_admin_index' }" title="{gt text="Cancel"}">
                    <span class="fa fa-remove"></span> {gt text="Cancel"}
                    </a>
                </div>
        </div>
    </div>
</form>
</div>                 
{adminfooter}