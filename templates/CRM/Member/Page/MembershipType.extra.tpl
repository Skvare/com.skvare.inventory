{literal}
  <script type="text/javascript">
    CRM.$(function($) {
      $('.crm-membership-type-form-block-shippable_to').insertAfter('.crm-membership-type-form-block-period_type');
      $('.crm-membership-type-form-block-may_renew').insertAfter('.crm-membership-type-form-block-period_type');
    });
  </script>
{/literal}

{crmScope extensionKey='com.skvare.inventory'}
  <table class="form-layout-compressed" style="display: none">
    <tr class="crm-membership-type-form-block-may_renew">
      <td class="label">{$form.may_renew.label}</td>
      <td>{$form.may_renew.html}<br/>
        <span class="description">
          {ts}If true, a member may renew at this membership level.{/ts}
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
