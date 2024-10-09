<div>
  <p>Dear {$sale.contact_id},</p>
  <p><br/></p>
  <h1>The SIM for this hotspot is on the top of the box the hotspot comes in. Do not throw the box away until you have installed the SIM. If you have any questions about this process, please call tech support at 877-216-9603.</h1>

  <p>Your contribution is the lifeblood that keeps us going and powers work we do on privacy, security, free software development, and education. See <a href="https://calyxinstitute.org/projects">calyxinstitute.org/projects</a> to learn more about what your contribution makes possible. Thank you for your support of our mission!</p>
  <h1>Support</h1>
  <p><br/></p>
  {if $sale.any_device}
    <p>If you experience a technical problem with the hotspot device or the internet service, call tech support at 877-216-9603.</p>
  {/if}
  <p>For issues related to your Calyx membership, please visit our help center:<a href="https://calyxinstitute.org/help" target="_blank">>calyxinstitute.org/help</a>.</p>
  {if $sale.payment_details.total}
    <h1>Tax Information</h1>
    <p><br/></p>
    <p>The Calyx Institute is a public charity, which is exempt from taxation by the IRS under &sect;501(c)(3) and as such, contributions are tax deductible by the donor to the extent provided by law. Our tax ID number is <b>27-2800937</b></p>
    <p>The tax-deductible amount for this order is <b>#{$sale.payment_details.total}</b>. Consult your tax advisor for more information.</p>
    <p><br/></p>
    <p>A copy of our tax determination letter is available at <a href="https://calyxinstitute.org/irs">calyxinstitute.org/irs</a>. A copy of our annual report may be obtained upon request in person or in writing (see <a href="https://calyxinstitute.org/about-us">calyxinstitute.org/about-us</a>), or from the Charities Bureau, New York State Attorney General (see <a href="https://charitiesnys.com">charitiesnys.com</a>).</p>
  {/if}

  <h1>Details</h1>
  <p><br/></p>
  <table>
    <tr>
      <td>
        <p>Order</p>
      </td>
      <td>
        <p>{$sale.code} ($sale.sale_date)</p>
      </td>
    </tr>
    <tr>
      <td>
        <p>Items</p>
      </td>
      <td>
        <table>
          {foreach from=$sale.line_items item=item}
            <tr>
              <td>{$item.label}</td>
              <td>{$item.qty}</td>
              <td>{$item.line_total}</td>
            </tr>
          {/foreach}
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <p>Payments</p>
      </td>
      <td>
        <table>
          {foreach from=$sale.payment_details.transaction item=transaction}
            <tr>
              <td>{$transaction.payment_instrument}</td>
              <td>{$transaction.status}</td>
              <td>{$transaction.receive_date}</td>
              <td>{$transaction.total_amount}</td>
            </tr>
          {/foreach}
          <tr>
            <td colspan="1">
              Balance
            </td>
            <td colspan="3">
              {$sale.payment_details.balance}
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <p>Mailing Address</p>
      </td>
      <td colspan="4">
        <p>Lionel Mante II 72257 Fidel Haven</p>
        <p>Powlowkibury AZ 85089</p>
      </td>
    </tr>
  </table>
  <p>84536-51808 (Pixel 6a)</p>
</div>
