<table>
  {foreach from=$shipmentDetails key=$model item=shipments}
    <tr><td colspan="4"><strong>{$model}</strong></td></tr>
    {foreach from=$shipments item=order}
      <tr>
        <td><a target="crm-popup" class="crm-popup" href="/civicrm/inventory/sale-lineitems?code={$order.code}">{$order.code}</a></td>
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
