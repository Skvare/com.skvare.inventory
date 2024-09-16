<div class="crm-block crm-content-block crm-inventory-view-form-block">
  <table class="crm-info-panel">
    <tr class="crm-inventory-form-block-contact_id">
      <td class="label">{ts}From{/ts}</td>
      <td class="bold"><a href="{crmURL p='civicrm/contact/view' q="cid=`$productDetails.product_variant.contact_id`"}">{$productDetails.contact.display_name}</a></td>
    </tr>
    <tr class="crm-inventory-form-block-product-label">
      <td class="label">{ts}Product{/ts}</td>
      <td>{$productDetails.product.label}</td>
    </tr>
    <tr class="crm-inventory-form-block-phone_number">
      <td class="label">{ts}Phone Number{/ts}</td>
      <td>{$productDetails.product_variant.product_variant_phone_number}</td>
    </tr>
    <tr class="crm-inventory-form-block-product_variant_unique_id">
      <td class="label">{ts}Identifier{/ts}</td>
      <td>{$productDetails.product_variant.product_variant_unique_id}</td>
    </tr>
    <tr class="crm-inventory-form-block-placement">
      <td class="label">{ts}Placement{/ts}</td>
      <td>{$productDetails.product_variant.status_label}</td>
    </tr>
    <tr class="crm-inventory-form-block-warranty_start_date">
      <td class="label">{ts}Warraynt Start on{/ts}</td>
      <td>{if $productDetails.product_variant.warranty_start_date}{$productDetails.product_variant.warranty_start_date|crmDate}{else}({ts}not available{/ts}){/if}</td>
    </tr>

    <tr class="crm-inventory-form-block-warranty_end_date">
      <td class="label">{ts}Warraynt End on{/ts}</td>
      <td>{if $productDetails.product_variant.warranty_end_date}{$productDetails.product_variant.warranty_end_date|crmDate}{else}({ts}not available{/ts}){/if}</td>
    </tr>

    <tr class="crm-inventory-form-block-expire_on">
      <td class="label">{ts}Expire on{/ts}</td>
      <td>{if $productDetails.product_variant.expire_on}{$productDetails.product_variant.expire_on|crmDate}{else}({ts}not available{/ts}){/if}</td>
    </tr>
    <tr>
      <td class="label">{ts}Tag(s){/ts}</td>
      <td >
        {$currentTags|implode}
      </td>
    </tr>
  </table>
  <div>
    <div>
      <h5>Change Status</h5>
    </div>
    {foreach from=$tags item=tag}
      <a class="{$tag.class}" style="color: #ffffff;border-radius: 3px;" crm-icon="fa-undo" id="change-request" href="/civicrm/contact/view/inventory-productvariant?action=update&change={$tag.id}&id={$productDetails.product_variant.id}&cid={$productDetails.product_variant.contact_id}#?id={$productDetails.product_variant.id}"><i class="fa fa-fw fa-plus"></i>{$tag.label}</a>
    {/foreach}
  </div>
  <table>
    <tr class="crm-membership-type-form-block-afsearchBatchChangeRequest">
      <td>
        <div>
          <div class="crm-container" id="bootstrap-theme">
            <crm-angular-js modules='afsearchBatchChangeRequest'>
              <afsearch-batch-change-request></afsearch-batch-change-request>
            </crm-angular-js>
          </div>
        </div>
      </td>
    </tr>
  </table>
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>
