<form id="view" class="z-form z-linear" action="{modurl modname="InterCom" type="user" func="switchaction"}" method="post">
    <fieldset>
        <legend>{$ictitle}</legend>
        <dl class="header">
            <dd class="check"><input name="allbox" id="allbox" onclick="CheckAll();" type="checkbox" value="{gt text="Mark all"}" /></dd>
            <dd class="icon">&nbsp;</dd>
            <dt class="subject">{gt text="Subject"}
                {if $sort neq 5}
                <a href="{modurl modname="InterCom" type="user" func=inbox sort=5}">{*img src="black_down.png"*}</a>
                {else}{*img src="green_down.png"*}{/if}
                {if $sort neq 6}
                <a href="{modurl modname="InterCom" type="user" func=inbox sort=6}">{*img src="black_up.png"*}</a>
                {else}{*img src="green_up.png"*}{/if}
            </dt>
            <dd class="time">{gt text="Date"}
                {if $sort neq 3}
                <a href="{modurl modname="InterCom" type="user" func=inbox sort=3}">{*img src="black_down.png"*}</a>
                {else}{*img src="green_down.png"*}{/if}
                {if $sort neq 4}
                <a href="{modurl modname="InterCom" type="user" func=inbox sort=4}">{*img src="black_up.png"*}</a>
                {else}{*img src="green_up.png"*}{/if}
            </dd>
            <dd class="uname">{gt text="Sender"}
                {if $sort neq 1}
                <a href="{modurl modname="InterCom" type="user" func=inbox sort=1}">{*img src="black_down.png"*}</a>
                {else}{*img src="green_down.png"*}{/if}
                {if $sort neq 2}
                <a href="{modurl modname="InterCom" type="user" func=inbox sort=2}">{*img src="black_up.png"*}</a>
                {else}{*img src="green_up.png"*}{/if}
            </dd>
            <dd class="view">&nbsp;</dd>
        </dl>

        <div id="listing">
            {section name=message loop=$messagearray}
            {counter assign=zaehlen}
            <dl id="msgheader-{$messagearray[message].id}" class="line {if $zaehlen is odd}odd{elseif $zaehlen is even}even{/if}">
                <dd class="check"><input type="checkbox" onclick="CheckCheckAll();" name="messageid[{$smarty.section.message.index}]" value="{$messagearray[message].id}" /></dd>
                <dd id="msgicon{$messagearray[message].id}" class="icon clickable">
                    {if $messagearray[message].seen == null}
                    {*img modname=InterCom src="mail_unread.png" __title="Unread"  id="msg-unread-`$messagearray[message].id`"}
                    {img modname=InterCom src="mail_answered.png" __title="Answered"  class="invisible" id="msg-answered-`$messagearray[message].id`"}
                    {img modname=core src="mail_generic.png" set="icons/extrasmall" __title="Read"  class="invisible" id="msg-read-`$messagearray[message].id`"*}
                    {else}
                    {if $messagearray[message].replied !== NULL}
                    {*img modname=InterCom src="mail_unread.png" __title="Unread"  class="invisible" id="msg-unread-`$messagearray[message].id`"}
                    {img modname=InterCom src="mail_answered.png" __title="Answered"  id="msg-answered-`$messagearray[message].id`"}
                    {img modname=core src="mail_generic.png" set="icons/extrasmall" __title="Read"  class="invisible" id="msg-read-`$messagearray[message].id`"*}
                    {else}
                    {*img modname=InterCom src="mail_unread.png" __title="Unread"  class="invisible" id="msg-unread-`$messagearray[message].id`"}
                    {img modname=InterCom src="mail_answered.png" __title="Answered"  class="invisible" id="msg-answered-`$messagearray[message].id`"}
                    {img modname=core src="mail_generic.png" set="icons/extrasmall" __title="Read"  id="msg-read-`$messagearray[message].id`"*}
                    {/if}
                    {/if}
                </dd>
                <dt class="subject clickable"><a class="noajax" href="{modurl modname="InterCom" type="user" func="readinbox" messageid=$messagearray[message].id}">{if $messagearray[message].subject}{$messagearray[message].subject}{else}{gt text="Error! No subject line."}{/if}</a></dt>
                <dd class="time clickable"><a href="{modurl modname="InterCom" type="user" func="readinbox" messageid=$messagearray[message].id}">{$messagearray[message].send|dateformat:"datetimebrief"}</a></dd>
                <dd class="uname clickable"><a href="{modurl modname="InterCom" type="user" func="readinbox" messageid=$messagearray[message].id}"><strong>{*$messagearray[message].sender.uid|truncate:45|safehtml*}</strong></a></dd>
                <dd class="view"><a href="{modurl modname="InterCom" type="user" func="readinbox" messageid=$messagearray[message].id}">{img modname=core src="demo.png" set="icons/extrasmall" __title="Read" }</a></dd>
            </dl>

            <div id="msgbody-{$messagearray[message].id}" class="body" style="display: none;">

                <div class="z-clearfix">
                    <div class="avatar">
                        {*icuseravatar uid=$messagearray[message].from_userid assign=useravatar}
                        {if isset($useravatar)}
                        {$messagearray[message].from_userid|profilelinkbyuid:'':$useravatar}
                        {/if}
                        {modavailable modname="ContactList" assign="ContactListInstalled"}
                        {*if $ContactListInstalled}
                        <p><a href="{modurl modname="ContactList" type="user" func="create" uid=$messagearray[message].from_userid}">{img modname="ContactList" src="user_add.png" __title="Add buddy" }</a></p>
                        {/if*}
                    </div>

                    <div class="text">
                       {*$messagearray[message].text|safehtml|nl2br} {* {$messagearray[message].text|safehtml|modcallhooks|nl2br} }
                        {if $messagearray[message].signature != ""}<div class="signature">{$messagearray[message].signature|safehtml|nl2br}{* {$messagearray[message].signature|safehtml|nl2br} }</div>{/if*}
                    </div>
                </div>

                <ul class="links">
                    <li><a class="image view" href="{modurl modname="InterCom" type="user" func="readinbox" messageid=$messagearray[message].id}">{gt text="Read"}</a></li>
                    <li><a id="reply-{$messagearray[message].id}" class="image mailreply" href="{modurl modname="InterCom" type="user" func="replyinbox" messageid=$messagearray[message].id}">{gt text="Reply"}</a></li>
                    <li><a id="forward-{$messagearray[message].id}" class="image mailforward" href="{modurl modname="InterCom" type="user" func="forwardinbox" messageid=$messagearray[message].id}">{gt text="Forward"}</a></li>
                    <li><a id="store-{$messagearray[message].id}" class="image mailsave" href="{modurl modname="InterCom" type="user" func="storepm" messageid=$messagearray[message].id}">{gt text="Save"}</a></li>
                    <li><a id="print-{$messagearray[message].id}" class="image printer" href="{modurl modname="InterCom" type="user" func="readinbox" messageid=$messagearray[message].id theme=printer}">{gt text="Print"}</a></li>
                    <li><a id="delete-{$messagearray[message].id}" class="image maildelete" href="{modurl modname="InterCom" type="user" func="deletefrominbox" messageid=$messagearray[message].id}">{gt text="Delete"}</a></li>
                </ul>

                <div id="information-{$messagearray[message].id}" style="display: none;">&nbsp;</div>

                <div id="msgaction-{$messagearray[message].id}" class="ajaxbody invisible">&nbsp;</div>

            </div>
            {/section}
        </div>

        <div class="ic-buttons footer">
            <input type="submit" name="save" value="{gt text="Save marked messages"}" />
            <input type="submit" name="delete" value="{gt text="Delete marked messages"}" />
        </div>

    </fieldset>

    {*if $getmessagecount.inboxlimitreached == 1 && !pnSecAuthAction(0, "InterCom", ".*",ACCESS_ADMIN)}
    {pager show="page" rowcount=$getmessagecount.limitin limit=$messagesperpage posvar=startnum shift=0}
    {else}
    {pager show="page" rowcount=$getmessagecount.totalin limit=$messagesperpage posvar=startnum shift=0}
    {/if*}

</form>