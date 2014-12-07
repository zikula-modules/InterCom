{adminheader}
<div class="z-admin-content-pagetitle">
{icon type="gears" size="small"}
    <h3>{gt text="Utilities"}</h3>
</div>

<div class="z-formnote z-warningmsg">{gt text="Warning! Use these utilities with extreme caution. They will affect all site users. However, you will be prompted for confirmation first."}</div>
<ul>
    <li><a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="delete_all"}" onclick="return confirm('{gt text="Do you really want to delete ALL private messages?"}')">{gt text="Delete all messages in all inboxes, outboxes and archives"}</a></li>
    <li><a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="delete_inboxes"}" onclick="return confirm('{gt text="Do you really want to delete ALL private messages in ALL inboxes?"}')">{gt text="Delete all messages from all inboxes"}</a></li>
    <li><a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="delete_outboxes"}" onclick="return confirm('{gt text="Do you really want to delete ALL private messages in ALL outboxes?"}')">{gt text="Delete all messages from all outboxes"}</a></li>
    <li><a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="delete_archives"}" onclick="return confirm('{gt text="Do you really want to delete ALL private messages in ALL archives?"}')">{gt text="Delete all messages from all archives"}</a></li>
    <li><a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="optimize_db"}" onclick="return confirm('{gt text="Do you really want to optimize the database tables?"}')">{gt text="Optimize all tables"}</a> ({gt text="Notice: This deletes all messages from the database that are not in someone's inbox."})</li>
    <li><a href="{modurl modname="InterCom" type="admin" func="dbtools" operation="reset_to_defaults"}" onclick="return confirm('{gt text="Do you really want to reset all settings to their default values?"}')">{gt text="Reset all settings to default values"}</a> ({gt text="Notice: This resets all settings to their default values."})</li>
</ul>

{adminfooter}
