<div class="crm-block crm-content-block" id="bootstrap-theme1">
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
        <div id="panel_{$tabName}">
          {include file=$tabValue.template part=$tabName}
        </div>
      {/if}
    {/foreach}
  </div>
  <div class="clear" style="padding-bottom: 10px;"></div>
  <div class="row mt-4">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <div class="card-text">
            {include file="CRM/Member/Page/DashBoard.tpl"}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4" id="bootstrap-theme">
    <div class="col">
      <div class="card">
        <div class="card-img-top bg-light">
          <div class="h4 p-2">
            <i class="fa fa-fw fa-boxes"></i>
            <span class="text-secondary">Inventory</span>
          </div>
        </div>
        <div class="card-body">
          <div class="card-text">
            <table class="table table-striped table-sm">
              <tbody><tr>
                <td>Device Model</td>
                <td></td>
                <td>Total</td>
                <td>Available</td>
                <td>Pending Orders</td>
                <td>Avg / month</td>
                <td>Days Left</td>
              </tr>
              {foreach from=$dashboardStats item=modelStat}
                <tr>
                  <td>{$modelStat.label}</td>
                  <td><span class="badge {$modelStat.badge}">{$modelStat.inventory_status}</span></td>
                  <td>{$modelStat.total_inventory}</td>
                  <td><a href="/admin/devices?device_model_id=1&amp;filter=new_inventory&amp;view=unassigned">{$modelStat.available_inventory}</a></td>
                  <td><a href="/admin/orders?item=17&amp;view=pending">{$modelStat.pendingOrder}</a></td>
                  <td>{$modelStat.monthly_avg}</td>
                  <td>{$modelStat.days_left}</td>
                </tr>
              {/foreach}
              </tbody></table>
            <div class="card-link">
              <a class="btn btn-sm btn-outline-success" href="/civicrm/inventory/upload-device"><i class="fa fa-fw fa-upload"></i> Upload Device Spreadsheet</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
{literal}
  <style>
    .row {
      display: flex;
      flex-wrap: wrap;
      margin-right: -15px;
      margin-left: -15px;
    }
    .mt-4 {
      margin-top: 1.5rem !important;
    }
    .col {
      flex-basis: 0;
      flex-grow: 1;
      max-width: 100%;
      position: relative;
      width: 100%;
      padding-right: 15px;
      padding-left: 15px;
    }
    .card {
      position: relative;
      display: flex;
      flex-direction: column;
      min-width: 0;
      word-wrap: break-word;
      background-color: #fff;
      background-clip: border-box;
      border: 1px solid rgba(0,0,0,0.125);
      border-radius: 0.25rem;
    }
    .card-body {
      flex: 1 1 auto;
      min-height: 1px;
      padding: 1.25rem;
    }
  </style>
{/literal}
