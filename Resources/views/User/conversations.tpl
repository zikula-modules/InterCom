{include file="User/header.tpl" ictitle=$ictitle}
<form class="form" action="{route name="zikulaintercommodule_user_inbox"}" method="post" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
<div class="list-group">
{section name=message loop=$messagearray}
        <div class="list-group-item clearfix {if $messagearray[message].seen == NULL} warning bold{/if}">
            <div class="hide">            
            <input type="checkbox" name="messageid[{$smarty.section.message.index}]" value="{$messagearray[message].id}" />                
            </div>            
            <div class="col-lg-2">
                <div class="col-lg-6">                
                <a href="#" class="thumbnail" style="height:30px; width:30px;margin-bottom: 5px;">
                </a>
                </div>
                <div class="col-lg-6">
                <strong>{$messagearray[message].sender.uname}</strong>
                </div>
            </div>           
            <div class="col-lg-4">
                    <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id}">{if $messagearray[message].subject}{$messagearray[message].subject}{else}{gt text="Error! No subject line."}{/if}</a>               
            </div>            
            <div class="col-lg-3">              
                    {if $messagearray[message].seen == NULL}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagearray[message].id`" class="fa fa-envelope "> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagearray[message].id`" class="fa fa-reply hide"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagearray[message].id`" class="fa fa-envelope-o hide"> </span>                                 
                    {else}
                    {if $messagearray[message].replied !== NULL}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagearray[message].id`" class="fa fa-envelope hide"> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagearray[message].id`" class="fa fa-reply"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagearray[message].id`" class="fa fa-reply hide"> </span>                   
                    {else}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagearray[message].id`" class="fa fa-envelope hide"> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagearray[message].id`" class="fa fa-reply hide"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagearray[message].id`" class="fa fa-envelope-o"> </span> 
                    {/if}
                    {/if}
                    <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id}">{$messagearray[message].send|dateformat:"datetimebrief"}</a>            
            </div>
            <div class="col-lg-1">            
                    <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id}"><strong>{$messagearray[message].conversation|@count}</strong></a>
            </div>         
            <div class="col-lg-1">                    
                    <a data-toggle="collapse" data-target="msgbody-{$messagearray[message].id}"  href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id}"> <span class="fa fa-plus-square-o" title="Read"> </span></a>            
            </div> 
            <div id="msgbody-{$messagearray[message].id}" class="col-lg-12 hide">
                <div class="row">
                <div class="col-sm-2">
                </div>    
                <div class="col-sm-10">
                       {$messagearray[message].text|safehtml|nl2br} {* {$messagearray[message].text|safehtml|modcallhooks|nl2br} *}
                        {*if $messagearray[message].signature != ""}<div class="signature">{$messagearray[message].signature|safehtml|nl2br}{* {$messagearray[message].signature|safehtml|nl2br} }</div>{/if*}
                </div>
                </div>
                {if $messagearray[message].conversation|@count > 0 }
                {foreach from=$messagearray[message].conversation item='convmessage'}    
                <div class="row">                    
                <div class="col-sm-2">
                <a href="#" class="thumbnail" style="height:60px;">
                        {*icuseravatar uid=$convmessage.from_userid assign=useravatar}
                        {if isset($useravatar)}
                        {$convmessage.from_userid|profilelinkbyuid:'':$useravatar}
                        {/if}
                        {modavailable modname="ContactList" assign="ContactListInstalled"}
                        {*if $ContactListInstalled}
                        <p><a href="{modurl modname="ContactList" type="user" func="create" uid=$messagearray[message].from_userid}">{img modname="ContactList" src="user_add.png" __title="Add buddy" }</a></p>
                        {/if*}
                </a>
                {$convmessage.sender.uname}
                </div>                    
                <div class="col-sm-10">
                       {$convmessage.text|safehtml|nl2br} {* {$messagearray[message].text|safehtml|modcallhooks|nl2br} *}
                        {*if $messagearray[message].signature != ""}<div class="signature">{$messagearray[message].signature|safehtml|nl2br}{* {$messagearray[message].signature|safehtml|nl2br} }</div>{/if*}
                </div>
                
                <div class="col-sm-12 text-right">
                    <a  class="btn btn-default btn-sm " role="button" id="delete-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='delete' id=$messagearray[message].id}" title="{gt text='Delete'}"><i class="fa fa-trash"></i></a>                    
                    <a class="btn btn-default btn-sm " role="button" id="reply-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='reply' id=$messagearray[message].id}"      title="{gt text='Reply'}"><i class="fa fa-reply"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="forward-{$messagearray[message].id}" href="{route name='zikulaintercommodule_user_message' mode='forward'  id=$messagearray[message].id}"    title="{gt text='Forward'}"><i class="fa fa-share"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="store-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='save' id=$messagearray[message].id}" title="{gt text='Save'}"><i class="fa fa-save"></i></a>
                    <a  class="btn btn-default btn-sm " role="button"  id="print-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id theme=printer}" title="{gt text='Print'}"><i class="fa fa-print"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="read-{$messagearray[message].id}"    href="{route name='zikulaintercommodule_user_message' mode='read'  id=$messagearray[message].id}"          title="{gt text='Read'}"><i class="fa fa-search"></i></a>               
                </div>
                <div id="information-{$messagearray[message].id}" class=" hide">&nbsp;</div>

                <div id="msgaction-{$messagearray[message].id}" class=" hide">&nbsp;</div>
                </div>
                {/foreach}
                {/if}
                
            </div>
        </div>       
            {/section}
        </div>            
        <div class="btn-group">
            <button title="{gt text="Save marked messages"}"    type="submit" name="selected" value="save" class="btn btn-default btn-sm"><i class="fa fa-save"></i></button>
            <button title="{gt text="Mark as read"}"            type="submit" name="selected" value="markread" class="btn btn-default btn-sm"><i class="fa fa-check-square"></i></button>
            <button title="{gt text="Delete marked messages"}"  type="submit" name="selected" value="delete" class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button>
        </div>            
            
    {*if $getmessagecount.inboxlimitreached == 1 && !pnSecAuthAction(0, "InterCom", ".*",ACCESS_ADMIN)}
    {pager show="page" rowcount=$getmessagecount.limitin limit=$messagesperpage posvar=startnum shift=0}
    {else*}
    {pager show="page" rowcount=$getmessagecount.totalin limit=$messagesperpage posvar=startnum shift=0}
    {*/if*}
</form>
{include file="User/indicators.tpl"}
{include file="User/footer.tpl"}