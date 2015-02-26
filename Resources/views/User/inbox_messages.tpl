<form class="form" action="{route name="zikulaintercommodule_user_inbox"}" method="post" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />    
    
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading"><i class="fa fa-inbox"> </i> {$ictitle}</div>     
        <table class="table table-striped table-hover">
        <tr>
            <th><input type="checkbox"/></th>
            <th></th>        
            <th>{gt text="Subject"}
            {if $sortby eq 'subject'}
            {if $sortorder eq 'ASC'}
            <a href="{route name='zikulaintercommodule_user_inbox' sortby='subject' sortorder='DESC'}"><i class='fa fa-sort-desc'></i></a>             
            {else}
            <a href="{route name='zikulaintercommodule_user_inbox' sortby='subject' sortorder='ASC'}"><i class='fa fa-sort-asc'></i></a>                
            {/if}    
            {else}
            <a href="{route name='zikulaintercommodule_user_inbox' sortby='subject' sortorder='ASC'}"><i class='fa fa-sort'></i></a>                
            {/if}    
            </th>
            <th>{gt text="Date"}
            {if $sortby eq 'send'}
            {if $sortorder eq 'ASC'}
            <a href="{route name='zikulaintercommodule_user_inbox' sortby='send' sortorder='DESC'}"><i class='fa fa-sort-desc'></i></a>             
            {else}
            <a href="{route name='zikulaintercommodule_user_inbox' sortby='send' sortorder='ASC'}"><i class='fa fa-sort-asc'></i></a>                
            {/if}    
            {else}
            <a href="{route name='zikulaintercommodule_user_inbox' sortby='send' sortorder='ASC'}"><i class='fa fa-sort'></i></a>                
            {/if}            
            </th>
            <th>{gt text="Sender"}</th>
            <th></th>         
        </tr>
            {section name=message loop=$messagesarray}
        <tr class="{if $messagesarray[message].seen == NULL} warning bold{/if}">
            <td><input type="checkbox" name="messageid[{$smarty.section.message.index}]" value="{$messagesarray[message].id}" /></td>
            <td>                    
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
            </td>
            <td>
                    <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagesarray[message].id}">{if $messagesarray[message].subject}{$messagesarray[message].subject}{else}{gt text="Error! No subject line."}{/if}</a>               
            </td>
            <td>
                    <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagesarray[message].id}">{$messagesarray[message].send|dateformat:"datetimebrief"}</a>            
            </td>
            <td>
                    <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagesarray[message].id}"><strong>{$messagesarray[message].sender.uname}</strong></a>
            </td>
            <td>
                    <a data-toggle="collapse" data-target="#msgbody-{$messagesarray[message].id}"  href="#{*route name='zikulaintercommodule_user_message' mode='read' id=$messagesarray[message].id*}"> <span class="fa fa-plus-square-o" title="Read"> </span></a>  
            </td>            
            </tr>
            <tr id="msgbody-{$messagesarray[message].id}" class="collapse"><td colspan="6">
            <div class="row">
                <div class="col-sm-2">
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
                {$messagesarray[message].sender.uname}
                </div>                    
                <div class="col-sm-10">
                       {$messagesarray[message].text|safehtml|nl2br} {* {$messagearray[message].text|safehtml|modcallhooks|nl2br} *}
                        {*if $messagesarray[message].signature != ""}<div class="signature">{$messagesarray[message].signature|safehtml|nl2br}{* {$messagesarray[message].signature|safehtml|nl2br} }</div>{/if*}
                </div>
                
                <div class="col-sm-12 text-right">
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
                </td>
            </tr>       
            {/section}
        </table> 
    <div class="panel-footer">
        <div class="btn-group">
            <button title="{gt text="Save marked messages"}"    type="submit" name="selected" value="save" class="btn btn-default btn-sm"><i class="fa fa-save"></i></button>
            <button title="{gt text="Mark as read"}"            type="submit" name="selected" value="markread" class="btn btn-default btn-sm"><i class="fa fa-check-square"></i></button>
            <button title="{gt text="Delete marked messages"}"  type="submit" name="selected" value="delete" class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button>
        </div>
    </div> 
</div>        

    {*if $getmessagecount.inboxlimitreached == 1 && !pnSecAuthAction(0, "InterCom", ".*",ACCESS_ADMIN)}
    {pager show="page" rowcount=$getmessagecount.limitin limit=$messagesperpage posvar=startnum shift=0}
    {else*}
    {pager show="page" rowcount=$getmessagecount limit=$limit posvar=startnum shift=0}
    {*/if*}

</form>