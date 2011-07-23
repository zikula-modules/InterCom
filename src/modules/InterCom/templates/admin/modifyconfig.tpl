{* $Id$ *}
{include file="admin/menu.tpl"}

<h2>{gt text="Settings"}</h2>

{form cssClass='z-form'}
{formvalidationsummary}

<fieldset>
    <legend>{gt text="General settings"}</legend>
    <div class="z-formrow">
        {formlabel for="messages_active" __text="Enable private messaging"}
        {formcheckbox id="messages_active" checked=$pncore.InterCom.messages_active}
    </div>
    <div id="configmaintenance">
        <div class="z-formrow">
            {formlabel for="messages_maintain" __text="Message to display when private messaging is disabled"}
            {formtextinput textMode="multiline" cols="40" rows="3" id="messages_maintain" text=$pncore.InterCom.messages_maintain}
        </div>
    </div>

    <div class="z-formrow">
        {formlabel for="messages_limitinbox" __text="Maximum number of messages in inbox"}
        {formintinput minValue="0" size="10" maxLength="10" id="messages_limitinbox" text=$pncore.InterCom.messages_limitinbox}
    </div>
    <div class="z-formrow">
        {formlabel for="messages_limitoutbox" __text="Maximum number of messages in outbox"}
        {formintinput minValue="0" size="10" maxLength="10" id="messages_limitoutbox" text=$pncore.InterCom.messages_limitoutbox}
    </div>
    <div class="z-formrow">
        {formlabel for="messages_limitarchive" __text="Maximum number of messages in archive"}
        {formintinput minValue="0" size="10" maxLength="10" id="messages_limitarchive" text=$pncore.InterCom.messages_limitarchive}
    </div>
    <div class="z-formrow">
        {formlabel for="messages_allowhtml" __text="Allow HTML mark-up in messages"}
        {formcheckbox id="messages_allowhtml" checked=$pncore.InterCom.messages_allowhtml}
    </div>
    <div class="z-formrow">
        {formlabel for="messages_allowsmilies" __text="Allow Smilies in Messages?"}
        {formcheckbox id="messages_allowsmilies" checked=$pncore.InterCom.messages_allowsmilies}
    </div>
    <div class="z-formrow">
        {formlabel for="messages_perpage" __text="Messages per page"}
        {formintinput minValue="5" maxValue="99" size="10" maxLength="10" id="messages_perpage" text=$pncore.InterCom.messages_perpage}
    </div>
</fieldset>

<fieldset>
    <legend>{gt text="Announcement settings"}</legend>
    <div class="z-formrow">
        {formlabel for="messages_userprompt_display" __text="Display announcement"}
        {formcheckbox id="messages_userprompt_display" checked=$pncore.InterCom.messages_userprompt_display}
    </div>
    <div id="configuserprompt">
        <div class="z-formrow">
            {formlabel for="messages_userprompt" __text="Content"}
            {formtextinput textMode="multiline" cols="40" rows="3" id="messages_userprompt" text=$pncore.InterCom.messages_userprompt}
            <div class="z-formnote z-informationmsg">{gt text="Notice: This message will be displayed above each user's inbox. You can post all kinds of information intended for your users."}</div>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>{gt text="Notification settings"}</legend>
    <div class="z-formrow">
        {formlabel for="messages_allow_emailnotification" __text="Allow e-mail notifications"}
        {formcheckbox id="messages_allow_emailnotification" checked=$pncore.InterCom.messages_allow_emailnotification}
    </div>
    <div id="configemailnotification">
        <div class="z-formrow">
            {formlabel for="messages_force_emailnotification" __text="Activate e-mail notifications for new users"}
            {formcheckbox id="messages_force_emailnotification" checked=$pncore.InterCom.messages_force_emailnotification}
            <div class="z-formnote z-informationmsg">{gt text="Notice: To activate the sending of e-mail notifications for new users, the 'InterCom' module hook has to be enabled for the 'Users' module. This also activates the sending of a welcome message to new users (refer to the setting below)."}</div>
        </div>
        <div class="z-formrow">
            {formlabel for="messages_mailsubject" __text="Subject line"}
            {formtextinput size="50" maxLength="100" id="messages_mailsubject" text=$pncore.InterCom.messages_mailsubject}
        </div>
        <div class="z-formrow">
            {formlabel for="messages_fromname" __text="Sender"}
            {formtextinput size="50" maxLength="100" id="messages_fromname" text=$pncore.InterCom.messages_fromname}
            <div class="z-formnote z-informationmsg">{gt text="Notice: If you leave the 'Sender' box blank then the site name will be used automatically."}</div>
        </div>
        <div class="z-formrow">
            {formlabel for="messages_from_email" text="Sender address"}
            {formtextinput size="50" maxLength="100" id="messages_from_email" text=$pncore.InterCom.messages_from_email}
            <div class="z-formnote z-informationmsg">{gt text="Notice: If you leave the 'Sender address' box blank then the administrator's address will be used automatically."}</div>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>{gt text="Automatic response settings"}</legend>
    <div class="z-formrow">
        {formlabel for="messages_allow_autoreply" __text="Enable automatic responses"}
        {formcheckbox id="messages_allow_autoreply" checked=$pncore.InterCom.messages_allow_autoreply}
        <div class="z-formnote z-informationmsg">{gt text="Notice: When the automatic response feature, users can enter a message to be sent as an automatic response to all incoming private messages."}</div>
    </div>
</fieldset>

