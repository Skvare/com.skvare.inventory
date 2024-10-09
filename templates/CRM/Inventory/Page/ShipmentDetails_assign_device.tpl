
<h3>Assign Devices to Members</h3>

<form action="/civicrm/inventory/shipment-details?id={$shipmentID}" accept-charset="UTF-8" method="post">
  <input type="hidden" name="id" id="id" value="{$shipmentID}" autocomplete="off">
  <input type="hidden" name="operation" id="operation" value="assigndevice" autocomplete="off">
  <input type="hidden" name="action" id="action" value="renew" autocomplete="off">
  <table class="form-row">
    <td class="col">
      <input class="form-control" placeholder="Order Code" autofocus="autofocus" type="text" name="order_id" id="order_id">
    </td>
    <td class="col">
      <input class="form-control" placeholder="Device IMEI" autofocus="autofocus" type="text" name="device_id" id="device_id">
    </td>
    <td class="col">
      <button class="btn btn-outline-primary" type="submit">
        <i class="fa fa-fw fa-check"></i> Assign
      </button>
    </td>
  </table>
</form>
<h4 style="color: red;">Unassigned<i class="fa fa-fw fa-times"></i></h4>
<table>
  {assign var="is_any_unassigned" value="true"}
  {foreach from=$shipmentDetails key=$model item=shipments}
    {foreach from=$shipments item=order}
      {if $order.needs_assignment && !$order.has_assignment}
        {assign var="is_any_unassigned" value="false"}
        <tr>
          <td>{$order.code}</td>
          <td>{$order.sort_name}</td>
          <td>{$order.product_label}</td>
        </tr>
      {/if}
    {/foreach}
  {/foreach}
  {if $is_any_unassigned}
    <td>No Record</td>
  {/if}
</table>
<h4 style="color:green;">Assigned</h4>
{assign var="is_any_assigned" value="true"}
<table>
  {foreach from=$shipmentDetails key=$model item=shipments}
    {foreach from=$shipments item=order}
      {if $order.needs_assignment && $order.has_assignment}
        {assign var="is_any_assigned" value="false"}
        <tr>
          <td>{$order.code}</td>
          <td>{$order.sort_name}</td>
          <td>{$order.product_label}</td>
        </tr>
      {/if}
    {/foreach}
  {/foreach}
  {if $is_any_unassigned}
    <td>No Record</td>
  {/if}
</table>
