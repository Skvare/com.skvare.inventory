<!-- Tab links -->
<h2>Shipment {$shipmentID}
  <div class="muted">
  <span class="date" title="{$shipmentInfo.created_date}">{$shipmentInfo.created_date|crmDate:'%Y-%m-%d'}</span>
  </div>
</h2>
<div class="shipment_tab">
  <button class="shipment_tablinks active" style="display: block;" onclick="openShippingTab(event, 'default')">Default</button>
  <button class="shipment_tablinks" onclick="openShippingTab(event, 'shipping')">Shipping</button>
  <button class="shipment_tablinks" onclick="openShippingTab(event, 'assign_device')">Assign Device</button>
  <button class="shipment_tablinks" onclick="openShippingTab(event, 'move_order')">Move Orders</button>
</div>

<div id="default" class="shipment_tabcontent" style="display: block;">
  <table>
    {foreach from=$shipmentDetails key=$model item=shipments}
      <tr><td colspan="4"><strong>{$model}</strong></td></tr>
      {foreach from=$shipments item=order}
        <tr>
          <td>{$order.code}</td>
          <td>{$order.sale_date}</td>
          <td>{$order.sort_name}</td>
          <td>
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
          </td>
        </tr>
      {/foreach}
    {/foreach}
  </table>
</div>
<div id="shipping" class="shipment_tabcontent">
  <h3>Shipping content</h3>
  <div>
    <div>
      {foreach from=$links item=link}
        <a class="{$link.class}" {if $link.is_disable} disabled="disabled" {/if} style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" id="{$link.id}" href="/civicrm/inventory/shipment-details?entity=shipment&action=export&id={$shipmentID}&operation={$link.id}">{if $link.fa}<i class="fa fa-fw fa-{$link.fa}"></i>{/if}{if $link.icon}<b>{$link.icon}</b>{/if}{$link.label}</a>
      {/foreach}
    </div>

  </div>
  <table>
    {foreach from=$shipmentDetails key=$model item=shipments}
      {foreach from=$shipments item=order}
        <tr>
          <td>{$order.code}</td>
          <td>{$order.sale_date}</td>
          <td>{$order.sort_name}</td>
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
</div>
<div id="assign_device" class="shipment_tabcontent">
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
</div>
<div id="move_order" class="shipment_tabcontent">
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
            <td>{$order.code}</td>
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
</div>
{literal}
  <script>
    function openShippingTab(evt, tabName) {
      // Declare all variables
      var i, tabcontent, tablinks;

      // Get all elements with class="shipment_tabcontent" and hide them
      tabcontent = document.getElementsByClassName("shipment_tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }

      // Get all elements with class="shipment_tablinks" and remove the class "active"
      tablinks = document.getElementsByClassName("shipment_tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }

      // Show the current tab, and add an "active" class to the button that opened the tab
      document.getElementById(tabName).style.display = "block";
      evt.currentTarget.className += " active";
    }
  </script>
  <style>

    /* Style the tab */
    .shipment_tab {
      overflow: hidden;
      border: 1px solid #ccc;
      background-color: #f1f1f1;
    }

    /* Style the buttons that are used to open the tab content */
    .shipment_tab button {
      background-color: gray;
      color: black;
      float: left;
      border: solid 1px white;
      outline: none;
      cursor: pointer;
      padding: 14px 16px;
      transition: 0.3s;
    }

    /* Change background color of buttons on hover */
    .shipment_tab button:hover {
      background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .shipment_tab button.active {
      background-color: #ccc;
    }

    /* Style the tab content */
    .shipment_tabcontent {
      display: none;
      padding: 6px 12px;
      border: 1px solid #ccc;
      border-top: none;
    }
    .muted {
      color: #6c757d;
      font-weight: normal;
      display: inline-block;
    }
  </style>
{/literal}
