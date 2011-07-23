{* $Id$ *}

{pageaddvar name="stylesheet" value="modules/InterCom/style/style.css"}
{pageaddvar name="javascript" type="prototype"}

<div id="ic-newmessages" class="intercomblock">
    <script type="text/javascript">
        // <![CDATA[
        new Ajax.PeriodicalUpdater(
        'ic-newmessages',
        document.location.pnbaseURL + 'ajax.php',
        {
            method: 'get',
            parameters: 'module=InterCom&func=getmessages',
            frequency: 60
        });
        // ]]>
    </script>

    <noscript>
        {include file="ajax/getmessages.tpl"}
    </noscript>
</div>
