<!-- Tab links -->
<h2>Shipment {$shipmentID}
  <div class="muted">
    <span class="date" title="{$shipmentInfo.created_date}">{$shipmentInfo.created_date|crmDate:'%Y-%m-%d'}</span>
  </div>
</h2>

<div class="crm-block crm-content-block" >
  <div id="mainTabContainer">
    <ul>
      {foreach from=$tabHeader key=tabName item=tabValue}
        <li id="tab_{$tabName}" class="crm-tab-button ui-corner-all{if !$tabValue.valid} disabled{/if} {$tabValue.class}" {$tabValue.extra}>
          {if $tabValue.active}
            <a href="{if $tabValue.template}#panel_{$tabName}{else}{$tabValue.link|smarty:nodefaults}{/if}" title="{$tabValue.title|escape}{if !$tabValue.valid} ({ts}disabled{/ts}){/if}">
              {if $tabValue.icon}<i class="{$tabValue.icon}"></i>{/if}
              <span>{$tabValue.title}</span>
              {if is_numeric($tabValue.count)}<em>{$tabValue.count}</em>{/if}
            </a>
          {else}
            <span {if !$tabValue.valid} title="{ts}disabled{/ts}"{/if}>{$tabValue.title}</span>
          {/if}
        </li>
      {/foreach}
    </ul>
    {foreach from=$tabHeader key=tabName item=tabValue}
      {if $tabValue.template}
        <div id="panel_{$tabName}" style="padding-top: 20px;">
          {include file=$tabValue.template}
        </div>
      {/if}
    {/foreach}
  </div>
</div>

