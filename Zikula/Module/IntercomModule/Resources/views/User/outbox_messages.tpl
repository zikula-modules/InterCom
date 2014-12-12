<form class="form" action="{route name="zikulaintercommodule_user_outbox"}" method="post" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />  
    
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">{$ictitle}</div>       
        <table class="table table-striped table-hover">
        <tr>
            <th>#</th>
            <th></th>        
            <th>{gt text="Subject"}
            {if $sortby eq 'subject'}
            {if $sortorder eq 'ASC'}
            <a href="{route name='zikulaintercommodule_user_outbox' sortby='subject' sortorder='DESC'}"><i class='fa fa-sort-desc'></i></a>             
            {else}
            <a href="{route name='zikulaintercommodule_user_outbox' sortby='subject' sortorder='ASC'}"><i class='fa fa-sort-asc'></i></a>                
            {/if}    
            {else}
            <a href="{route name='zikulaintercommodule_user_outbox' sortby='subject' sortorder='ASC'}"><i class='fa fa-sort'></i></a>                
            {/if}    
            </th>
            <th>{gt text="Date"}
            {if $sortby eq 'send'}
            {if $sortorder eq 'ASC'}
            <a href="{route name='zikulaintercommodule_user_outbox' sortby='send' sortorder='DESC'}"><i class='fa fa-sort-desc'></i></a>             
            {else}
            <a href="{route name='zikulaintercommodule_user_outbox' sortby='send' sortorder='ASC'}"><i class='fa fa-sort-asc'></i></a>                
            {/if}    
            {else}
            <a href="{route name='zikulaintercommodule_user_outbox' sortby='send' sortorder='ASC'}"><i class='fa fa-sort'></i></a>                
            {/if}            
            </th>
            <th>{gt text="Recipient(s)"}</th>
            <th></th>         
        </tr>
       {section name=message loop=$messagearray}
        <tr class="{if $messagearray[message].seen == NULL} warning bold{/if}">
            <td><input type="checkbox" name="messageid[{$smarty.section.message.index}]" value="{$messagearray[message].id}" /></td>
            <td>{if $messagearray[message].seen == NULL}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagearray[message].id`" class="fa fa-envelope "> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagearray[message].id`" class="fa fa-envelope-o hide"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagearray[message].id`" class="fa fa-reply hide"> </span>                                 
                    {else}
                    {if $messagearray[message].replied !== NULL}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagearray[message].id`" class="fa fa-envelope hide"> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagearray[message].id`" class="fa fa-envelope-o hide"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagearray[message].id`" class="fa fa-reply"> </span>                   
                    {else}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagearray[message].id`" class="fa fa-envelope hide"> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagearray[message].id`" class="fa fa-envelope-o"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagearray[message].id`" class="fa fa-re hide"> </span> 
                    {/if}
                    {/if}
            </td>
            <td>
                    <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id}">{if $messagearray[message].subject}{$messagearray[message].subject}{else}{gt text="Error! No subject line."}{/if}</a>               
            </td>
            <td>
                    <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id}">{$messagearray[message].send|dateformat:"datetimebrief"}</a>            
            </td>
            <td>
                    <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id}"><strong>{$messagearray[message].recipient.uname}</strong></a>
            </td>
            <td>
                    <a href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id}"> <span class="fa fa-eye" title="Read"> </span></a>            
            </td>            
            </tr>
            <tr id="msgbody-{$messagearray[message].id}" class=" hide">
           <td colspan="6">
            <div class="row">
                <div class="col-sm-3">
                <a href="#" class="thumbnail">
                </a></div>
                    
                <div class="col-sm-9">
                {$messagearray[message].text|safehtml|nl2br}
                </div>
                
                <div class="col-sm-12">
                    <a class="btn btn-default btn-sm " role="button" id="read-{$messagearray[message].id}"    href="{route name='zikulaintercommodule_user_message' mode='read'  id=$messagearray[message].id}"      title="{gt text='Read'}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="reply-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='reply' id=$messagearray[message].id}"      title="{gt text='Reply'}"><i class="fa fa-reply"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="forward-{$messagearray[message].id}" href="{route name='zikulaintercommodule_user_message' mode='forward'  id=$messagearray[message].id}"    title="{gt text='Forward'}"><i class="fa fa-forward"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="store-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='save' id=$messagearray[message].id}" title="{gt text='Save'}"><i class="fa fa-save"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="print-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_message' mode='read' id=$messagearray[message].id theme=printer}" title="{gt text='Print'}"><i class="fa fa-print"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="delete-{$messagearray[message].id}"  href="{route name='zikulaintercommodule_user_message' mode='delete' id=$messagearray[message].id}" title="{gt text='Delete'}"><i class="fa fa-trash"></i></a>
                </div>
                <div id="information-{$messagearray[message].id}" class=" hide">&nbsp;</div>

                <div id="msgaction-{$messagearray[message].id}" class=" hide">&nbsp;</div>
                </div>
                </td>
            </tr>       
         {/section}
        </table> 
    <div class="panel-footer">
        <div class="btn-group">
            <button title="{gt text="Save marked messages"}"    type="submit" name="selected" value="save" class="btn btn-default btn-sm"><i class="fa fa-save"></i></button>
            <button title="{gt text="Delete marked messages"}"  type="submit" name="selected" value="delete"   class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button>
        </div>
    </div> 
</div>        

    {*if $getmessagecount.inboxlimitreached == 1 && !pnSecAuthAction(0, "InterCom", ".*",ACCESS_ADMIN)}
    {pager show="page" rowcount=$getmessagecount.limitin limit=$messagesperpage posvar=startnum shift=0}
    {else}
    {pager show="page" rowcount=$getmessagecount.totalin limit=$messagesperpage posvar=startnum shift=0}
    {/if*}

</form>