{adminheader}
<h3>
    <span class="fa fa-wrench"></span>
    {gt text="Utilities"}
</h3>

<div class="row">
<div class="col-lg-2">
<div class="alert alert-warning"> <strong>{gt text="Warning!"}</strong> {gt text="Use these utilities with extreme caution. They will affect all site users. However, you will be prompted for confirmation first."}</div>        
</div>    
<div class="col-lg-10">
<div class="list-group">
  <a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="delete_all"}" onclick="return confirm('{gt text="Do you really want to delete ALL private messages?"}')" class="list-group-item">
      <h4 class="list-group-item-heading">{gt text="Delete all messages in all inboxes, outboxes and archives"}          
      </h4>
    <p class="list-group-item-text">...</p>
  </a>
  <a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="delete_inboxes"}" onclick="return confirm('{gt text="Do you really want to delete ALL private messages in ALL inboxes?"}')" class="list-group-item">
      <h4 class="list-group-item-heading">{gt text="Delete all messages from all inboxes"}          
      </h4>
    <p class="list-group-item-text">...</p>
  </a>      
  <a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="delete_outboxes"}" onclick="return confirm('{gt text="Do you really want to delete ALL private messages in ALL outboxes?"}')" class="list-group-item">
      <h4 class="list-group-item-heading">{gt text="Delete all messages from all outboxes"}          
      </h4>
    <p class="list-group-item-text">...</p>
  </a>      
  <a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="delete_archives"}" onclick="return confirm('{gt text="Do you really want to delete ALL private messages in ALL archives?"}')" class="list-group-item">
      <h4 class="list-group-item-heading">{gt text="Delete all messages from all archives"}          
      </h4>
    <p class="list-group-item-text">...</p>
  </a>
<a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="optimize_db"}" onclick="return confirm('{gt text="Do you really want to optimize the database tables?"}')" class="list-group-item">
      <h4 class="list-group-item-heading">{gt text="Optimize all tables"}          
      </h4>
    <p class="list-group-item-text">{gt text="Notice: This deletes all messages from the database that are not in someone's inbox."}</p>
  </a>
  <a href="{route name='zikulaintercommodule_admin_tools' operation='reset_to_defaults' }" class="list-group-item">
      <h4 class="list-group-item-heading">{gt text="Reset all settings to default values"}         
      </h4>
    <p class="list-group-item-text">{gt text="Notice: This resets all settings to their default values."}</p>
  </a>      
</div>
</div>
</div>
{adminfooter}
