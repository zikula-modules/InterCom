{* $Id$ *}
{gt text="Messaging settings" assign=ictitle}
{include file="User/header.tpl" ictitle=$ictitle}
<form class="form" action="{route name="zikulaintercommodule_user_preferences"}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">{$ictitle}</div>
  <div class="panel-body">
    {if $modvars.ZikulaIntercomModule.allow_emailnotification eq true}      
        <div class="form-group col-lg-12">    
        <label class="control-label col-lg-6" for="ic_note">{gt text="Receive notification of new private messages via e-mail"}</label>      
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $ic_note eq "1" } active{else}{/if}">
        <input id="ic_ar" name="ic_note" type="radio" value="1" {if $ic_note eq "1" } checked="checked"{else}{/if}> {gt text="On"}
         </label>
        <label class="btn btn-default btn-sm {if $ic_note eq "0" } active{else}{/if}">
        <input id="ic_ar" name="ic_note" type="radio" value="0" {if $ic_note eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>      
        </div>
        <p class="help-block col-lg-12"><i class="fa fa-info-circle" ></i> {gt text="Notice: Here you can specify whether or not you want to be notified via an e-mail message each time you receive a private message in your private messaging mailbox on the site."}</p>
        </div>
    {/if}
    {if $modvars.ZikulaIntercomModule.allow_autoreply eq true}
        <div class="form-group col-lg-12">    
        <label class="control-label col-lg-6" for="ic_ar">{gt text="Send automatic responses"}</label>      
        <div class="btn-group col-lg-6" data-toggle="buttons">
        <label class="btn btn-default btn-sm {if $ic_ar eq "1" } active{else}{/if}">
        <input id="ic_ar" name="ic_ar" type="radio" value="1" {if $ic_ar eq "1" } checked="checked"{else}{/if}> {gt text="On"}
         </label>
        <label class="btn btn-default btn-sm {if $ic_ar eq "0" } active{else}{/if}">
        <input id="ic_ar" name="ic_ar" type="radio" value="0" {if $ic_ar eq "0" } checked="checked"{else}{/if}> {gt text="Off"}
        </label>      
        </div>
        <p class="help-block col-lg-12"><i class="fa fa-info-circle" ></i> {gt text="Notice: Here you can specify whether or not you want an automatic response to be sent to the sender each time you receive a private message (possibly useful when you are on holiday, for instance)."}</p>
        </div>       
        <div class="form-group col-lg-12 {if isset($errors.ic_art)}has-error{/if}">
        <label class="control-label col-lg-12" for="ic_art">{gt text="Welcome message text"}</label>
        <div class="col-lg-12">
        <textarea class="form-control" id="ic_art" name="ic_art" type="textarea">{$ic_art}</textarea>
        </div>
        {if isset($errors.ic_art)}<p class="help-block col-lg-12">{$errors.ic_art}</p>{/if}
        </div> 
    {/if}
    </div>
     {if $modvars.ZikulaIntercomModule.allow_emailnotification eq false && $modvars.ZikulaIntercomModule.allow_autoreply eq false}
    <div class="alert alert-warning">{gt text="Sorry! This feature has been disabled by the site administrator."}</div>
    {else}
    <div class="panel-footer clearfix">
        <div class="form-group col-lg-12">
        <div class="btn-group pull-right">
            <a title="{gt text='Cancel'}"  href="{route name='zikulaintercommodule_user_index'}"   class="btn btn-default btn-sm"><i class="fa fa-close"> {gt text="Cancel"}</i></a>
            <button title="{gt text="Save"}"    type="submit" name="send" class="btn btn-default btn-sm"><i class="fa fa-save"></i> {gt text="Save"}</button>
        </div>
        </div>
    </div> 
    {/if}
    </div>
</form>
{include file="User/footer.tpl"}
