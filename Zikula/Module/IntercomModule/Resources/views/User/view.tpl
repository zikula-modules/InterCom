{* $Id$ *}

{include file="User/header.tpl" ictitle=$ictitle}

{if $boxtype eq "inbox"}
{*assign var="indicatorbar" value=$getmessagecount.indicatorbarin*}
{elseif $boxtype eq "outbox"}
{*assign var="indicatorbar" value=$getmessagecount.indicatorbarout*}
{elseif $boxtype eq "archive"}
{*assign var="indicatorbar" value=$getmessagecount.indicatorbararchive*}
{/if}

{modgetvar module="InterCom" name="messages_userprompt_display" assign=display}
{if $display == 1}
<div class="alert alert-info">
    {modgetvar module="InterCom" name="messages_userprompt" assign=userprompt}
    {$userprompt|safehtml}
</div>
{/if}

{*if ($boxtype eq "inbox" && $autoreply == 1) || $getmessagecount.totalin>$getmessagecount.limitin || $getmessagecount.totalout>$getmessagecount.limitout || $getmessagecount.totalarchive>$getmessagecount.limitarchive}
{if $boxtype eq "inbox"   && $autoreply == 1}<div class="alert alert-warning">{gt text="You have activated the function 'automatic reply'."}<br /></div>{/if}
{if $boxtype eq "inbox"   && $getmessagecount.totalin>$getmessagecount.limitin}<div class="alert alert-warning">{gt text="Please delete some of the messages in your inbox, you've reached the limit."}<br />{gt text="New Messages will be displayed first after you have delete some of the old."}<br /></div>{/if}
{if $boxtype eq "outbox"  && $getmessagecount.totalout>$getmessagecount.limitout}<div class="alert alert-warning">{gt text="There are too many message in the outbox. Please delete some messages in the outbox, so that you can send new messages."}<br /></div>{/if}
{if $boxtype eq "archive" && $getmessagecount.indicatorbararchive>$getmessagecount.limitarchive}<div class="alert alert-warning">{gt text="There are too many messages in the archive. Please delete some messages in the archive, so that you can again store messages there."}<br /></div>{/if}
{/if*}

{if $messagearray == FALSE}
<div class="alert alert-info">{gt text="You currently have no messages."}</div>
{else}
{include file="User/`$boxtype`_messages.tpl"}
{/if}

{include file="User/indicators.tpl"}
{include file="User/footer.tpl"}
