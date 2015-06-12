{* $Id$ *}
<div class="col-lg-12" >
  <div class="col-lg-2">{gt text="Used"}</div>    
  <div class="col-lg-8">  
    <div class="progress">   
    <div class="progress-bar" role="progressbar" style="width: {$indicatorbar}%;" aria-valuenow="{$total.inbox.count}" aria-valuemin="0" aria-valuemax="{$total.inbox.limit}">
    {$indicatorbar}%
    </div>
    </div>
  </div>
  <div class="col-lg-2">   
        {if $boxtype eq 'inbox'}{$total.inbox.count} {gt text="of"} {$total.inbox.limit}   
        {elseif $boxtype eq 'outbox'}{$total.outbox.count} {gt text="of"} {$total.outbox.limit}
        {elseif $boxtype eq 'archive'}{$total.archive.count} {gt text="of"} {$total.archive.limit}   
        {/if}
  </div>
</div>