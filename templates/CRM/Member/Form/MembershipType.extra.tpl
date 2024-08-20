{literal}
  <script type="text/javascript">
    CRM.$(function($) {
      $('.crm-membership-type-form-block-shippable_to').insertAfter('.crm-membership-type-form-block-period_type');
      $('.crm-membership-type-form-block-renewal_fee').insertAfter('.crm-membership-type-form-block-period_type');
      $('.crm-membership-type-form-block-signup_fee').insertAfter('.crm-membership-type-form-block-period_type');
    });
  </script>
{/literal}

{crmScope extensionKey='com.skvare.inventory'}
  <table class="form-layout-compressed" style="display: none">
    <tr class="crm-membership-type-form-block-signup_fee">
      <td class="label">{$form.signup_fee.label}</td>
      <td>{$form.signup_fee.html}<br/>
        <span class="description">
          {ts}This is the fee charged for the first time while buying the product.{/ts}
        </span>
        <br/><br/>
      </td>
    </tr>
    <tr class="crm-membership-type-form-block-renewal_fee">
      <td class="label">{$form.renewal_fee.label}</td>
      <td>{$form.renewal_fee.html}<br/>
        <span class="description">
          {ts}This fee will be charged for renewal of membership.{/ts}
        </span>
        <br/><br/>
      </td>
    </tr>

    <tr class="crm-membership-type-form-block-shippable_to">
      <td class="label">{$form.shippable_to.label}</td>
      <td>{$form.shippable_to.html}<br/>
        <span class="description">
          {ts}Limit the membership type availability to a specific country(s); if nothing is selected, the membership type will be available in all regions.{/ts}
        </span>
        <br/><br/>
      </td>
    </tr>
  </table>
{/crmScope}
