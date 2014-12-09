{adminheader}
<h3>
    <span class="fa fa-wrench"></span>
    {gt text="Informations"}
</h3>

<div class="row">
<div class="col-lg-6">     
<h4>{gt text="Number of messages"}</h4>
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
<div class="col-lg-6">
    
    
</div>    
</div>    
{adminfooter}