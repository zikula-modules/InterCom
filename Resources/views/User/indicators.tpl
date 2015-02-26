{* $Id$ *}
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading"><i class="fa fa-tasks"> </i> {$ictitle} {gt text="status"} {gt text="used"} 
  {if $boxtype eq 'inbox'}{$total.inbox.count} {gt text="of"} {$total.inbox.limit}   
  {elseif $boxtype eq 'outbox'}{$total.outbox.count} {gt text="of"} {$total.outbox.limit}
  {elseif $boxtype eq 'archive'}{$total.archive.count} {gt text="of"} {$total.archive.limit}   
  {/if}    
  </div>
  <div class="panel-body">
    <div class="progress">
    <div class="progress-bar" role="progressbar" style="width: {$indicatorbar}%;" aria-valuenow="{$total.inbox.count}" aria-valuemin="0" aria-valuemax="{$total.inbox.limit}">
    <span class="sr-only">{$indicatorbar}%</span>
    </div>
    </div>
  </div>
</div>