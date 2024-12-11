{literal}
  <script type="text/javascript">
    CRM.$(function($) {
      $('.crm-membership-type-form-block-shippable_to').insertAfter('.crm-membership-type-form-block-auto_renew');
      $('.crm-membership-type-form-block-may_renew').insertAfter('.crm-membership-type-form-block-auto_renew');
      $('.crm-membership-type-form-block-category').insertAfter('.crm-membership-type-form-block-auto_renew');
      $('.crm-membership-type-form-block-product_mapping').insertAfter('.crm-membership-type-form-block-auto_renew');
      $('.crm-membership-type-form-block-fair_value').insertAfter('.crm-membershiptype-form-block-minimum_fee');

    });
  </script>
{/literal}

{crmScope extensionKey='com.skvare.inventory'}
  <table class="form-layout-compressed" style="display: none">
    <tr class="crm-membership-type-form-block-product_mapping">
      <td class="label"><label for="period_type">Product Linked</label></td>
      <td>
        <div>
          <div class="crm-container" id="bootstrap-theme">
            <crm-angular-js modules='afsearchProductMembershipMappingList'>
              <afsearch-product-membership-mapping-list></afsearch-product-membership-mapping-list>
            </crm-angular-js>
          </div>
        </div>
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
    <tr class="crm-membership-type-form-block-category">
      <td class="label">{$form.category.label}</td>
      <td>{$form.category.html}<br/>
        <span class="description">
          {ts} The category determins which other membership levels are shown and can be upgraded to.{/ts}
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
    <tr class="crm-membership-type-form-block-fair_value">
      <td class="label">{$form.fair_value.label}</td>
      <td>{$form.fair_value.html}</td>
    </tr>
  </table>
{/crmScope}
