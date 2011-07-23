{* $Id$ *}
{include file="admin/menu.tpl"}

<h2>{gt text="Statistics"}</h2>

<dl>
    <dt><strong>{gt text="Number of messages in"}</strong></dt>
    <dd>{gt text="Inbox"}: {$inbox}</dd>
    <dd>{gt text="Outbox"}: {$outbox}</dd>
    <dd>{gt text="Archive"}: {$archive}</dd>
</dl>

{include file="admin/footer.tpl"}
