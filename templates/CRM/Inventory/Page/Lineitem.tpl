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
            <td colspan="2">
              <div class="btn-toolbar">
                <a class="btn btn-success" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" id="change-request" href="{if !$salesDetails.inventory_shipment_labels.is_paid}/civicrm/inventory/sale-lineitems?code={$orderID}&operation=getrate{else}javascript:void(0){/if}"><i class="fa fa-fw fa-repeat"></i>Get Rates</a>
                <a class="btn btn-success" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" id="change-request" href="{if $salesDetails.inventory_shipment_labels.is_valid and !$salesDetails.inventory_shipment_labels.is_paid}/civicrm/inventory/sale-lineitems?code={$orderID}&operation=pay{else}javascript:void(0){/if}"><i class="fa fa-fw fa-dollar"></i>Purchase Label</a>
                <a class="btn btn-warning" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" id="change-request" href="{if $salesDetails.inventory_shipment_labels.is_paid}/civicrm/inventory/sale-lineitems?code={$orderID}&operation=refund{else}javascript:void(0){/if}"><i class="fa fa-fw fa-times"></i>Request Refund</a>
                <a class="btn btn-danger" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" id="change-request" href="/civicrm/inventory/sale-lineitems?code={$orderID}&operation=destroy"><i class="fa fa-fw fa-trash"></i> Destroy</a>
              </div>
            </td>
          </tr>
          {if 'purchase'|array_key_exists:$salesDetails.inventory_shipment_labels and ('status'|array_key_exists:$salesDetails.inventory_shipment_labels.purchase OR 'messages'|array_key_exists:$salesDetails.inventory_shipment_labels.purchase) }
            {if !empty($salesDetails.inventory_shipment_labels.purchase.status)}
              <tr>
                <td><h3 class="mt-3">Purchase Status</h3><br/>
                  {if $salesDetails.inventory_shipment_labels.purchase.status neq 'SUCCESS' }
                    <span class="badge badge-danger">Error</span> {$salesDetails.inventory_shipment_labels.purchase.status}
                  {/if}
                </td>
                <td>
                  {if !empty($salesDetails.inventory_shipment_labels.purchase.status) && $salesDetails.inventory_shipment_labels.purchase.status eq 'SUCCESS'}
                    <span class="badge badge-success">SUCCESS</span>
                  {elseif !empty($salesDetails.inventory_shipment_labels.purchase.status) && $salesDetails.inventory_shipment_labels.purchase.status eq 'ERROR'}
                    <table>
                      <tr><th>Source</th><th>Code</th><th>Text</th></tr>
                      {foreach from=$salesDetails.inventory_shipment_labels.purchase.messages key=key item=item}
                        <tr>
                          <td>{if !empty($item.source)}{$item.source}{/if}</td><td>{$item.code}</td><td>{$item.text}</td>
                        </tr>
                      {/foreach}
                    </table>
                  {/if}
                </td>
              </tr>
            {/if}
          {/if}
          <tr>
            <td><h3 class="mt-3">Valid?</h3></td>
            <td>{if $salesDetails.inventory_shipment_labels.is_valid}<span class="badge badge-success">Yes</span>{else}<span class="badge badge-warning">Unknown</span><div class="help-text">No information yet about label. Press <b>Get Rates</b>.</div>{/if}</td>
          </tr>
          <tr>
            <td><h3 class="mt-3">Paid?</h3></td>
            <td>{if $salesDetails.inventory_shipment_labels.is_paid}<span class="badge badge-success">Yes</span>{else}<span class="badge badge-danger">no</span><div class="help-text">The shipping label has not yet been purchased, or something went wrong when we tried to purchase it.</div>{/if}</td>
          </tr>
          <tr>
            <td><h3 class="mt-3">Tracking Code</h3></td>
            <td>
              {if $salesDetails.inventory_shipment_labels.tracking_url}
                <a target="_blank" href="{$salesDetails.inventory_shipment_labels.tracking_url}">{$salesDetails.inventory_shipment_labels.tracking_id}</a>
              {elseif $salesDetails.inventory_shipment_labels.tracking_id}
                {$salesDetails.inventory_shipment_labels.tracking_id}
              {else}
                None
              {/if}
            </td>
          </tr>
          {if 'inventory_shipment_labels'|array_key_exists:$salesDetails and 'shipment'|array_key_exists:$salesDetails.inventory_shipment_labels}
          {foreach from=$salesDetails.inventory_shipment_labels.shipment key=key item=item}
            {if $key eq 'parcels'}
              <tr>
                <td><h3 class="mt-3">Parcel</h3></td>
                <td>
                  <table>
                    {foreach from=$item item=parcelValue}
                      <tr>
                        <td>Weight</td>
                        <td>{$parcelValue.weight} {$parcelValue.mass_unit}</td>
                      </tr>
                      <tr>
                        <td>Dimensions</td>
                        <td>{$parcelValue.length} x {$parcelValue.height} x {$parcelValue.width} {$parcelValue.distance_unit}<br/>
                          <span style="font-size: x-small;">L x H x W Unit</span>
                        </td>
                      </tr>
                    {/foreach}
                  </table>
                </td>
              </tr>
            {/if}
          {/foreach}
          {foreach from=$salesDetails.inventory_shipment_labels.shipment key=key item=item}
            {if $key eq 'address_from'}
              <tr>
                <td><h3 class="mt-3">Address From</h3> {if $item.is_complete}<span class="badge badge-success">Valid</span>{else}<span class="badge badge-danger">Invalid</span>{/if}</td>
                <td>{$item.name}<br/> {$item.street1}<br/>{$item.city}  {$item.state} {$item.zip}<br/> {$item.country}<br/></td>
              </tr>
            {/if}
          {/foreach}
          {foreach from=$salesDetails.inventory_shipment_labels.shipment key=key item=item}
            {if $key eq 'address_to'}
              <tr>
                <td><h3 class="mt-3">Address To</h3> {if $item.is_complete}<span class="badge badge-success">Valid</span>{else}<span class="badge badge-danger">Invalid</span>{/if}</td>
                <td>{$item.name}<br/> {$item.street1}<br/>{$item.city}  {$item.state} {$item.zip}<br/> {$item.country}<br/></td>
              </tr>
            {/if}
          {/foreach}
          {foreach from=$salesDetails.inventory_shipment_labels.shipment key=key item=item}
            {if $key eq 'rates' and !$salesDetails.inventory_shipment_labels.is_paid}
              <tr>
                <td><h3 class="mt-3">Rates</h3></td>
                <td>
                  <table>
                    {foreach from=$item item=rateValue}
                      <tr {if $rateValue.object_id eq $salesDetails.inventory_shipment_labels.rate_id}style="font-weight:bold;"{/if}>
                        <td>
                          {if $rateValue.object_id eq $salesDetails.inventory_shipment_labels.rate_id}
                            <a class="btn btn-success" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" id="change-request" href="javascript:void(0)" title="Current rate label"><i class="fa fa-fw fa-dollar"></i> {$rateValue.amount} {$rateValue.currency}</a>
                          {else}
                            <a class="btn btn-warning" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" id="change-request" href="{if !$salesDetails.inventory_shipment_labels.is_paid}/civicrm/inventory/sale-lineitems?code={$orderID}&new_rate_id={$rateValue.object_id}&operation=update_rate_id{else}javascript:void(0){/if}" title="Use this for label."><i class="fa fa-fw fa-dollar"></i> {$rateValue.amount} {$rateValue.currency}</a>
                          {/if}
                        </td>
                        <td>{$rateValue.provider}</td>
                        <td>{$rateValue.estimated_days} days</td>
                        <td>{$rateValue.servicelevel.name}</td>
                        <td>{', '|implode:$rateValue.attributes}</td>
                      </tr>
                    {/foreach}
                  </table>
                </td>
              </tr>
            {/if}
          {/foreach}
          {foreach from=$salesDetails.inventory_shipment_labels.shipment key=key item=item}
          {if $key eq 'messages' and !$salesDetails.inventory_shipment_labels.is_valid}
          <tr>
            <td><h3 class="mt-3">Messages</h3></td>
            <td>
              <table>
                {foreach from=$item item=messageValue}

                  <tr>
                    <td><span class="badge badge-warning">{$messageValue.source}</span></td>
                    <td>{$messageValue.text}</td>
                  </tr>

                {/foreach}
              </table>
            </td>
            {/if}
            {/foreach}
            {/if}

            {if $salesDetails.inventory_shipment_labels.is_valid and $salesDetails.inventory_shipment_labels.is_paid}
          <tr>
            <th>Shipping Charge</th><th>Provider</th><th>Tracking</th><th>Estimated Days</th>
          </tr>

          <tr>
            <td>{$salesDetails.inventory_shipment_labels.amount|crmMoney}</td>
            <td>
              {if $salesDetails.inventory_shipment_labels.label_url and $salesDetails.inventory_shipment_labels.label_url_exist}
                <a target="_blank" href="{crmURL p='civicrm/inventory/shipping-label' q="photo=`$salesDetails.inventory_shipment_labels.label_url`"}">{$salesDetails.inventory_shipment_labels.provider}</a>
              {else}
                {$salesDetails.inventory_shipment_labels.provider}
              {/if}
            </td>
            <td>
              {if $salesDetails.inventory_shipment_labels.tracking_url}
                <a target="_blank" href="{$salesDetails.inventory_shipment_labels.tracking_url}">{$salesDetails.inventory_shipment_labels.tracking_id}</a>
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
          {/if}
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
        <td>{$item.product_label}<br/>
        {if $item.product_id and empty($item.product_variant_id)}

          <span class="badge badge-danger">Assign product</span>
          <br/>
        {/if}


          {if $item.product_id and empty($item.membership_id)}
            <span class="badge badge-danger">Membership not present on Line item</span>
            <br/>
          {/if}
          {if $item.additional_details and !empty($item.additional_details)}
            <span style="color: #6c757d !important;font-size: 0.875em;font-weight: 400;">{$item.additional_details}</span>
            <br/>
          {/if}
        </td>
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
