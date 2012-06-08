{* $Id$ *}

{pageaddvar name="stylesheet" value="modules/InterCom/style/style.css"}
{getmessages}

<div class="intercomblock">
    <ul>
        <li>
            <a class="image inbox" href="{modurl modname="InterCom" type="user" func="inbox"}">{gt text="Mail" domain="module_intercom"}
                {if ($totalarray.unread > 0) || ($totalarray.totalin > 0)}
                [{$totalarray.unread}|{$totalarray.totalin}]
                {/if}
            </a>
        </li>
        <li><a class="image newmsg" href="{modurl modname="InterCom" type="user" func="newpm"}">{gt text="New mail" domain="module_intercom"}</a> </li>
        <li><a class="image memberlist" href="{modurl modname="Members_List"}">{gt text="Members list" domain="module_intercom"}</a></li>
    </ul>
</div>