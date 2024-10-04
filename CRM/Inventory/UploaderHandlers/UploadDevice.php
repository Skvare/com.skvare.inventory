<?php

/**
 * CRM_Inventory_UploaderHandlers_UploadDevice.
 *
 * The spreadsheet of active/inactive devices.
 */
class CRM_Inventory_UploaderHandlers_UploadDevice extends CRM_Inventory_Uploader {
  const HEADERS = [
    "MEID",
    "PTN",
    "Status",
    "Sub-Status",
    "ICC ID",
    "Device Taken Out of Service Date",
    "Ship Date",
    "Device",
    "Mobile Citizen Warranty Start Date",
    "Mobile Citizen Warranty End Date",
    "First Name",
    "Last Name",
    "Associated Return",
    "Replacement For",
  ];

  /**
   * File headers.
   *
   * @return string[]
   *   Array value
   */
  public function headers(): array {
    return self::HEADERS;
  }

  /**
   * Process row.
   *
   * @param array $row
   *   Row data.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   */
  public function processRow(array $row): void {
    if ($row) {
      if (!array_key_exists($row['Device'], $this->modelIds)) {
        $this->message("ERROR: Could not determine device model for device [device:{$row['MEID']}]");
      }
      else {
        /** @var CRM_Inventory_BAO_InventoryProductVariant $device */
        $device = $this->findEntityById('product_variant_unique_id', $row["MEID"], 'InventoryProductVariant', TRUE);
        if (!$device->id) {
          $device = $this->newDeviceFromActiveRow($row);
        }
        else {
          $this->updatePhoneNumber($device, $row);
        }
        if ($device->id) {
          $this->updateStatus($device, $row);
          $this->updateDates($device, $row);
          if ($device->id) {
            if (!$this->dryRun) {
              $device->save();
            }
          }
        }
      }
    }
  }

  /**
   * Handle Row.
   *
   * @param array $row
   *   Row data.
   *
   * @return CRM_Inventory_DAO_InventoryProductVariant|null
   *   Device or null.
   *
   * @throws CRM_Core_Exception
   */
  private function newDeviceFromActiveRow(array $row): ?CRM_Inventory_DAO_InventoryProductVariant {
    $meid = $row["MEID"];
    $phone = str_replace("-OLD", "", $row["PTN"]);
    $model_name = $row["Device"];
    $productID = $this->modelIds[$model_name];
    $params = [
      'product_variant_unique_id' => $meid,
      'product_variant_phone_number' => $phone,
      'product_id' => $productID,
    ];
    /** @var CRM_Inventory_BAO_InventoryProductVariant $device */
    $device = CRM_Inventory_BAO_InventoryProductVariant::create($params);
    if ($device->id) {
      $this->message("INFO: Created device {$device->product_variant_unique_id}", $device);
      return $device;
    }
    else {
      $this->message("ERROR: Could not create device {$device->product_variant_unique_id}");
      return NULL;
    }
  }

  /**
   * Update phone number.
   *
   * @param CRM_Inventory_BAO_InventoryProductVariant $device
   *   Device.
   * @param array $row
   *   Row data.
   *
   * @return void
   *   Nothing.
   */
  private function updatePhoneNumber(CRM_Inventory_BAO_InventoryProductVariant $device, array $row): void {
    $phone = str_replace("-OLD", "", $row["PTN"]);
    if ($device->product_variant_phone_number != $phone) {
      $device->product_variant_phone_number = $phone;
      $this->message("WARNING: Setting device {$device->product_variant_unique_id} phone number to $phone", $device);
    }
  }

  /**
   * Update Status.
   *
   * @param CRM_Inventory_BAO_InventoryProductVariant $device
   *   Device.
   * @param array $row
   *   Row data.
   *
   * @return void
   *   Nothing.
   */
  private function updateStatus(CRM_Inventory_BAO_InventoryProductVariant $device, array $row): void {
    $status = $this->getStatus($row);
    if ($status == 'active') {
      if (!$device->is_active || $device->is_suspended) {
        if (!$this->recentChange($device)) {
          $device->is_active = TRUE;
          $device->is_suspended = FALSE;
          $device->status = 'new_inventory';
          $this->message("WARNING: changing device {$device->product_variant_unique_id} to be active", $device);
        }
        else {
          // $this->message("WARNING: Upload says device
          // {$device->identifier} status should be ACTIVE. Device has newer
          // changes, so skipping.", ['device' => $device, 'log' => false]);
        }
      }
    }
    elseif ($status == 'suspended') {
      if (!$device->is_active || !$device->is_suspended) {
        if (!$this->recentChange($device)) {
          $device->is_active = TRUE;
          $device->is_suspended = TRUE;
          $this->message("WARNING: changing device {$device->product_variant_unique_id} to be suspended", $device);
        }
        else {
          // $this->message("WARNING: Upload says device
          // {$device->product_variant_unique_id} should be SUSPENDED. Device
          // has newer changes, so skipping.", ['device' => $device, 'log' =>
          // false]);
        }
      }
    }
    elseif ($status == 'terminated') {
      if ($device->is_active || !$device->is_suspended) {
        if (!$this->recentChange($device)) {
          $device->is_active = FALSE;
          $device->is_suspended = TRUE;
          $this->message("WARNING: changing device {$device->product_variant_unique_id} to be terminated", $device);
        }
        else {
          // $this->message("WARNING: Upload says device
          // {$device->product_variant_unique_id} should be TERMINATED.
          // Device has newer changes, so skipping.", ['device' => $device,
          // 'log' => false]);
        }
      }
    }
    else {
      $this->message("WARNING: can not figure out status for device {$device->product_variant_unique_id}", $device, FALSE);
    }
  }

  /**
   * Update date for device.
   *
   * @param CRM_Inventory_BAO_InventoryProductVariant $device
   *   Device object.
   * @param array $row
   *   Row data.
   *
   * @return void
   *   Nothing.
   */
  private function updateDates(CRM_Inventory_BAO_InventoryProductVariant $device, array $row): void {
    // Our ship date is more accurate, so keep it if set.
    $device->shipped_on = $device->shipped_on ?? $this->date($row["Ship Date"]);
    $device->warranty_start_on = $this->date($row["Mobile Citizen Warranty Start Date"]);
    $device->warranty_end_on = $this->date($row["Mobile Citizen Warranty End Date"]);
  }

  /**
   * Get Device Status.
   *
   * @param array $row
   *   Row data.
   *
   * @return string
   *   Status for device.
   */
  private function getStatus($row) {
    if ($row["Sub-Status"] == "Broken") {
      return 'terminated';
    }
    elseif ($row["Status"] == "Active") {
      if ($row["Sub-Status"] == "Blocked") {
        return 'suspended';
      }
      elseif ($row["Sub-Status"] == "In Use") {
        return 'active';
      }
    }
    elseif ($row["Status"] == "Inactive") {
      if ($row["Sub-Status"] == "Suspended") {
        return 'terminated';
      }
    }
    return 'unknown';
  }

}
