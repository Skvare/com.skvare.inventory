{* HEADER *}
{foreach from=$elementNames item=elementName}
  <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">{$form.$elementName.html}</div>
    <div class="clear"></div>
  </div>
{/foreach}
<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>

{if !empty($trackingDetails)}
  <br/><br/>
  <div class="crm-block crm-content-block crm-sale-view-form-block" id="bootstrap-theme">
    <table>
      <tr>
        <td><h3 class="mt-3">Address From</h3></td>
        <td>{$trackingDetails.address_from.name}<br/> {$trackingDetails.address_from.street1}<br/>{$trackingDetails.address_from.city}  {$trackingDetails.address_from.state} {$trackingDetails.address_from.zip}<br/> {$trackingDetails.address_from.country}<br/></td>
      </tr>
      <tr>
        <td><h3 class="mt-3">Address To</h3></td>
        <td>{$trackingDetails.address_to.name}<br/> {$trackingDetails.address_to.street1}<br/>{$trackingDetails.address_to.city}  {$trackingDetails.address_to.state} {$trackingDetails.address_to.zip}<br/> {$trackingDetails.address_to.country}<br/></td>
      </tr>

      <tr>
        <td><h3 class="mt-3">Original Estimated Time</h3></td>
        <td>{$trackingDetails.original_eta|crmDate}</td>
      </tr>
      <tr>
        <td><h3 class="mt-3">Estimated Time</h3></td>
        <td>{$trackingDetails.eta|crmDate}</td>
      </tr>
      <tr>
        <td><h3 class="mt-3">Tracking Number</h3></td>
        <td>{$trackingDetails.tracking_number}</td>
      </tr>
      <tr>
        <td><h3 class="mt-3">Carrier</h3></td>
        <td>{$trackingDetails.carrier}
          {if $trackingDetails.servicelevel.name}
            <br/>
            <span class="badge badge-warning">{$trackingDetails.servicelevel.name}</span>
          {/if}
        </td>
      </tr>
      <tr>
        <td><h3 class="mt-3">Tracking Status</h3></td>

        <td>
          {if $trackingDetails.tracking_status.status eq 'DELIVERED'}
            <span class="badge badge-success">{$trackingDetails.tracking_status.status}</span>
          {else}
            <span class="badge badge-warning">{$trackingDetails.tracking_status.status}</span>
          {/if}
          <br/> {$trackingDetails.tracking_status.status_date|crmDate}<br/>{$trackingDetails.tracking_status.status_details} </td>
      </tr>
      <tr>
        <td><h3 class="mt-3">Tracking History</h3></td>
        <td>
          <table>
            {foreach from=$trackingDetails.tracking_history item=history}
              <tr>
                <td>{$history.status_date|crmDate}</td>
                <td><span class="badge badge-{if $history.status eq 'DELIVERED'}success{else}warning{/if}">{$history.status}</span></td>
                <td>{$history.status_details}</td>
              </tr>
            {/foreach}
          </table>
        </td>
      </tr>
    </table>
  </div>
{/if}
