{* $Id$ *}
{gt text="Messaging settings" assign=ictitle}
{include file="user/header.tpl" ictitle=$ictitle}
<h3>{$ictitle}</h3>

{form cssClass='z-form'}
{formvalidationsummary}


    {if $modvars.InterCom.messages_allow_emailnotification eq true}
    <fieldset>
        <legend>{gt text="E-mail notifications"}</legend>
        <div class="z-formrow">
            {formlabel for="ic_note" __text="Receive notification of new private messages via e-mail"}
            {formcheckbox id="ic_note"}
            <p class="z-formnote z-informationmsg">{gt text="Notice: Here you can specify whether or not you want to be notified via an e-mail message each time you receive a private message in your private messaging mailbox on the site."}</p>
        </div>
    </fieldset>
    {/if}

    {if $modvars.InterCom.messages_allow_autoreply eq true}
    <fieldset>
        <legend>{gt text="Automatic responses"}</legend>
        <div class="z-formrow">
            {formlabel for="ic_ar" __text="Send automatic responses"}
            {formcheckbox id="ic_ar"}
            <p class="z-formnote z-informationmsg">{gt text="Notice: Here you can specify whether or not you want an automatic response to be sent to the sender each time you receive a private message (possibly useful when you are on holiday, for instance)."}</p>
        </div>
        <div id="autoreply_text">
            <div class="z-formrow">
                {formlabel for="ic_art" __text="Message content"}
                {formtextinput textMode="multiline" id="ic_art" rows="5" cols="40"}
            </div>
        </div>
    </fieldset>
    {/if}

    {if $modvars.InterCom.messages_allow_emailnotification eq false && $modvars.InterCom.messages_allow_autoreply eq false}
    <div class="z-warningmsg">{gt text="Sorry! This feature has been disabled by the site administrator."}</div>
    {else}
    <div class="z-formbuttons z-buttons">
        {formbutton class="z-bt-ok" id="submit" commandName="submit" __text="Save"}
        {formbutton class="z-bt-cancel" id="cancel" commandName="cancel" __text="Cancel"}
    </div>
    {/if}
{/form}
{include file="user/footer.tpl"}
