{if $action eq 4 && $membershipDevices}
{literal}
  <script type="text/javascript">
    CRM.$(function($) {
      $('#membership-device').insertAfter('.crm-info-panel');
    });
  </script>
{/literal}

  {crmScope extensionKey='com.skvare.inventory'}
    <div class="crm-container crm-accordion-body" id="membership-device">
      <div><h5>Device Linked with Membership</h5></div>
    <table  class="selector row-highlight">
      <thead class="sticky">
        <tr>
          <th></th>
          <th>Identifier</th>
          <th>Phone number</th>
          <th></th>
        </tr>
      </thead>
      {foreach from=$membershipDevices item=membershipDevice}
        <tr class="crm-membership-type-form-block-billing_plan">
          <td style="width: 30%;">{$membershipDevice.tag|implode}</td>
          <td>{$membershipDevice.product_variant_phone_number}</td>
          <td>{$membershipDevice.inventory_product_variant_unique_id}</td>
          <td>{$membershipDevice.action}</td>
        </tr>
      {/foreach}
    </table>
    </div>
  {/crmScope}
{/if}
