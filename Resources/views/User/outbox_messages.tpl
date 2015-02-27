<form class="form" action="{route name="zikulaintercommodule_user_inbox"}" method="post" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />  

    <div class="list-group clearfix">
        <div class="list-group-item col-lg-12" style="background: #F2F8FF;">
                    <div class="col-sm-1"><i class="fa fa-upload"> </i> {$ictitle}</div>                   
        </div>
        <div class="list-group-item col-lg-12" style="background: #eee;">
            <div class="col-lg-1">
                {gt text="Recipient"}                    
            </div>
            <div class="col-lg-4">
                {gt text="Subject"}
                {if $sortby eq 'subject'}
                {if $sortorder eq 'ASC'}
                <a href="{route name='zikulaintercommodule_user_inbox' sortby='subject' sortorder='DESC'}"><i class='fa fa-sort-desc'></i></a>             
                {else}
                <a href="{route name='zikulaintercommodule_user_inbox' sortby='subject' sortorder='ASC'}"><i class='fa fa-sort-asc'></i></a>                
                {/if}    
                {else}
                <a href="{route name='zikulaintercommodule_user_inbox' sortby='subject' sortorder='ASC'}"><i class='fa fa-sort'></i></a>                
                {/if}
            </div>             
            <div class="col-lg-2">
                {gt text="Date"}
                {if $sortby eq 'send'}
                {if $sortorder eq 'ASC'}
                <a href="{route name='zikulaintercommodule_user_inbox' sortby='send' sortorder='DESC'}"><i class='fa fa-sort-desc'></i></a>             
                {else}
                <a href="{route name='zikulaintercommodule_user_inbox' sortby='send' sortorder='ASC'}"><i class='fa fa-sort-asc'></i></a>                
                {/if}    
                {else}
                <a href="{route name='zikulaintercommodule_user_inbox' sortby='send' sortorder='ASC'}"><i class='fa fa-sort'></i></a>                
                {/if}                     
            </div>
            <div class="col-lg-1">
                {gt text="Status"}                    
            </div>      
            <div class="col-lg-2 pull-right text-right">                
                {gt text="Options"} <input type="checkbox" name="messageid" value="" /> 
            </div>
        </div>
        {if $messagesarray == FALSE}
            <div class="list-group-item col-lg-12 alert alert-info">{gt text="You currently have no messages."}</div>
        {else}
        {section name=message loop=$messagesarray}               
        <div class="list-group-item col-lg-12 {if $messagesarray[message].seen == NULL} warning bold{/if}" {if $messagesarray[message].seen == NULL} style="background:#fcf0ba"{/if}>
                <div class="col-lg-1">                 
                <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagesarray[message].id}"><strong>{$messagesarray[message].recipient.uname}</strong></a>
                </div>
                <div class="col-lg-4">    
                <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagesarray[message].id}">{if $messagesarray[message].subject}{$messagesarray[message].subject}{else}{gt text="Error! No subject line."}{/if}</a>  
                </div>
                <div class="col-lg-2">
                {$messagesarray[message].send|dateformat:"datetimebrief"}
                </div>                
                <div class="col-lg-1">
                    {if $messagesarray[message].seen == NULL}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagesarray[message].id`" class="fa fa-envelope "> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagesarray[message].id`" class="fa fa-reply hide"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagesarray[message].id`" class="fa fa-envelope-o hide"> </span>                                 
                    {else}
                    {if $messagesarray[message].replied !== NULL}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagesarray[message].id`" class="fa fa-envelope hide"> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagesarray[message].id`" class="fa fa-reply"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagesarray[message].id`" class="fa fa-reply hide"> </span>                   
                    {else}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagesarray[message].id`" class="fa fa-envelope hide"> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagesarray[message].id`" class="fa fa-reply hide"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagesarray[message].id`" class="fa fa-envelope-o"> </span> 
                    {/if}
                    {/if}                
                </div>
                <div class="col-lg-1 pull-right text-right">
                <input type="checkbox" name="messageid[{$smarty.section.message.index}]" value="{$messagesarray[message].id}" /> 
                </div>                 
                <div class="col-lg-1 pull-right text-right">
                <a data-toggle="collapse" data-target="#msgbody-{$messagesarray[message].id}"  href="#{*route name='zikulaintercommodule_user_message' mode='read' id=$messagesarray[message].id*}"> <span class="fa fa-angle-down fa-2x" title="{gt text='Read'}"> </span></a>   
                </div>               
                
                <div id="msgbody-{$messagesarray[message].id}" class="col-lg-12 collapse" >
                <div class="row">
                <div class="col-sm-1">
                <a href="#" class="thumbnail" style="height:40px;width:40px;">
                        {*icuseravatar uid=$messagesarray[message].from_userid assign=useravatar}
                        {if isset($useravatar)}
                        {$messagesarray[message].from_userid|profilelinkbyuid:'':$useravatar}
                        {/if}
                        {modavailable modname="ContactList" assign="ContactListInstalled"}
                        {*if $ContactListInstalled}
                        <p><a href="{modurl modname="ContactList" type="user" func="create" uid=$messagesarray[message].from_userid}">{img modname="ContactList" src="user_add.png" __title="Add buddy" }</a></p>
                        {/if*}
                </a>
                {* $messagesarray[message].sender.uname *}
                </div>                    
                <div class="col-sm-6">
                       {$messagesarray[message].text|safehtml|nl2br} {* {$messagearray[message].text|safehtml|modcallhooks|nl2br} *}
                        {*if $messagesarray[message].signature != ""}<div class="signature">{$messagesarray[message].signature|safehtml|nl2br}{* {$messagesarray[message].signature|safehtml|nl2br} }</div>{/if*}
                </div>
                
                <div class="col-sm-4">
                    <a  class="btn btn-default btn-sm " role="button" id="delete-{$messagesarray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='delete' id=$messagesarray[message].id}" title="{gt text='Delete'}"><i class="fa fa-trash"></i></a>                    
                    <a class="btn btn-default btn-sm " role="button" id="reply-{$messagesarray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='reply' id=$messagesarray[message].id}"      title="{gt text='Reply'}"><i class="fa fa-reply"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="forward-{$messagesarray[message].id}" href="{route name='zikulaintercommodule_user_message' mode='forward'  id=$messagesarray[message].id}"    title="{gt text='Forward'}"><i class="fa fa-share"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="store-{$messagesarray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='save' id=$messagesarray[message].id}" title="{gt text='Save'}"><i class="fa fa-save"></i></a>
                    <a  class="btn btn-default btn-sm " role="button"  id="print-{$messagesarray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagesarray[message].id theme=printer}" title="{gt text='Print'}"><i class="fa fa-print"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="read-{$messagesarray[message].id}"    href="{route name='zikulaintercommodule_user_message' mode='read'  id=$messagesarray[message].id}"          title="{gt text='Read'}"><i class="fa fa-search"></i></a>               
                </div>
                <div id="information-{$messagesarray[message].id}" class=" hide">&nbsp;</div>

                <div id="msgaction-{$messagesarray[message].id}" class=" hide">&nbsp;</div>
                </div>                    
                </div>
                </div>
                
            {/section}
            {/if}
    <div class="list-group-item col-lg-12" style="background: #eee;">
                    <div class="col-sm-6">    
                    {include file="User/indicators.tpl"}
                    </div>         
    <div class="btn-group pull-right">
            <button title="{gt text="Save marked messages"}"    type="submit" name="selected" value="save" class="btn btn-default btn-sm"><i class="fa fa-save"></i></button>
            <button title="{gt text="Mark as read"}"            type="submit" name="selected" value="markread" class="btn btn-default btn-sm"><i class="fa fa-check-square"></i></button>
            <button title="{gt text="Delete marked messages"}"  type="submit" name="selected" value="delete" class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button>
    </div>    
    </div>
    <div class="list-group-item col-lg-12" style="background: #F5F5F5">    
    {*if $getmessagecount.inboxlimitreached == 1 && !pnSecAuthAction(0, "InterCom", ".*",ACCESS_ADMIN)}
    {pager show="page" rowcount=$getmessagecount.limitin limit=$messagesperpage posvar=startnum shift=0}
    {else*}
    {pager show="page" rowcount=$messagescount limit=$limit posvar=page shift=1}
    {*/if*}        
    </div>     
 </div> 
</form>