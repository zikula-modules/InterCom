{adminheader}
<div class="z-gap">
    <h3>{gt text="Statistics"}</h3>
</div>

<div class="row">
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{gt text="Number of messages in"}</h3>
  </div>
  <div class="panel-body">
    <ul class="list-group">
  <li class="list-group-item">
    <span class="badge">{$inbox}</span>
    {gt text="Inbox"}
  </li>
  <li class="list-group-item">
    <span class="badge">{$outbox}</span>
    {gt text="Outbox"}
  </li>
  <li class="list-group-item">
    <span class="badge">{$archive}</span>
    {gt text="Archive"}
  </li>  
</ul>
  </div>
</div>    
</div>    
{adminfooter}