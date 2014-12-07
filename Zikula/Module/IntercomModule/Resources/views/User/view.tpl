{* $Id$ *}

{include file="user/header.tpl" ictitle=$ictitle}

{if $boxtype eq "inbox"}
{pageaddvar name="javascript" value="modules/InterCom/javascript/intercom_inbox.js"}
{assign var="indicatorbar" value=$getmessagecount.indicatorbarin}
{elseif $boxtype eq "outbox"}
{pageaddvar name="javascript" value="modules/InterCom/javascript/intercom_outbox.js"}
{assign var="indicatorbar" value=$getmessagecount.indicatorbarout}
{elseif $boxtype eq "archive"}
{pageaddvar name="javascript" value="modules/InterCom/javascript/intercom_archive.js"}
{assign var="indicatorbar" value=$getmessagecount.indicatorbararchive}
{/if}

{modgetvar module="InterCom" name="messages_userprompt_display" assign=display}
{if $display == 1}
<div class="z-informationmsg">
    {modgetvar module="InterCom" name="messages_userprompt" assign=userprompt}
    {$userprompt|safehtml}
</div>
{/if}

{if ($boxtype eq "inbox" && $autoreply == 1) || $getmessagecount.totalin>$getmessagecount.limitin || $getmessagecount.totalout>$getmessagecount.limitout || $getmessagecount.totalarchive>$getmessagecount.limitarchive}
{if $boxtype eq "inbox"   && $autoreply == 1}<div class="z-warningmsg">{gt text="You have activated the function 'automatic reply'."}<br /></div>{/if}
{if $boxtype eq "inbox"   && $getmessagecount.totalin>$getmessagecount.limitin}<div class="z-warningmsg">{gt text="Please delete some of the messages in your inbox, you've reached the limit."}<br />{gt text="New Messages will be displayed first after you have delete some of the old."}<br /></div>{/if}
{if $boxtype eq "outbox"  && $getmessagecount.totalout>$getmessagecount.limitout}<div class="z-warningmsg">{gt text="There are too many message in the outbox. Please delete some messages in the outbox, so that you can send new messages."}<br /></div>{/if}
{if $boxtype eq "archive" && $getmessagecount.indicatorbararchive>$getmessagecount.limitarchive}<div class="z-warningmsg">{gt text="There are too many messages in the archive. Please delete some messages in the archive, so that you can again store messages there."}<br /></div>{/if}
{/if}

{if $messagearray == FALSE}
<div class="z-informationmsg">{gt text="You currently have no messages."}</div>
{else}
{include file="user/`$boxtype`_messages.tpl"}
{/if}

<div class="z-form">
    <fieldset>
        <legend>{gt text="Status"}</legend>
        {include file="user/indicators.tpl"}
    </fieldset>
</div>

{include file="user/footer.tpl"}
