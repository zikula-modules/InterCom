{if $action eq "preview"}
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
      <i class="fa fa-eye"> </i>        
    {gt text="Preview"}
  </div>
  <div class="panel-body">
        <div class=" col-lg-12">{gt text="To"} <strong>{if isset($recipients.groups)}{$recipients.groups}{else}{$recipients.names}{/if}</strong>
        </div>
        <div class=" col-lg-12">
        {gt text="Subject"} <strong>{$subject}</strong>       
        </div>
        <div class=" col-lg-12">          
        {$text}
        </div>
            {*if $message.signature != ""}<div class="signature z-formnote">{$message.signature|safehtml|nl2br}{* {$message.signature|safehtml|nl2br} }</div>{/if*}
    </div>     
</div>
{/if}