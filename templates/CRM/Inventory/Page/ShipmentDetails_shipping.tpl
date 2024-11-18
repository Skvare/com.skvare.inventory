
<div>
  <div>
    {foreach from=$links item=link}
      <a class="{$link.class}" {if $link.is_disable} disabled="disabled" {/if} style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" id="{$link.id}" href="{if $link.is_disable}javascript:void(0){else}/civicrm/inventory/shipment-details?entity=shipment&action=export&id={$shipmentID}&operation={$link.id}{/if}">{if $link.fa}<i class="fa fa-fw fa-{$link.fa}"></i>{/if}{if $link.icon}<b>{$link.icon}</b>{/if}{$link.label}</a>
    {/foreach}
  </div>

</div>
<table style="margin-top: 10px;" id="bootstrap-theme">
  {foreach from=$shipmentDetails key=$model item=shipments}
    {foreach from=$shipments item=order}
      <tr>
        <td><a target="crm-popup" class="crm-popup" href="/civicrm/inventory/sale-lineitems?code={$order.code}">{$order.code}</a></td>
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

