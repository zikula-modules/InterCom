{* $Id$ *}

{pageaddvar name="stylesheet" value="modules/InterCom/style/style.css"}
{getmessages}

<div class="intercomblock">
    <ul>
        <li>
            <a class="image inbox" href="{modurl modname="InterCom" type="user" func="inbox"}">{img modname=core src="folder_inbox.gif" set="icons/extrasmall" __alt="Inbox" }&nbsp;{gt text="Mail" domain="module_intercom"}
                {if ($totalarray.unread > 0) || ($totalarray.totalin > 0)}
                [{$totalarray.unread}|{$totalarray.totalin}]
                {/if}
            </a>
        </li>
        <li><a class="image newpm" href="{modurl modname="InterCom" type="user" func="newpm"}">{img modname=core src="mail_new.gif" set="icons/extrasmall" __alt="New message" }&nbsp;{gt text="New mail" domain="module_intercom"}</a> </li>
        <li><a class="image memberlist" href="{modurl modname="Members_List"}">{img modname=core src="agt_forum.gif" set="icons/extrasmall" __alt="New message" }&nbsp;{gt text="Members list" domain="module_intercom"}</a></li>
    </ul>
</div>