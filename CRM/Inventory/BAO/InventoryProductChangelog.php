<?php

use Civi\Api4\Contact;
use Civi\Api4\InventoryBatch;
use Civi\Api4\InventoryProductChangelog;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 *  InventoryProductChangelog
 *
 *  Log device change actions.
 */
class CRM_Inventory_BAO_InventoryProductChangelog extends CRM_Inventory_DAO_InventoryProductChangelog {

  const REQUEST_COLUMN = [
    "SUSPEND" => "Suspend",
    "TERMINATE" => "Terminate",
    "REACTIVATE" => "UnSuspend",
    "STATUS" => "Status",
    "LOST" => "Lost/Stolen",
    "UPDATE" => "Add/Update End-user information",
  ];

  const STATUS_COLUMN = [
    "SUSPEND" => "Active",
    "TERMINATE" => "Inactive",
    "REACTIVATE" => "Active",
    "STATUS" => "Status",
    "LOST" => "Inactive",
    "UPDATE" => "",
  ];

  private const COLUMNS = [
    "Date", "MEID (DEC)", "Current Line of Service (PTN)", "Request (Dropdown)",
    "Status", "First Name", "Last Name", "Email Address", "Phone Number", "Secondary Phone",
    "Street Address", "City", "State", "Zip Code", "Eligability Docs Confirmed", "Reseller Notes",
  ];

  private const CHANGE_LIST = [
    "Suspend", "Terminate", "UnSuspend", "Status", "Lost/Stolen", "Add/Update End-user information",
  ];

  /**
   * Log change request for product.
   *
   * @param int $productID
   *   Product Variant id.
   * @param string $changeAction
   *   Change action.
   *
   * @return array|void|null
   *   Change Log row.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function logStatusChange(int $productID, string $changeAction) {
    $changeActionList = CRM_Inventory_Utils::productChangeLogStatus();

    if ($productID && $changeAction && array_key_exists($changeAction, $changeActionList)) {
      // Get Open Status Batch ID.
      $batchID = CRM_Inventory_BAO_InventoryBatch::getOpenBatchID();
      if (empty($batchID)) {
        // Create Open Status Batch.
        $batchID = CRM_Inventory_BAO_InventoryBatch::createOpenBatch();
      }
      $contactID = CRM_Core_Session::getLoggedInContactID();
      $results = InventoryProductChangelog::create(TRUE)
        ->addValue('batch_id', $batchID)
        ->addValue('status_id', $changeAction)
        ->addValue('contact_id', $contactID)
        ->addValue('product_variant_id', $productID)
        ->addValue('created_date', date('YmdHis'))
        ->execute();
      return $results->first();
    }
  }

  /**
   * Function to export change log.
   *
   * @param int $batchID
   *   Batch ID.
   * @param bool $download
   *   Download.
   *
   * @return \PhpOffice\PhpSpreadsheet\Spreadsheet|array|void
   *   Download the sheet or return spreadsheet object.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
   */
  public static function exportForBatch(int $batchID, bool $download = TRUE) {

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Daily Asset Status Changes");

    $sheet->fromArray([self::COLUMNS], NULL, 'A1');
    $exportData = [];
    $row = 2;
    // Get the changes logs for batch id.
    $inventoryProductChangelogs = InventoryProductChangelog::get(TRUE)
      ->addSelect('contact_id.display_name', 'created_date', 'status_id:label', 'inventory_product.product_brand:name', 'inventory_product_variant.product_variant_unique_id', 'inventory_product_variant.product_variant_phone_number', 'contact_id')
      ->addJoin('InventoryProductVariant AS inventory_product_variant', 'INNER')
      ->addJoin('InventoryProduct AS inventory_product', 'INNER')
      ->addWhere('batch_id', '=', $batchID)
      ->addOrderBy('product_variant_id', 'ASC')
      ->setLimit(0)
      ->execute();
    foreach ($inventoryProductChangelogs as $inventoryProductChangelog) {
      // Do something.
      $data = self::columnsFor($inventoryProductChangelog);
      if (!$download) {
        $exportData[] = array_combine(self::COLUMNS, $data);
      }
      $sheet->fromArray([$data], NULL, 'A' . $row);
      $row++;
    }
    if (!$download) {
      return $exportData;
    }

    $validation = $sheet->getDataValidation('D2:D200');
    $validation->setType(DataValidation::TYPE_LIST);
    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
    $validation->setAllowBlank(TRUE);
    $validation->setShowInputMessage(TRUE);
    $validation->setShowErrorMessage(TRUE);
    $validation->setShowDropDown(TRUE);
    // $validation->setFormula1('"' . implode(',', self::CHANGE_LIST) . '"');
    $writer = new Xlsx($spreadsheet);

    // Update the Batch status to Exported.
    InventoryBatch::update(TRUE)
      ->addValue('exported_date', date('YmdHis'))
      ->addValue('status_id:label', 'Exported')
      ->addWhere('id', '=', $batchID)
      ->execute();
    if ($download) {
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="calyx-status-changes-' . $batchID . '.xlsx"');
      $writer->save('php://output');
      \CRM_Utils_System::civiExit();
    }
    else {
      return $spreadsheet;
    }
  }

  /**
   * Function to prepare array each change log details.
   *
   * @param array $deviceChange
   *   Device/product variant change log.
   *
   * @return array
   *   Product change details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function columnsFor($deviceChange) {
    $data = [
      "Date" => date('Y-m-d', strtotime($deviceChange['created_date'])),
      "MEID (DEC)" => $deviceChange['inventory_product_variant.product_variant_unique_id'],
      "Current Line of Service (PTN)" => $deviceChange['inventory_product_variant.product_variant_phone_number'],
      "Request (Dropdown)" => self::REQUEST_COLUMN[$deviceChange['status_id:label']],
      "Status" => self::STATUS_COLUMN[$deviceChange['status_id:label']],
    ];

    if ($deviceChange['status_id:label'] == "UPDATE") {
      $contact = Contact::get(TRUE)
        ->addSelect('first_name', 'last_name', 'address_primary.street_address', 'address_primary.city', 'address_primary.postal_code', 'address_primary.state_province_id:label', 'address_primary.country_id:label')
        ->addWhere('id', '=', $deviceChange['contact_id'])
        ->setLimit(1)
        ->execute()->first();
      $data = array_merge($data, [
        "First Name" => $contact['first_name'] ?? NULL,
        "Last Name" => $contact['last_name'] ?? NULL,
      ]);

      $data = array_merge($data, [
        "Street Address" => $contact['address_primary.street_address'] ?? NULL,
        "City" => $contact['address_primary.city'] ?? NULL,
        "State" => $contact['address_primary.state_province_id:label'] ?? NULL,
        "Zip Code" => $contact['address_primary.postal_code'] ?? NULL,
      ]);
    }

    $columns = array_fill(0, count(self::COLUMNS), NULL);
    foreach ($data as $key => $value) {
      $index = array_search($key, self::COLUMNS);
      if ($index !== FALSE) {
        $columns[$index] = $value;
      }
    }

    return $columns;
  }

}
