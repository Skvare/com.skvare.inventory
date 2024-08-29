{* HEADER *}

<div id="help">
  <p>{ts}Select your device and submit the request for a replacement.{/ts}</p>
</div>
{if !empty($productList)}
{foreach from=$elementNames item=elementName}
  <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">{$form.$elementName.html}</div>
    <div class="clear"></div>
  </div>
{/foreach}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
{else}
  <div class="messages status no-popup">You do not have any active device.</div>
{/if}
