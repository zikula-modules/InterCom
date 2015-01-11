{* $Id$ *}
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading"><i class="fa fa-tasks"> </i> {$ictitle} {gt text="status"} {gt text="used"} 
  {if $boxtype eq 'inbox'}{$getmessagecount.totalin} {gt text="of"} {$getmessagecount.limitin}   
  {elseif $boxtype eq 'outbox'}{$getmessagecount.totalout} {gt text="of"} {$getmessagecount.limitout}
  {elseif $boxtype eq 'archive'}{$getmessagecount.totalarchive} {gt text="of"} {$getmessagecount.limitarchive}   
  {/if}    
  </div>
  <div class="panel-body">
    <div class="progress">
    <div class="progress-bar" role="progressbar" style="width: {$indicatorbar}%;" aria-valuenow="{$getmessagecount.totalin}" aria-valuemin="0" aria-valuemax="{$getmessagecount.limitin}">
    <span class="sr-only">{$indicatorbar}%</span>
    </div>
    </div>
  </div>
</div>