<fieldset>
    <legend>{gt text="Welcome message settings"}</legend>
    <div class="z-formrow">
        {formlabel for="messages_createhookactive" __text="Send a welcome message to new users"}
        {formcheckbox id="messages_createhookactive" checked=$createhookactive}
    </div>
    <div id="configwelcome">
        <div class="z-formrow">
            {formlabel for="messages_welcomemessagesender" __text="Sender of welcome message"}
            {formtextinput size="40" maxLength="25" id="messages_welcomemessagesender" text=$pncore.InterCom.messages_welcomemessagesender}
            <div class="z-formnote z-informationmsg">{gt text="Notice: The welcome message sender must be one of the site's registered users."}</div>
        </div>
        <div class="z-formrow">
            {formlabel for="messages_welcomemessagesubject" __text="Welcome message subject line"}
            {formtextinput size="40" maxLength="100" id="messages_welcomemessagesubject" text=$pncore.InterCom.messages_welcomemessagesubject}
        </div>
        <div class="z-formrow">
            {formlabel for="messages_welcomemessage" __text="Welcome message text"}
            {formtextinput textMode="multiline" cols="40" rows="3" id="messages_welcomemessage" text=$pncore.InterCom.messages_welcomemessage}
        </div>
        {if $intlwelcomemessage neq ""}
        <div class="z-formrow">
            {formlabel for="messages_intlwelcomemessage" __text="Welcome message for selected language"}
            {formtextinput textMode="multiline" readonly="1" cols="40" rows="6" id="messages_intlwelcomemessage" text=$intlwelcomemessage}
        </div>
        <div class="z-formnote z-informationmsg">{gt text="Notice: The following place holders are supported:<ul><li>%username% for the person's user name</li><li>%realname% for the person's real name</li><li>%sitename% for the site name</li></ul>If the text begins with an underscore ('_'), it will be processed like a language define. The language define should be placed in 'modules/InterCom/pnlang/xxx/welcome.php' (where 'xxx' is the language code)."}</div>
        {/if}
        <div class="z-formrow">
            {formlabel for="messages_savewelcomemessage" __text="Save welcome message in user outbox"}
            {formcheckbox id="messages_savewelcomemessage" checked=$pncore.InterCom.messages_savewelcomemessage}
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>{gt text="Spam prevention settings"}</legend>
    <div class="z-formrow">
        {formlabel for="messages_protection_on" __text="Enable spam prevention"}
        {formcheckbox id="messages_protection_on" checked=$pncore.InterCom.messages_protection_on}
    </div>
    <div id="configspam">
        <div class="z-formrow">
            {formlabel for="messages_protection_time" __text="Measured time span (in minutes)"}
            {formintinput minValue="1" size="4" maxLength="2" id="messages_protection_time" text=$pncore.InterCom.messages_protection_time}
        </div>
        <div class="z-formrow">
            {formlabel for="messages_protection_amount" __text="Measured number of messages"}
            {formintinput minValue="1" size="4" maxLength="2" id="messages_protection_amount" text=$pncore.InterCom.messages_protection_amount}
        </div>
        <div class="z-formrow">
            {formlabel for="messages_protection_mail" __text="Send admin notification of spam messaging via e-mail"}
            {formcheckbox id="messages_protection_mail" checked=$pncore.InterCom.messages_protection_mail}
        </div>
        <div class="z-formnote z-informationmsg">{gt text="Notice: With the spam prevention feature, you can specify the number of messages that a user can send within a certain time span before the spam prevention feature is triggered. When a message is send to multiple recipients, each recipient is counted as one message."}</div>
    </div>
</fieldset>

<div class="z-formbuttons ic-buttons">
    {formbutton id="submit" commandName="submit" __text="Save"}
</div>

{/form}

<script type="text/javascript">
    /* <![CDATA[ */
    $('configmaintenance').hide();
    $('messages_active').observe('click', icCheckMaintenanceEntry);
    $('messages_active').observe('keyup', icCheckMaintenanceEntry);
    icCheckMaintenanceEntry();

    $('configuserprompt').hide();
    $('messages_userprompt_display').observe('click', icCheckUserpromptEntry);
    $('messages_userprompt_display').observe('keyup', icCheckUserpromptEntry);
    icCheckUserpromptEntry();

    $('configemailnotification').hide();
    $('messages_allow_emailnotification').observe('click', icCheckMailNotificationEntry);
    $('messages_allow_emailnotification').observe('keyup', icCheckMailNotificationEntry);
    icCheckMailNotificationEntry();

    $('configwelcome').hide();
    $('messages_createhookactive').observe('click', icCheckWelcomeEntry);
    $('messages_createhookactive').observe('keyup', icCheckWelcomeEntry);
    icCheckWelcomeEntry();

    $('configspam').hide();
    $('messages_protection_on').observe('click', icCheckSpamEntry);
    $('messages_protection_on').observe('keyup', icCheckSpamEntry);
    icCheckSpamEntry();

    function icCheckMaintenanceEntry() {
        if ($('messages_active').checked == false) {
            Effect.BlindDown('configmaintenance');
        } else {
            Effect.BlindUp('configmaintenance');
        }
    }

    function icCheckUserpromptEntry() {
        if ($('messages_userprompt_display').checked == true) {
            Effect.BlindDown('configuserprompt');
        } else {
            Effect.BlindUp('configuserprompt');
        }
    }

    function icCheckMailNotificationEntry() {
        if ($('messages_allow_emailnotification').checked == true) {
            Effect.BlindDown('configemailnotification');
        } else {
            Effect.BlindUp('configemailnotification');
        }
    }

    function icCheckWelcomeEntry() {
        if ($('messages_createhookactive').checked == true) {
            Effect.BlindDown('configwelcome');
        } else {
            Effect.BlindUp('configwelcome');
        }
    }

    function icCheckSpamEntry() {
        if ($('messages_protection_on').checked == true) {
            Effect.BlindDown('configspam');
        } else {
            Effect.BlindUp('configspam');
        }
    }


    /* ]]> */
</script>

{include file="admin/footer.tpl"}
