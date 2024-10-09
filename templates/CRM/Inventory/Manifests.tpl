<div>
  <div>
    <table style="margin-top:1px;padding-bottom:5px;width: 100%">
      <tr>
        <td>
          <div>
          <img src="{$headerImage}" height="90px" width="450px">
          </div>
        </td>
      </tr>
    </table>
  </div>
  <p>Dear {contact.display_name},</p>
  {if 'product'|array_key_exists:$sale and !empty($sale.product.product.has_sim)}
  <p><b>The SIM for this hotspot is on the top of the box the hotspot comes in. Do not throw the box away until you have installed the SIM. If you have any questions about this process, please call tech support at 877-216-9603.</b></p>
  {/if}
  <p>Your contribution is the lifeblood that keeps us going and powers work we do on privacy, security, free software development, and education. See <a href="https://calyxinstitute.org/projects">calyxinstitute.org/projects</a> to learn more about what your contribution makes possible. Thank you for your support of our mission!</p>
  <h3>Support</h3>
  {if $sale.product_id}
    <p>If you experience a technical problem with the hotspot device or the internet service, call tech support at 877-216-9603.</p>
  {/if}
  <p>For issues related to your Calyx membership, please visit our help center:<a href="https://calyxinstitute.org/help" target="_blank">calyxinstitute.org/help</a>.</p>
  {if $sale.payment_details.total}
    <h3>Tax Information</h3>
    <p>The Calyx Institute is a public charity, which is exempt from taxation by the IRS under &sect;501(c)(3) and as such, contributions are tax deductible by the donor to the extent provided by law. Our tax ID number is <b>27-2800937</b></p>
    <p>The tax-deductible amount for this order is <b>#{$sale.payment_details.total|crmMoney}</b>. Consult your tax advisor for more information.</p>
    <p>A copy of our tax determination letter is available at <a href="https://calyxinstitute.org/irs">calyxinstitute.org/irs</a>. A copy of our annual report may be obtained upon request in person or in writing (see <a href="https://calyxinstitute.org/about-us">calyxinstitute.org/about-us</a>), or from the Charities Bureau, New York State Attorney General (see <a href="https://charitiesnys.com">charitiesnys.com</a>).</p>
  {/if}

  <h3>Details</h3>
  <table style="font-family: Arial, Verdana, sans-serif;" width="100%">
    <tr class="{cycle values="odd-row,even-row"}  crm-report">
      <td>
        Order
      </td>
      <td>
        {$sale.code} ({$sale.sale_date|crmDate:'%Y-%m-%d'})
      </td>
    </tr>
    <tr class="{cycle values="odd-row,even-row"}  crm-report">
      <td>
        Items
      </td>
      <td>
        <table>
          {foreach from=$sale.line_items item=item}
            <tr class="{cycle values="odd-row,even-row"}  crm-report">
              <td width="30%">{$item.label}</td>
              <td>{$item.qty}</td>
              <td>{$item.line_total|crmMoney}</td>
            </tr>
          {/foreach}
        </table>
      </td>
    </tr>
    <tr class="{cycle values="odd-row,even-row"}  crm-report">
      <td>
        Payments
      </td>
      <td>
        <table>
          {foreach from=$sale.payment_details.transaction item=transaction}
            <tr class="{cycle values="odd-row,even-row"}  crm-report">
              <td>{$transaction.payment_instrument}</td>
              <td>{$transaction.status}</td>
              <td>{$transaction.receive_date|crmDate:'%Y-%m-%d'}</td>
              <td>{$transaction.total_amount|crmMoney}</td>
            </tr>
          {/foreach}
          <tr>
            <td colspan="1">
              Balance
            </td>
            <td colspan="3">
              {$sale.payment_details.balance|crmMoney}
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        Mailing Address
      </td>
      <td colspan="4">
        {$sale.address_display}
      </td>
    </tr>
  </table>
  {if $sale.barcode_number}
    <p><img src="{$sale.barcode_image}"/></p>
    <p>{$sale.barcode_number}</p>
  {/if}
</div>
