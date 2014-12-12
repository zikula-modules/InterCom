{adminheader}
<h3>
    <span class="fa fa-wrench"></span>
    {gt text="Utilities"}
</h3>
<div class="row">
<div class="col-lg-3">
<div class="alert alert-warning"> <h3>{gt text="Warning!"}</h3> {gt text="Use these utilities with extreme caution. They will affect all site users. However, you will be prompted for confirmation first."}</div>        
</div>    
<div class="col-lg-9 pull-right">
<div class="list-group">
  <a href="{route name='zikulaintercommodule_admin_tools' operation='check_integrity_users' }" class="list-group-item">
    <i class="fa fa-group fa-2x pull-right text-muted"></i>   
    <h4 class="list-group-item-heading"> {gt text="Integrity users"}</h4>
    <p class="list-group-item-text">{gt text="This tool check sender and recipient integrity against users table and sets null for those users that are not present"}</p>
  </a>
  <a href="{route name='zikulaintercommodule_admin_tools' operation='check_integrity_orphaned' }" class="list-group-item">
    <i class="fa fa-envelope fa-2x pull-right text-muted"></i>       
    <h4 class="list-group-item-heading"> {gt text="Orphaned messages"}</h4>
    <p class="list-group-item-text">{gt text="This tool removes orphaned messages"}</p>
  </a>
  <a href="{route name='zikulaintercommodule_admin_tools' operation='check_integrity_inbox' }" class="list-group-item">
     <i class="fa fa-inbox fa-2x pull-right text-muted"></i> <h4 class="list-group-item-heading">{gt text="Check inboxes"}</h4>
    <p class="list-group-item-text">{gt text="This tool check inboxes integity"}</p>
  </a>
  <a href="{route name='zikulaintercommodule_admin_tools' operation='check_integrity_outbox' }" class="list-group-item">
    <i class="fa fa-upload fa-2x pull-right text-muted"></i> <h4 class="list-group-item-heading"> {gt text="Check outboxes"}</h4>
    <p class="list-group-item-text">{gt text="This tool check outboxes integrity"}</p>
  </a>
  <a href="{route name='zikulaintercommodule_admin_tools' operation='reset_to_defaults' }" class="list-group-item">
    <i class="fa fa-refresh fa-2x pull-right text-muted"></i> <h4 class="list-group-item-heading">  {gt text="Reset all settings to default values"}</h4>
    <p class="list-group-item-text">{gt text="Notice: This resets all settings to their default values."}</p>
  </a> 
  <a href="{route name='zikulaintercommodule_admin_tools' operation='delete_inbox' }" class="list-group-item">
    <span class="fa-stack fa-lg pull-right fa-1x">
    <i class="fa fa-eraser fa-stack-2x text-muted"></i>
    <i class="fa fa-exclamation fa-stack-1x text-danger text-left"></i>
    </span> 
    <h4 class="list-group-item-heading"> {gt text="Delete all messages from inboxes"}</h4>
    <p class="list-group-item-text">{gt text="Notice: This resets all settings to their default values."}</p>
  </a>      
  <a href="{route name='zikulaintercommodule_admin_tools' operation='delete_outbox' }" class="list-group-item">
     <span class="fa-stack fa-lg pull-right fa-1x">
    <i class="fa fa-eraser fa-stack-2x text-muted"></i>
    <i class="fa fa-exclamation fa-stack-1x text-danger text-left"></i>
    </span> 
     <h4 class="list-group-item-heading"> {gt text="Delete all messages from outboxes"}</h4>
    <p class="list-group-item-text">{gt text="Notice: This resets all settings to their default values."}</p>
  </a>      
    <a href="{route name='zikulaintercommodule_admin_tools' operation='delete_stored' }" class="list-group-item">
    <span class="fa-stack fa-lg pull-right fa-1x">
    <i class="fa fa-eraser fa-stack-2x text-muted"></i>
    <i class="fa fa-exclamation fa-stack-1x text-danger text-left"></i>
    </span> 
    <h4 class="list-group-item-heading"> {gt text="Delete all messages from archives"}</h4>
    <p class="list-group-item-text">{gt text="Notice: This resets all settings to their default values."}</p>
  </a>
  <a href="{route name='zikulaintercommodule_admin_tools' operation='delete_all' }" class="list-group-item">
     <span class="fa-stack fa-lg pull-right fa-1x">
    <i class="fa fa-trash-o fa-stack-2x text-muted"></i>
    <i class="fa fa-adjust fa-exclamation fa-stack-1x text-danger text-left"></i>
    </span>
     <h4 class="list-group-item-heading"> {gt text="Delete all messages in all inboxes, outboxes and archives"}</h4>
    <p class="list-group-item-text">{gt text="Notice: This resets all settings to their default values."}</p>
  </a>     
</div>
</div>
<div class="col-lg-3">
<div class="alert alert-info">
    <h3>{gt text="Integrity checks and status"}</h3>
    <p class="">{gt text="Senders"}:{if $users_check.sender gt 0} {$users_check.sender} <i class="fa fa-warning text-danger"></i> {else} <i class="fa fa-check text-success"></i> {/if}</p>
    <p class="">{gt text="Recipients"}:{if $users_check.recipient gt 0} {$users_check.recipient} <i class="fa fa-warning text-danger"></i> {else} <i class="fa fa-check text-success"></i> {/if}</p>
    <p class="">{gt text="Orphaned"}:{if $orphaned gt 0} {$orphaned} <i class="fa fa-warning text-danger"></i> {else} <i class="fa fa-check text-success"></i> {/if}</p>
    <p class="">{gt text="Inboxes"}:{if $inboxes gt 0} {$inboxes} <i class="fa fa-warning text-danger"></i> {else} <i class="fa fa-check text-success"></i> {/if}</p>
    <p class="">{gt text="Outboxes"}:{if $outboxes gt 0} {$outboxes}  <i class="fa fa-warning text-danger"></i> {else} <i class="fa fa-check text-success"></i> {/if}</p>
    <p class="">{gt text="Archives"}:{if $archives gt 0} {$archives} <i class="fa fa-warning text-danger"></i> {else} <i class="fa fa-check text-success"></i>{/if}</p>
</div>        
</div>   
</div>
{adminfooter}
