{* $Id$ *}

{include file="user/header.tpl" ictitle=$ictitle}
{pageaddvar name="javascript" value="modules/InterCom/javascript/intercom_newmsg.js"}

{if $msg_preview == 1}
{include file="user/previewpm.tpl"}
{/if}

{if $pmtype eq "reply"}
{capture assign="subject"}{replysubject subject=$message.msg_subject|replace:" ":""|truncate:3:"":true subjectclean=$message.msg_subject}{/capture}
{elseif $pmtype eq "forward"}
{capture assign="subject"}{forwardsubject subject=$message.msg_subject|replace:" ":""|truncate:3:"":true subjectclean=$message.msg_subject}{/capture}
{else}
{capture assign="subject"}{$message.msg_subject|safetext}{/capture}
{/if}

{if $pmtype eq "reply" && $allowbbcode eq 1}
{capture assign="messagetext"}[quote={$message.from_user} {gt text="wrote"} {$message.msg_unixtime|dateformat:datetimebrief}]{$message.msg_text}[/quote]{/capture}
{elseif $pmtype eq "forward"}
{capture assign="messagetext"}{$message.forward_text|safetext}{/capture}
{else}
{capture assign="messagetext"}{$message.msg_text|safetext}{/capture}
{/if}

<form id="post" class="z-form z-linear" action="{modurl modname="InterCom" type="user" func="submitpm"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        {if $pmtype eq "reply"}
        <input type="hidden" name="msg_id" value="{$message.msg_id}" />
        {/if}
        <input type="hidden" name="from_uid" value="{$currentuid|safetext}" />
        <input type="hidden" name="authid" value="{secgenauthkey module=Intercom}" />
        <fieldset>
            <legend>{$ictitle}</legend>
            <div class="z-formrow">
                <ol>
                    <li>
                        <label for="username">{gt text="Individual recipient(s)"}</label>
                        <div class="ic-inputbox-username">
                            <input id="username" name="to_user" type="text" value="{$to_user_string}" />
                            <div id="list-user">
                                <p class="default">{gt text="Notice: To send a private message to multiple individual recipients, enter their user names separated by commas."}</p>
                                <ul class="feed">
                                    {if !empty($to_user)}
                                    {foreach from=$to_user item=item}
                                    <li value="{$item|safetext}">{$item|safetext}</li>
                                    {/foreach}
                                    {/if}
                                </ul>
                            </div>
                        </div>
                    </li>
                </ol>
            </div>

            {if $pmtype eq "new" && $msgtogroups eq true}
            <div class="z-formrow">
                <ol>
                    <li>
                        <label for="groupname">{gt text="Group recipient(s)"}</label>
                        <div class="ic-inputbox-groupname">
                            <input id="groupname" name="to_group" type="text" />
                            <div id="list-group">
                                <p class="default">{gt text="Notice: To send a private message to multiple groups, enter the group names separated by commas."}</p>
                                <ul class="feed">
                                    {if !empty($to_group)}
                                    {foreach from=$to_group item=item}
                                    <li value="{$item|safetext}">{$item|safetext}</li>
                                    {/foreach}
                                    {/if}
                                </ul>
                            </div>
                        </div>
                    </li>
                </ol>
            </div>
            {/if}

            <script type="text/javascript">
                document.observe('dom:loaded', function() {
                    // init
                    tlist1 = new FacebookList('username', 'list-user',{fetchFile:document.location.pnbaseURL + 'ajax.php?module=InterCom'+'&'+'func=getusers'});
                    {{if $pmtype eq "new" && $msgtogroups eq true}}
                    tlist2 = new FacebookList('groupname', 'list-group',{fetchFile:document.location.pnbaseURL + 'ajax.php?module=InterCom'+'&'+'func=getgroups'});
                    {{/if}}
                });
            </script>

            <div class="z-formrow">
                <label for="subject">{gt text="Subject line"}</label>
                <span id="advice-required-subject" class="custom-advice" style="display:none">{gt text="Error! No subject line entered."}</span>
                <input id="subject" class="required ic-subject" name="subject" type="text" maxlength="255" value="{$subject}" />
            </div>

            <div class="z-formrow">
                <label for="message">{gt text="Message text"}</label>
                <span id="advice-required-message" class="custom-advice" style="display:none">{gt text="Error! No message text entered."}</span>
                <textarea id="message" class="required ic_texpand" name="message" cols="40" rows="5">{$messagetext}</textarea>
            </div>

            <div class="z-clearfix ic-margin">

                {if $allowhtml eq 1 || $allowsmilies eq 1}
                <div class="ic-floatleft">
                    {if $allowsmilies eq 1}
                    {modfunc modname='BBSmile' func='bbsmiles' textfieldid='message'}
                    {/if}
                    {if $allowhtml eq 1}
                    <br />
                    <label for="html">{gt text="Disable HTML mark-up"}</label>
                    <input type="checkbox" id="html" name="html" value="1" {if $html eq 1}checked="true"{/if} />
                    {/if}
                </div>
                {/if}

                {if $allowbbcode eq 1}
                <div class="ic-floatright">
                    {modfunc modname='BBCode' type='user' func='bbcodes' textfieldid=message images=0}
                </div>
                {/if}

            </div>

            {if $allowhtml eq 1}
            <div class="z-formnote z-warningmsg">{gt text="Permitted HTML tags:"}&nbsp;{allowedhtml}</div>
            {/if}

        </fieldset>

        <div class="z-formbuttons ic-buttons">
            {button mode="input" src=button_ok.gif set=icons/extrasmall name="mail_send" value="send" __alt="Send now" __title="Send now" __text="Send now"}
            {button mode="input" src=mail_find.gif set=icons/extrasmall name="mail_prev" value="preview" __alt="Preview message" __title="Preview message" __text="Preview message"}
            <a href="{modurl modname=InterCom type=user func=inbox}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"}</a>
        </div>

    </div>
</form>

<script type="text/javascript">
    var valid = new Validation('post');
</script>

{include file="user/footer.tpl"}
