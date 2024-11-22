<div>
  <div>
    {if $shipmentInfo.is_finished}
      <a class="btn btn-success" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" title="A closed shipment may not have new orders added." href="/civicrm/inventory/shipment-details?entity=shipment&action=update&id={$shipmentID}&operation=is_finished_open"><i class="fa fa-fw fa-check"></i>Closed</a>
    {else}
      <a class="btn btn-primary" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" title="An open shipment may have new orders added." href="/civicrm/inventory/shipment-details?entity=shipment&action=update&id={$shipmentID}&operation=is_finished_close"><i class="fa fa-fw fa-times"></i>Open</a>
    {/if}
    {if $shipmentInfo.is_shipped}
      <a class="btn btn-success" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" title="Once shipped, a shipment cannot be modified." href="/civicrm/inventory/shipment-details?entity=shipment&action=update&id={$shipmentID}&operation=is_shipped_open"><i class="fa fa-fw fa-check"></i>Shipped</a>
    {else}
      <a class="btn btn-primary" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" title="" href="/civicrm/inventory/shipment-details?entity=shipment&action=update&id={$shipmentID}&operation=is_shipped_close"><i class="fa fa-fw fa-times"></i>Pending</a>
    {/if}
  </div>

</div>
<table style="margin-top: 10px;" id="bootstrap-theme">
  {foreach from=$shipmentDetails key=$model item=shipments}
    <tr><td colspan="4"><strong>{$model}</strong></td></tr>
    {foreach from=$shipments item=order}
      <tr>
        <td><a target="crm-popup" class="crm-popup" href="/civicrm/inventory/sale-lineitems?code={$order.code}">{$order.code}</a></td>
        <td>{$order.sale_date}</td>
        <td>{$order.sort_name}</td>
        <td>
          {if $order.is_shipping_required}<span class="badge badge-warning">Shipment required</span>{else}<span class="badge badge-danger">Shipment not required</span>{/if}
          {if $order.needs_assignment && !$order.has_assignment}
            <span class="badge badge-danger">needs device</span>
          {/if}
          {if $order.has_assignment}
            <span class="badge badge-success">has device</span>
          {/if}
          {if $order.label_url}
            <span class="badge badge-success">has label</span>
          {else}
            <span class="badge badge-danger">no label</span>
          {/if}
          {if $order.shipment_label_id && $order.has_error}
            <span class="badge badge-danger">label error</span>
          {/if}
          {if $order.shipment_label_id && $order.is_paid}
            <span class="badge badge-success">paid</span>
          {/if}
        </td>
      </tr>
    {/foreach}
  {/foreach}
</table>
