<!-- Tab links -->
<h2>Shipment {$shipmentID}
  <span class="date" title="{$shipmentInfo.created_date}">{$shipmentInfo.created_date|crmDate}</span></h2>
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
            {if $order.needs_assignment}
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
  <h3>Assign Device here</h3>
</div>
<div id="move_order" class="shipment_tabcontent">
  <h3>Move shipment other shipment</h3>
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
      background-color: inherit;
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
  </style>
{/literal}
