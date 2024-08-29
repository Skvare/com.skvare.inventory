{literal}
  <script type="text/javascript">
    CRM.$(function($) {
      $('.crm-membership-type-form-block-shippable_to').insertAfter('.crm-membership-type-form-block-period_type');
      $('.crm-membership-type-form-block-may_renew').insertAfter('.crm-membership-type-form-block-period_type');
      $('.crm-membership-type-form-block-billing_plan').insertAfter('.crm-membership-type-form-block-period_type');
    });
  </script>
{/literal}

{crmScope extensionKey='com.skvare.inventory'}
  <table class="form-layout-compressed" style="display: none">
    <tr class="crm-membership-type-form-block-billing_plan">
      <td class="label"><label for="period_type">Billing Plan information</label></td>
      <td colspan="1">
        <a href="/civicrm/inventory/membership-billing-plan"><i class="crm-i fa-plus" aria-hidden="true"></i> Add New Billing Plan</a>
        <br/><br/>
        <a class="action-item crm-hover-button" href='{crmURL p="civicrm/inventory/membership-billing-plan-list" q="" f="?membership_type_id=$membershipTypeId"}'><i class="crm-i fa-list" aria-hidden="true"></i> {ts}List Billing Plan{/ts}</a>
      </td>
    </tr>
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
