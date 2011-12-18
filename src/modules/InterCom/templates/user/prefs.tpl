{* $Id$ *}
{gt text="Messaging settings" assign=ictitle}
{include file="user/header.tpl" ictitle=$ictitle}
{pageaddvar name="javascript" value="modules/InterCom/javascript/intercom_prefs.js"}
<h3>{$ictitle}</h3>

<form id="post" class="z-form" action="{modurl modname=InterCom type=user func=modifyprefs}" method="post" enctype="multipart/form-data">
    <div>
        <input type="hidden" name="authid" value="{secgenauthkey module="InterCom"}" />

        {if $pncore.InterCom.messages_allow_emailnotification eq true}
        <fieldset>
            <legend>{gt text="E-mail notifications"}</legend>
            <div class="z-formrow">
                <label for="intercom_email_notification">{gt text="Receive notification of new private messages via e-mail"}</label>
                <select name="intercom_email_notification" id="intercom_email_notification" size="1">
                    <option value="0" {if $email_notification == 0}selected="selected"{/if}>{gt text="No"}</option>
                    <option value="1" {if $email_notification == 1}selected="selected"{/if}>{gt text="Yes"}</option>
                </select>
                <p class="z-formnote z-informationmsg">{gt text="Notice: Here you can specify whether or not you want to be notified via an e-mail message each time you receive a private message in your private messaging mailbox on the site."}</p>
            </div>
        </fieldset>
        {/if}

        {if $pncore.InterCom.messages_allow_autoreply eq true}
        <fieldset>
            <legend>{gt text="Automatic responses"}</legend>
            <div class="z-formrow">
                <label for="intercom_autoreply">{gt text="Send automatic responses"}</label>
                <select name="intercom_autoreply" id="intercom_autoreply" size="1">
                    <option value="0" {if $autoreply == 0}selected="selected"{/if}>{gt text="No"}</option>
                    <option value="1" {if $autoreply == 1}selected="selected"{/if}>{gt text="Yes"}</option>
                </select>
                <p class="z-formnote z-informationmsg">{gt text="Notice: Here you can specify whether or not you want an automatic response to be sent to the sender each time you receive a private message (possibly useful when you are on holiday, for instance)."}</p>
            </div>
            <div id="autoreply_text">
                <div class="z-formrow">
                    <label for="intercom_autoreply_text">{gt text="Message content"}</label>
                    <textarea id="intercom_autoreply_text" rows="5" cols="40" class="ic_texpand" name="intercom_autoreply_text">{$autoreply_text}</textarea>
                </div>
            </div>
        </fieldset>
        {/if}

        {if $pncore.InterCom.messages_allow_emailnotification eq false && $pncore.InterCom.messages_allow_autoreply eq false}
        <div class="z-warningmsg">{gt text="Sorry! This feature has been disabled by the site administrator."}</div>
        {else}
        <div class="z-formbuttons ic-buttons">
            {button src=button_ok.gif set=icons/extrasmall __alt="Save" __title="Save" __text="Save"}
            <a href="{modurl modname=InterCom type=user func=inbox}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
        {/if}
    </div>
</form>
{include file="user/footer.tpl"}