<div class="crm-block crm-content-block crm-sale-view-form-block" id="bootstrap-theme">
  <table>
    <tr>
      <td>{ts}Contact{/ts}</td>
      <td class="bold"><a href="{crmURL p='civicrm/contact/view' q="cid=`$salesDetails.contact.id`"}">{$salesDetails.contact.display_name}</a></td>
    </tr>
    <tr>
      <td>{ts}Order ID{/ts}</td>
      <td>{$salesDetails.code}
        {if $salesDetails.is_paid}<span class="badge badge-success">paid</span>{else}<span class="badge badge-warning">not paid</span>{/if}
      </td>
    </tr>
    <tr>
      <td>{ts}Product/Model{/ts}</td>
      <td>{$salesDetails.product.label}</td>
    </tr>
    <tr>
      <td>{ts}Order Date{/ts}</td>
      <td>{$salesDetails.sale_date|crmDate}</td>
    </tr>
    <tr>
      <td>Shipping</td>
      <td>
        {if $salesDetails.inventory_shipment.is_shipped}<span class="badge badge-success">Shipped</span>{else}<span class="badge badge-warning">Not yet shipped</span>{/if}
        {if $salesDetails.inventory_shipment.is_finished}<span class="badge badge-success">Finished</span>{else}<span class="badge badge-warning">Not yet finished</span>{/if}
      </td>
    </tr>

    <tr>
      <td>{ts}Shipment Labels{/ts}</td>
      <td>
        <table>
          <tr>
            <th>Shipping Charge</th><th>Provider</th><th>Tracking</th><th>Estimated Days</th>
          </tr>
          <tr>
            <td>{$salesDetails.inventory_shipment_labels.amount|crmMoney}
              <br/>
              {if $salesDetails.inventory_shipment_labels.is_valid}<span class="badge badge-success">valid</span>{else}<span class="badge badge-warning">not valid</span>{/if}
              {if $salesDetails.inventory_shipment_labels.is_paid}<span class="badge badge-success">paid</span>{else}<span class="badge badge-warning">not paid</span>{/if}
            </td>
            <td>
            {if $salesDetails.inventory_shipment_labels.label_url and $salesDetails.inventory_shipment_labels.label_url_exist}
            <a target="_blank" href="{crmURL p='civicrm/inventory/shipping-label' q="photo=`$salesDetails.inventory_shipment_labels.label_url`"}">{$salesDetails.inventory_shipment_labels.provider}</a>
            {else}
              {$salesDetails.inventory_shipment_labels.provider}
            {/if}
            </td>
            <td>
              {if $salesDetails.inventory_shipment_labels.tracking_url}
                <a href="{$salesDetails.inventory_shipment_labels.tracking_url}">{$salesDetails.inventory_shipment_labels.tracking_id}</a>
              {else}
                {$salesDetails.inventory_shipment_labels.tracking_id}
              {/if}
            </td>
            <td>
              {if !empty($salesDetails.inventory_shipment_labels.rate_used)}
                {$salesDetails.inventory_shipment_labels.rate_used.estimated_days} day(s)
                {if $salesDetails.inventory_shipment_labels.rate_used.duration_terms}
                  <br/>
                  <span class="badge badge-danger">{$salesDetails.inventory_shipment_labels.rate_used.duration_terms}</span>
                {/if}
              {else}
                No available
              {/if}
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table>
    <tr>
      <th>Item</th>
      <th>Qty</th>
      <th>Amount</th>
      <th>Value Amount</th>
      <th>Product</th>
    </tr>
    {foreach from=$lineItemArray item=item}
      <tr class="{cycle values="odd-row,even-row"}  crm-report">
        <td width="30%">{$item.label}
          {if $item.subtitle}<br/>{$item.subtitle}{/if}
        </td>
        <td>{$item.qty}</td>
        <td>{$item.line_total|crmMoney}</td>
        <td>{$item.non_deductible_amount|crmMoney}</td>
        <td>{$item.product_label}</td>
      </tr>
    {/foreach}
    <tr>
      <td colspan="3"></td>
      <td>Amount</td><td>{$paidAmount|crmMoney}</td>
    </tr>
    <tr>
      <td colspan="3"></td>
      <td>Transaction Fee</td><td>{$feeAmount|crmMoney}</td>
    </tr>
    <tr>
      <td colspan="3"></td>
      <td>Value Amount</td><td>{$fairAmount|crmMoney}</td>
    </tr>
  </table>
</div>
