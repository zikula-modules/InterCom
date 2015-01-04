
{*if !isset($modvars.ZikulaIntercomModule.disable_ajax) || !$modvars.ZikulaIntercomModule.disable_ajax}
{/if*}
{pagesetvar name=title value=$ictitle}
<div id="intercom">
    <h2>{gt text="Private messaging"}
    </h2>

    {modulelinks modname='ZikulaIntercomModule' type='user'}
    {insert name="getstatusmsg"}