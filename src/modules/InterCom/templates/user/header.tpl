{pageaddvar name="javascript" value="prototype"}
{pageaddvar name="javascript" value="javascript/ajax/validation.min.js"}
{pageaddvar name="javascript" value="modules/InterCom/javascript/intercom.js"}
{pageaddvar name="javascript" value="modules/InterCom/javascript/facebooklist.js"}

{pageaddvar name="javascript" value="modules/InterCom/javascript/texpand.packed.js"}
{pageaddvar name="stylesheet" value="modules/InterCom/style/facebooklist.css"}
{pagesetvar name=title value=$ictitle}
<script type="text/javascript">
    //  ![CDATA[[
    Ajax.Responders.register({
        onCreate: function(){ Element.show('spinner')},
        onComplete: function(){Element.hide('spinner')}
    });
    // ]]
</script>
<div id="intercom">
    <h2>{gt text="Private messaging"}
        <img alt="spinner" id="spinner" src="modules/InterCom/images/ajax-loader.gif"
        style="display:none;" />
    </h2>

    {modulelinks modname='InterCom' type='user'}
    {insert name="getstatusmsg"}