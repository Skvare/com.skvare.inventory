<h3>Batch information:</h3>
<table>
  <tr class="crm-membership-type-form-block-afsearchBatchList1">
    <td>
      <div>
        <div class="crm-container" id="bootstrap-theme">
          <crm-angular-js modules='afsearchBatchList1'>
            <afsearch-batch-list1></afsearch-batch-list1>
          </crm-angular-js>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td style="padding-bottom: 25px;">
      <br/>
      <a class="af-button btn btn-warning" style="background-color: #b5731d;color: #ffffff;" href='{crmURL p="civicrm/inventory/batch-change-log" q="action=export&batch_id=$batch_id" f="?batch_id=$batch_id"}'>
        <i class="crm-i fa-download" aria-hidden="true"></i> {ts}Export Spreadsheet{/ts}</a>
      <br/>
    </td>
  </tr>
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
