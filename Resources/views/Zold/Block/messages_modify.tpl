{* $Id$ *}
<div class="z-formrow">
    <label for="pn_template">{gt text="Template file name" domain="module_intercom"}</label>
    <input value="{$vars.pn_template|safetext}" maxlength="130" size="60" name="pn_template" id="pn_template" type="text" />
    <label>{gt text="Examples" domain="module_intercom"}:</label>
    <ul>
        <li>messages.tpl</li>
        <li>messages2.tpl</li>
        <li>messages3.tpl</li>
        <li>../ajax/ajax.tpl</li>
    </ul>
</div>