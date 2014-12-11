{* $Id$ *}
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">{gt text="Status"}</div>
  <div class="panel-body">
    <div class="progress">
    <div class="progress-bar" role="progressbar" style="width: {$indicatorbar}%;" aria-valuenow="{$getmessagecount.totalin}" aria-valuemin="0" aria-valuemax="{$getmessagecount.limitin}">
    <span class="sr-only">60% Complete</span>
    </div>
    </div>
    <ul>
    <li class="{$getmessagecount.inboxlimitclass}"><strong>{gt text="Inbox"}:</strong> {gt text="Used"} <span id="ic-totalin">{$getmessagecount.totalin}</span> {gt text="of"} {$getmessagecount.limitin}</li>
    <li class="{$getmessagecount.outboxlimitclass}"><strong>{gt text="Outbox"}:</strong> {gt text="Used"} <span id="ic-totalout">{$getmessagecount.totalout}</span> {gt text="of"} {$getmessagecount.limitout}</li>
    <li class="{$getmessagecount.archivelimitclass}"><strong>{gt text="Archive"}:</strong> {gt text="Used"} <span id="ic-totalarchive">{$getmessagecount.totalarchive}</span> {gt text="of"} {$getmessagecount.limitarchive}</li>
    </ul>
  </div>
</div>