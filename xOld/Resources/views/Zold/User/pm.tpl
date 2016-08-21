{include file="User/header.tpl" ictitle=$ictitle}

{include file="User/previewpm.tpl"}

<form class="form" action="{route name="zikulaintercommodule_user_message" mode=$mode}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />        
        <input type="hidden" name="id" value="{if isset($id)}{$id}{/if}" />
        <input type="hidden" name="sender" value="{if isset($sender)}{$sender.uid}{else}{$currentuid}{/if}" />
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading"><i class="fa fa-file-o"> </i>   {$ictitle}</div>
  <div class="panel-body">        
        <div class="form-group col-lg-12 {if isset($errors.recipient)}has-error{/if}">
        <label class="control-label col-lg-12" for="recipient">{gt text="Individual recipient(s)"}</label>
        <div class="col-lg-12">
        <input class="form-control input-sm" type="text" id="recipient" name="recipients[names]"  size="5" type="text" value="{if isset($recipients.names)}{$recipients.names}{/if}"/>
        </div>
        {if isset($errors.recipient)}<p class="help-block col-lg-12">{$errors.recipient}</p>{/if}         
        <p class="help-block col-lg-12">{gt text="Notice: To send a private message to multiple individual recipients, enter their user names separated by commas."}</p>
        </div> 
        {*if $pmtype eq "new" && $msgtogroups eq true*}
        <div class="form-group col-lg-12 {if isset($errors.group)}has-error{/if}">
        <label class="control-label col-lg-12" for="group">{gt text="Group recipient(s)"}</label>
        <div class="col-lg-12">
        <input class="form-control input-sm" type="text" id="group" name="recipients[groups]"  size="5" type="text" value="{if isset($recipients.groups)}{$recipients.groups}{/if}"/>
        </div>
        {if isset($errors.group)}<p class="help-block col-lg-12">{$errors.group}</p>{/if}         
        <p class="help-block col-lg-12">{gt text="Notice: To send a private message to multiple groups, enter the group names separated by commas."}</p>
        </div>
            
        <div class="form-group col-lg-12 {if isset($errors.subject)}has-error{/if}">
        <label class="control-label col-lg-12" for="subject">{gt text="Subject line"}</label>
        <div class="col-lg-12">
        <input class="form-control input-sm" type="text" id="subject" name="subject"  size="5" type="text" value="{if isset($subject)}{$subject}{/if}"/>
        </div>
        {if isset($errors.subject)}<p class="help-block col-lg-12">{$errors.subject}</p>{/if}         
        <p class="help-block col-lg-12">{gt text="Notice: To send a private message to multiple groups, enter the group names separated by commas."}</p>
        </div> 
        <div class="form-group col-lg-12 {if isset($errors.text)}has-error{/if}">
        <label class="control-label col-lg-12" for="text">{gt text="Message text"}</label>
        <div class="col-lg-12">
        <textarea class="form-control" id="text" name="text" type="textarea">{if isset($text)}{$text}{/if}</textarea>
        </div>
        {if isset($errors.text)}<p class="help-block col-lg-12">{$errors.text}</p>{/if}
        </div>  
    </div>
    <div class="panel-footer clearfix">
        <div class="form-group col-lg-12">
        <div class="btn-group pull-right">
            <a title="{gt text='Cancel'}"  href="{route name='zikulaintercommodule_user_index'}"   class="btn btn-default btn-sm"><i class="fa fa-close"> {gt text="Cancel"}</i></a>
            <button title="{gt text="Preview"}" type="submit" name="action"   value="preview"   class="btn btn-default btn-sm"><i class="fa fa-eye"></i> {gt text="Preview"}</button>
            <button title="{gt text="Send"}"    type="submit" name="action"   value="send"   class="btn btn-default btn-sm"><i class="fa fa-send"></i> {gt text="Send"}</button>
        </div>
        </div>
    </div> 
    </div>
</form>

{include file="User/footer.tpl"}
