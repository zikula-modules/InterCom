{* $Id$ *}
{gt text="Maintenance settings" assign=ictitle}
{include file="user/header.tpl" ictitle=$ictitle}

<div class="z-warningmsg">
    <h3>{$ictitle}</h3>
    {modgetvar module="InterCom" name="messages_maintain" assign=maintain}
    <p>{$maintain|safehtml}</p>
</div>

{include file="user/footer.tpl"}