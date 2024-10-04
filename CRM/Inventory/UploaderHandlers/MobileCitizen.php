<?php

/**
 * The spreadsheet we get with a shipment of new devices from Mobile Citizen.
 */
class CRM_Inventory_UploaderHandlers_MobileCitizen extends CRM_Inventory_Uploader {

  const HEADERS = ["Ship Date", "Device", "MEID DEC", "PTN", "Sales Order Number"];

  /**
   * Header list.
   *
   * @return string[]
   *   Header list.
   */
  public function headers(): array {
    return self::HEADERS;
  }

  /**
   * Process Rows.
   *
   * @param array $row
   *   Row data.
   *
   * @return void
   *   Nothing.
   *
   * @throws Exception
   */
  public function processRow(array $row): void {
    /** @var CRM_Inventory_BAO_InventoryProductVariant $device */
    $device = $this->findEntityById('product_variant_unique_id', $row["MEID DEC"], 'InventoryProductVariant', TRUE);
    if ($device->id) {
      $this->message("WARNING: device {$device->product_variant_unique_id} already exists.");
      if (is_null($device->membership_id) && $device->status != "new_inventory") {
        $this->message("WARNING: marking device {$device->product_variant_unique_id} as new inventory", $device);
        $device->status = 'new_inventory';
        $device->save();
      }
    }
    else {
      $ship_date = $this->date($row["Ship Date"]);
      if (!$ship_date) {
        throw new Exception('Invalid date in "Ship Date"');
      }
      if (!array_key_exists($row['Device'], $this->modelIds)) {
        throw new Exception('Invalid Device ' . $row['Device']);
      }

      $params = [
        'product_variant_unique_id' => $this->clean($row["MEID DEC"]),
        'product_variant_phone_number' => $this->clean($row["PTN"]),
        'product_id' => $this->modelIds[$row['Device']],
        'status' => 'new_inventory',
        'shipped_on' => $ship_date,
        'created_at' => $ship_date,
        'warranty_start_on' => $ship_date,
        'warranty_end_on' => date('Y-m-d', strtotime('+9 months', strtotime($ship_date))),
        // 'memo' => "Created {$this->clean($row['MEID DEC'])} ({$label})"
      ];

      /** @var CRM_Inventory_BAO_InventoryProductVariant $device */
      $device = CRM_Inventory_BAO_InventoryProductVariant::create($params);
      if ($device->save()) {
        $this->message("INFO: Created device {$device->product_variant_unique_id}, model " . ($device->product_id ?? 'UNKNOWN'), $device, FALSE);
      }
    }
  }

}
