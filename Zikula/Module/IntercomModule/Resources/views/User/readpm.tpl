{* $Id$ *}
{if $message.inbox eq 1}
{gt text="Received message" assign=ictitle}
{else}
{gt text="Message send" assign=ictitle}
{/if} 

{include file="User/header.tpl" ictitle=$ictitle}

<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
            {$ictitle}
           {if $message.inbox eq 1}
            {gt text="from"} <strong>{$message.sender.uname}</strong> 
            {else}
            {gt text="to"} <strong>{$message.recipient.uname}</strong> 
            {/if}      
      <span class="pull-right">{gt text="Date"}: {$message.send|dateformat:"datetimebrief"}</span>
  </div>
  <div class="panel-body">
        <div class=" col-lg-12">
            <p><strong>{$message.subject}</strong></p>
        </div>
        <div class=" col-lg-12">          
        {$message.text}
        </div>
            {*if $message.signature != ""}<div class="signature z-formnote">{$message.signature|safehtml|nl2br}{* {$message.signature|safehtml|nl2br} }</div>{/if*}
    </div>     
    <div class="panel-footer">
        <div class="btn-group">
        <a class="btn btn-default btn-sm " role="button" id="reply-{$message.id}"   href="{route name='zikulaintercommodule_user_message' mode='reply' id=$message.id}"      title="{gt text='Reply'}"><i class="fa fa-reply"></i></a>
        <a class="btn btn-default btn-sm " role="button" id="forward-{$message.id}" href="{route name='zikulaintercommodule_user_message' mode='forward'  id=$message.id}"    title="{gt text='Forward'}"><i class="fa fa-forward"></i></a>
        <a class="btn btn-default btn-sm " role="button" id="store-{$message.id}"   href="{route name='zikulaintercommodule_user_message' mode='store' id=$message.id}" title="{gt text='Save'}"><i class="fa fa-save"></i></a>
        <a class="btn btn-default btn-sm " role="button" id="print-{$message.id}"   href="{route name='zikulaintercommodule_user_message' mode='read' id=$message.id theme=printer}" title="{gt text='Print'}"><i class="fa fa-print"></i></a>
        <a class="btn btn-default btn-sm " role="button" id="delete-{$message.id}"  href="{route name='zikulaintercommodule_user_message' mode='delete' id=$message.id}" title="{gt text='Delete'}"><i class="fa fa-trash"></i></a>
        </div>
    </div> 
</div>  

{*modavailable modname="ContactList" assign="ContactListInstalled"}
{if $ContactListInstalled}
{modapifunc modname="ContactList" type="user" func="getFOAFLink" uid1=$message.to_userid uid2=$message.from_userid}
{/if*}

{include file="User/footer.tpl"}
