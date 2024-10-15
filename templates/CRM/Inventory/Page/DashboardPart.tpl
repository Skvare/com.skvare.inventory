{if $part eq 'device'}
  <div style="padding-bottom: 10px;padding-top: 10px;">
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/device-model-list" q="reset=1"}'><i class="crm-i fa-list" aria-hidden="true"></i> {ts}Device Models{/ts}</a>
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/device-model" q="reset=1"}'><i class="crm-i fa-plus" aria-hidden="true"></i> {ts}Add Device Models{/ts}</a>
    <br/>

    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/product" q="reset=1"}'><i class="crm-i fa-plus" aria-hidden="true"></i> {ts}Device Listing (tabs){/ts}</a>
    <br/>
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/device-list" q="reset=1"}'><i class="crm-i fa-list" aria-hidden="true"></i> {ts}Device List{/ts}</a>
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/device-unassigned" q="reset=1"}'><i class="crm-i fa-list" aria-hidden="true"></i> {ts}Device Un-Assigned List{/ts}</a>
    <br/>
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/device-from" q="reset=1"}'><i class="crm-i fa-plus" aria-hidden="true"></i> {ts}Add Device{/ts}</a>
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/product-replacement-request?reset=1" q="reset=1"}'><i class="crm-i fa-plus" aria-hidden="true"></i> {ts}Device Replacement request{/ts}</a>
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/device-model-membership?reset=1" q="reset=1"}'><i class="crm-i fa-plus" aria-hidden="true"></i> {ts}Product and Membership Mapping{/ts}</a>
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/admin/member/membershipType?reset=1" q="reset=1"}'><i class="crm-i fa-list" aria-hidden="true"></i> {ts}Membership Type{/ts}</a>
  </div>
{elseif $part eq 'order'}
  <div style="padding-bottom: 10px;padding-top: 10px;">
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/batch-list?reset=1" q="reset=1"}'><i class="crm-i fa-plus" aria-hidden="true"></i> {ts}Device Batch{/ts}</a>
  </div>
{elseif $part eq 'shipping'}
  <div style="padding-bottom: 10px;padding-top: 10px;">
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/shipments?reset=1" q="reset=1"}'><i class="crm-i fa-plus" aria-hidden="true"></i> {ts}Shipment Batch{/ts}</a>
  </div>
{elseif $part eq 'setting'}
  <div style="padding-bottom: 10px;padding-top: 10px;">
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/setting" q="reset=1"}'><i class="crm-i fa-gear" aria-hidden="true"></i> {ts}Setting{/ts}</a>
    <br/>
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/admin/options/warehouse_shelf?reset=1" q="reset=1"}'><i class="crm-i fa-plus" aria-hidden="true"></i> {ts}Warehouse Self{/ts}</a>
    <br/>
    <a target="_blank" class="action-item crm-hover-button" href='{crmURL p="civicrm/admin/shippo?reset=1" q="reset=1"}'><i class="crm-i fa-gear" aria-hidden="true"></i> {ts}Shippo Setting{/ts}</a>
  </div>
{else}
{/if}
