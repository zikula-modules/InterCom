{* $Id$ *}
<div class="z-menu">
    <div class="z-menuitem-title">
        [
        <span><a class="image inbox" href="{modurl modname="InterCom" type="user" func="inbox"}" title="{gt text="Inbox"}">{gt text="Inbox"}</a></span>
        |
        <span><a class="image outbox" href="{modurl modname="InterCom" type="user" func="outbox"}" title="{gt text="Outbox"}">{gt text="Outbox"}</a></span>
        |
        <span><a class="image mailsave navigationonly" href="{modurl modname="InterCom" type="user" func="archive"}" title="{gt text="Archive"}">{gt text="Archive"}</a></span>
        {if $pncore.InterCom.messages_allow_emailnotification eq true && $pncore.InterCom.messages_allow_autoreply eq true}
        |
        <span><a class="image userpref" href="{modurl modname="InterCom" type="user" func="settings"}" title="{gt text="Settings"}">{gt text="Settings"}</a></span>
        {/if}
        |
        <span><a class="image newmsg" href="{modurl modname="InterCom" type="user" func="newpm"}" title="{gt text="New message"}">{gt text="New message"}</a></span>
        ]
    </div>
</div>