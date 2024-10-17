<h3>Move selected devices to a different shipment</h3>
<form action="/civicrm/inventory/shipment-details?id={$shipmentID}" accept-charset="UTF-8" method="post">
  <input type="hidden" name="id" id="id" value="{$shipmentID}" autocomplete="off">
  <input type="hidden" name="operation" id="operation" value="newshipment" autocomplete="off">
  <input type="hidden" name="action" id="action" value="renew" autocomplete="off">
  <button class="btn btn-outline-primary" type="submit">
    <i class="fa fa-fw fa-check"></i> Create New Shipment
  </button>
</form>
<br/><br/>
<form action="/civicrm/inventory/shipment-details?id={$shipmentID}" accept-charset="UTF-8" method="post">
  <input type="hidden" name="id" id="id" value="{$shipmentID}" autocomplete="off">
  <input type="hidden" name="operation" id="operation" value="moveshipment" autocomplete="off">
  <input type="hidden" name="action" id="action" value="renew" autocomplete="off">
  <table class="form-row">
    <td class="col">
      <select class="form-control" name="new_shipment_id" id="new_shipment_id">
        {foreach from=$openShipmentList key=listShipmentID item=listShipmentLabel}
          <option value="{$listShipmentID}">{$listShipmentLabel}</option>
        {/foreach}
      </select>
    </td>
    <td class="col">
      <button class="btn btn-outline-primary" type="submit">
        <i class="fa fa-fw fa-check"></i> Move Selected
      </button>
    </td>
  </table>
  <table>
    {foreach from=$shipmentDetails key=$model item=shipments}
      {foreach from=$shipments item=order}
        <tr>
          <td><input type="checkbox" name="sale_id[{$order.sale_id}]" value="{$order.sale_id}" /></td>
          <td><a target="crm-popup" class="crm-popup" href="/civicrm/inventory/sale-lineitems?code={$order.code}">{$order.code}</a></td>
          <td>{$order.sale_date}</td>
          <td>{$order.sort_name}</td>
          <td>{$order.product_label}</td>
          <td>
            {if $order.label_url}
              <span class="badge badge-success">has label</span>
            {else}
              <span class="badge badge-danger">no label</span>
            {/if}
          </td>
        </tr>
      {/foreach}
    {/foreach}
  </table>
</form>
