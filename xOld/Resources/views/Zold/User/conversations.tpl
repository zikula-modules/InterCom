{include file="User/header.tpl" ictitle=$ictitle}
{pageaddvar name='stylesheet' value='@ZikulaIntercomModule/Resources/public/css/conversations.css'}
<form class="form" action="{route name="zikulaintercommodule_user_inbox"}" method="post" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
<div class="list-group">
{section name=message loop=$messagearray}
        <div class="list-group-item clearfix {if $messagearray[message].seen == NULL} list-group-item-warning{/if}">
            <div class="hide">            
            <input type="checkbox" name="messageid[{$smarty.section.message.index}]" value="{$messagearray[message].id}" />                
            </div>            
            <div class="col-lg-1">
                <div class="col-lg-5">                
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
                    <a data-toggle="collapse" data-target="#msgbody-{$messagearray[message].id}"  href="#{*route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id*}"> <span class="fa fa-plus-square-o" title="Read"> </span></a>            
            </div> 
            <div id="msgbody-{$messagearray[message].id}" class="col-lg-12 collapse">
                <div class="col-lg-12">    
                <div class="col-sm-6">
                       {$messagearray[message].text|safehtml|nl2br} {* {$messagearray[message].text|safehtml|modcallhooks|nl2br} *}
                        {*if $messagearray[message].signature != ""}<div class="signature">{$messagearray[message].signature|safehtml|nl2br}{* {$messagearray[message].signature|safehtml|nl2br} }</div>{/if*}
                </div>
                </div>
                {if $messagearray[message].conversation|@count > 0 }
                {foreach from=$messagearray[message].conversation item='convmessage'}    
                <div class="conversation-message col-sm-12 {if $convmessage.recipient.uid eq $currentuid} {else} {/if}">                    
            <div class="col-lg-1">
                <div class="col-lg-5">                
                <a href="#" class="thumbnail" style="height:30px; width:30px;margin-bottom: 5px;">
                </a>
                </div>
                <div class="col-lg-6">
                <strong>{$convmessage.sender.uname}</strong>
                </div>
            </div>                     
                <div class="col-sm-10">
                       {$convmessage.text|safehtml|nl2br} {* {$messagearray[message].text|safehtml|modcallhooks|nl2br} *}
                        {*if $messagearray[message].signature != ""}<div class="signature">{$messagearray[message].signature|safehtml|nl2br}{* {$messagearray[message].signature|safehtml|nl2br} }</div>{/if*}
                </div>
                </div>
                {/foreach}
                {/if}
                <div class="conversation-message col-sm-12">                    
                <div class="col-lg-1">
                <div class="col-lg-5">                
                <a href="#" class="thumbnail" style="height:30px; width:30px;margin-bottom: 5px;">
                </a>
                </div>
                <div class="col-lg-6">
                <strong>admin</strong>
                </div>
                </div>                     
                <div class="col-sm-10">
                    <textarea class="form-control col-lg-6" id="reply-message" name="reply-message" type="textarea" value="" rows="3"></textarea>
                </div>
                </div>                
            </div>
        </div>       
            {/section}
        </div>            
         
    {*if $getmessagecount.inboxlimitreached == 1 && !pnSecAuthAction(0, "InterCom", ".*",ACCESS_ADMIN)}
    {pager show="page" rowcount=$getmessagecount.limitin limit=$messagesperpage posvar=startnum shift=0}
    {else*}
    {pager show="page" rowcount=$getmessagecount.totalin limit=$messagesperpage posvar=startnum shift=0}
    {*/if*}
</form>
{include file="User/indicators.tpl"}
{include file="User/footer.tpl"}