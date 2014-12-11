{* $Id$ *}

<div id="" class="">
    <fieldset>
        <legend>{gt text="Message preview"}</legend>

        <div class="">
            <label>{gt text="Recipient(s)"}</label>
            <p class="">
                {foreach from=$to_user item=item}
                <strong>{$item|profilelinkbyuname}</strong>
                {foreachelse}
                <strong>{gt text="Error! No recipient entered."}</strong>
                {/foreach}
            </p>
        </div>

        <div class="">
            <div class="">
                <label>{gt text="Subject line"}</label>
                <p class="">{$message.msg_subject}</p>
            </div>
            <div class="">
                <label>{gt text="Date"}</label>
                <p class="">{$smarty.now|dateformat:"datetimebrief"}</p>
            </div>
        </div>

        <div class="">
            <label>{gt text="Message text"}</label>
            <div class="">
                {$message.msg_text|nl2br}{* {$message.msg_text|nl2br|modcallhooks} *}
                {usergetvar name="_SIGNATURE" assign="signature"}
                {if $signature != ""}<div class="">{$signature|safehtml|nl2br}{* {$signature|safehtml|modcallhooks|nl2br} *}</div>{/if}
            </div>
        </div>

    </fieldset>
</div>
