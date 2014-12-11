<form id="view" class="z-form z-linear" action="{modurl modname="InterCom" type="user" func="switchaction"}" method="post">
    
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">{$ictitle}</div>
  <div class="panel-body">
    <p>...</p>
  </div>        
        <table class="table table-striped table-hover">
        <tr>
            <th>#</th>
            <th></th>        
            <th>{gt text="Subject"}
            </th>
            <th>{gt text="Date"}</th>
            <th>{gt text="Sender"}</th>
            <th></th>         
        </tr>
            {section name=message loop=$messagearray}
            {counter assign=zaehlen}
        <tr>
            <td><input type="checkbox" onclick="CheckCheckAll();" name="messageid[{$smarty.section.message.index}]" value="{$messagearray[message].id}" /></td>
            <td>{if $messagearray[message].seen == null}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagearray[message].id`" class="fa fa-envelope"> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagearray[message].id`" class="fa fa-envelope-o hide"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagearray[message].id`" class="fa fa-envelope-square hide"> </span>                                 
                    {else}
                    {if $messagearray[message].replied !== NULL}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagearray[message].id`" class="fa fa-envelope"> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagearray[message].id`" class="fa fa-envelope-o hide"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagearray[message].id`" class="fa fa-envelope-square hide"> </span>                   
                    {else}
                    <span title="{gt text="Unread"}"  id="msg-unread-`$messagearray[message].id`" class="fa fa-envelope"> </span>
                    <span title="{gt text="Answered"}"  id="msg-answered-`$messagearray[message].id`" class="fa fa-envelope-o hide"> </span>                    
                    <span title="{gt text="Read"}"  id="msg-read-`$messagearray[message].id`" class="fa fa-envelope-square hide"> </span> 
                    {/if}
                    {/if}
            </td>
            <td>
                    <a class="noajax" href="{route name='zikulaintercommodule_user_read' id=$messagearray[message].id}">{if $messagearray[message].subject}{$messagearray[message].subject}{else}{gt text="Error! No subject line."}{/if}</a>               
            </td>
            <td>
                    <a href="{route name='zikulaintercommodule_user_read' id=$messagearray[message].id}">{$messagearray[message].send|dateformat:"datetimebrief"}</a>            
            </td>
            <td>
                    <a href="{route name='zikulaintercommodule_user_read' id=$messagearray[message].id}"><strong>{$messagearray[message].sender.uname}</strong></a>
            </td>
            <td>
                    <a href="{route name='zikulaintercommodule_user_read' id=$messagearray[message].id}"> <span class="fa fa-eye" title="Read"> </span></a>            
            </td>            
            </tr>
            <tr id="msgbody-{$messagearray[message].id}" class=" hide"><td colspan="6">
            <div class="row">
                <div class="col-sm-3">
                <a href="#" class="thumbnail">
                        {*icuseravatar uid=$messagearray[message].from_userid assign=useravatar}
                        {if isset($useravatar)}
                        {$messagearray[message].from_userid|profilelinkbyuid:'':$useravatar}
                        {/if}
                        {modavailable modname="ContactList" assign="ContactListInstalled"}
                        {*if $ContactListInstalled}
                        <p><a href="{modurl modname="ContactList" type="user" func="create" uid=$messagearray[message].from_userid}">{img modname="ContactList" src="user_add.png" __title="Add buddy" }</a></p>
                        {/if*}
                </a></div>
                    
                <div class="col-sm-9">
                       {$messagearray[message].text|safehtml|nl2br} {* {$messagearray[message].text|safehtml|modcallhooks|nl2br} *}
                        {*if $messagearray[message].signature != ""}<div class="signature">{$messagearray[message].signature|safehtml|nl2br}{* {$messagearray[message].signature|safehtml|nl2br} }</div>{/if*}
                </div>
                
                <div class="col-sm-12">
                    <a class="btn btn-default btn-sm " role="button" id="read-{$messagearray[message].id}"    href="{route name='zikulaintercommodule_user_read'  id=$messagearray[message].id}"          title="{gt text='Read'}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="reply-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_reply' id=$messagearray[message].id}"      title="{gt text='Reply'}"><i class="fa fa-reply"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="forward-{$messagearray[message].id}" href="{route name='zikulaintercommodule_user_forward'  id=$messagearray[message].id}"    title="{gt text='Forward'}"><i class="fa fa-forward"></i></a>
                    <a class="btn btn-default btn-sm " role="button" id="store-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_store' id=$messagearray[message].id}" title="{gt text='Save'}"><i class="fa fa-save"></i></a>
                    <a  class="btn btn-default btn-sm " role="button"  id="print-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_read' id=$messagearray[message].id theme=printer}" title="{gt text='Print'}"><i class="fa fa-print"></i></a>
                    <a  class="btn btn-default btn-sm " role="button" id="delete-{$messagearray[message].id}"   href="{route name='zikulaintercommodule_user_delete' id=$messagearray[message].id}" title="{gt text='Delete'}"><i class="fa fa-trash"></i></a>
                </div>
                <div id="information-{$messagearray[message].id}" class=" hide">&nbsp;</div>

                <div id="msgaction-{$messagearray[message].id}" class="ajaxbody invisible">&nbsp;</div>
                </div>
                </td>
            </tr>       
            {/section}
        </table> 
    <div class="panel-footer">
        <div class="btn-group">
            <button title="{gt text="Save marked messages"}"    type="submit" name="delete" class="btn btn-default btn-sm"><i class="fa fa-save"></i></button>
            <button title="{gt text="Mark as read"}"            type="submit" name="read"   class="btn btn-default btn-sm"><i class="fa fa-check-square"></i></button>
            <button title="{gt text="Delete marked messages"}"  type="submit" name="save"   class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button>
        </div>
    </div> 
</div>        

    {*if $getmessagecount.inboxlimitreached == 1 && !pnSecAuthAction(0, "InterCom", ".*",ACCESS_ADMIN)}
    {pager show="page" rowcount=$getmessagecount.limitin limit=$messagesperpage posvar=startnum shift=0}
    {else}
    {pager show="page" rowcount=$getmessagecount.totalin limit=$messagesperpage posvar=startnum shift=0}
    {/if*}

</form>