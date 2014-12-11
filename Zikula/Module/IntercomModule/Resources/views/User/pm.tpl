{include file="User/header.tpl" ictitle=$ictitle}


{if !empty($preview)}
{include file="User/previewpm.tpl"}
{/if}

{*if $pmtype eq "reply"}
{capture assign="subject"}{replysubject subject=$message.msg_subject|replace:" ":""|truncate:3:"":true subjectclean=$message.msg_subject}{/capture}
{elseif $pmtype eq "forward"}
{capture assign="subject"}{forwardsubject subject=$message.msg_subject|replace:" ":""|truncate:3:"":true subjectclean=$message.msg_subject}{/capture}
{else}
{capture assign="subject"}{$message.msg_subject|safetext}{/capture}
{/if}

{if $pmtype eq "reply" && $allowbbcode eq 1}
{capture assign="messagetext"}[quote={$message.from_user} {gt text="wrote"} {$message.msg_time|dateformat:datetimebrief}]{$message.msg_text}[/quote]{/capture}
{elseif $pmtype eq "forward"}
{capture assign="messagetext"}{$message.forward_text|safetext}{/capture}
{else}
{capture assign="messagetext"}{$message.msg_text|safetext}{/capture}
{/if*}

<form class="form" action="{route name="zikulaintercommodule_user_new"}" method="post" enctype="application/x-www-form-urlencoded">
        {*if $pmtype eq "reply"}
        <input type="hidden" name="msg_id" value="{$message.msg_id}" />
        {/if*}
        <input type="hidden" name="from_uid" value="{*$currentuid|safetext*}" />
        <input type="hidden" name="authid" value="{insert name='csrftoken'}" />
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">{$ictitle}</div>
  <div class="panel-body">        
        <div class="form-group col-lg-12 {if isset($errors.username)}has-error{/if}">
        <label class="control-label col-lg-12" for="username">{gt text="Individual recipient(s)"}</label>
        <div class="col-lg-12">
        <input class="form-control input-sm" type="text" id="username" name="recipient"  size="5" type="text" value="{if isset($to_user_string)}{$to_user_string}{/if}"/>
        </div>
        <p class="help-block col-lg-12">{gt text="Notice: To send a private message to multiple individual recipients, enter their user names separated by commas."}</p>
        {if isset($errors.username)}<p class="help-block col-lg-12">{$errors.username}</p>{/if} 
        </div> 
        {*
        <label for="username"></label>
                        <div class="ic-inputbox-username">
                            <input id="username" name="to_user" type="text" value="" />
                            <div id="list-user">
                                <p class="default"></p>
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
        *}
            {*if $pmtype eq "new" && $msgtogroups eq true*}
        <div class="form-group col-lg-12 {if isset($errors.groupname)}has-error{/if}">
        <label class="control-label col-lg-12" for="groupname">{gt text="Group recipient(s)"}</label>
        <div class="col-lg-12">
        <input class="form-control input-sm" type="text" id="groupname" name="groupname"  size="5" type="text" value="{if isset($to_user_string)}{$to_user_string}{/if}"/>
        </div>
        <p class="help-block col-lg-12">{gt text="Notice: To send a private message to multiple groups, enter the group names separated by commas."}</p>
        {if isset($errors.groupname)}<p class="help-block col-lg-12">{$errors.groupname}</p>{/if} 
        </div> {*            
            <div class="z-formrow">
                <ol>
                    <li>
                        <label for="groupname"></label>
                        <div class="ic-inputbox-groupname">
                            <input id="groupname" name="to_group" type="text" />
                            <div id="list-group">
                                <p class="default"></p>
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
            </div>*}
            
        <div class="form-group col-lg-12 {if isset($errors.subject)}has-error{/if}">
        <label class="control-label col-lg-12" for="subject">{gt text="Subject line"}</label>
        <div class="col-lg-12">
        <input class="form-control input-sm" type="text" id="subject" name="subject"  size="5" type="text" value="{if isset($to_user_string)}{$to_user_string}{/if}"/>
        </div>
        <p class="help-block col-lg-12">{gt text="Notice: To send a private message to multiple groups, enter the group names separated by commas."}</p>
        {if isset($errors.subject)}<p class="help-block col-lg-12">{$errors.subject}</p>{/if} 
        </div> 
        <div class="form-group col-lg-12 {if isset($errors.text)}has-error{/if}">
        <label class="control-label col-lg-12" for="text">{gt text="Welcome message text"}</label>
        <div class="col-lg-12">
        <textarea class="form-control" id="text" name="text" type="textarea">{*$text*}</textarea>
        </div>
        {if isset($errors.text)}<p class="help-block col-lg-12">{$errors.text}</p>{/if}
        </div>  
        {*if $allowhtml eq 1 || $allowsmilies eq 1}
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
                {/if*}

                {*if $allowbbcode eq 1}
                <div class="ic-floatright">
                    {modfunc modname='BBCode' type='user' func='bbcodes' textfieldid=message images=0}
                </div>
                {/if*}

            {*if $allowhtml eq 1}
            <div class="z-formnote z-warningmsg">{gt text="Permitted HTML tags:"}&nbsp;{intercom_allowedhtml}</div>
            {/if*}
    </div>
    <div class="panel-footer clearfix">
        <div class="form-group col-lg-12">
        <div class="btn-group pull-right">
            <a title="{gt text='Cancel'}"  href="{route name='zikulaintercommodule_user_index'}"   class="btn btn-default btn-sm"><i class="fa fa-close"> {gt text="Cancel"}</i></a>
            <button title="{gt text="Preview"}" type="submit" name="preview"   class="btn btn-default btn-sm"><i class="fa fa-eye"></i> {gt text="Preview"}</button>
            <button title="{gt text="Send"}"    type="submit" name="send" class="btn btn-default btn-sm"><i class="fa fa-save"></i> {gt text="Send"}</button>
        </div>
        </div>
    </div> 
    </div>
</form>

{include file="User/footer.tpl"}
