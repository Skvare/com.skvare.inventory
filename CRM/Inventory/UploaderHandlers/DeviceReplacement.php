<?php

/**
 * Spreadsheet of warranty replacements.
 */
class CRM_Inventory_UploaderHandlers_DeviceReplacement extends CRM_Inventory_Uploader {
  // The label for the source of these replacements.
  const SOURCE = "mc";

  // @todo make configurable.

  /**
   * Headers.
   *
   * @return string[]
   *   Header name.
   */
  public function headers(): array {
    return [
      "Replacement MEID DEC",
      "Replacement PTN",
      "Replacement Device Ship Date",
      "Defective MEID DEC",
      "Defective PTN",
    ];
  }

  /**
   * Optional Headers.
   *
   * @return string[]
   *   Header name.
   */
  public function optionalHeaders(): array {
    return [
      "Sales Order Number",
      "Associated Return",
      "Replacement For",
      "Replacement ICC ID",
      "Replacement Device Type",
      "Internal ID",
      "Replacement Device First Name",
      "Replacement Device Last Name",
      "Defective Device First Name",
      "Defective Device Last Name",
      "Asset Customer",
    ];
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
    $row = [
      'new_meid' => $this->clean($row["Replacement MEID DEC"]),
      'new_ptn' => $this->cleanNumstr($row["Replacement PTN"]),
      'shipped_on' => $this->date($row["Replacement Device Ship Date"]),
      'old_meid' => $this->clean($row["Defective MEID DEC"]),
      'old_ptn' => $this->cleanNumstr($row["Defective PTN"]),
      'internal_id' => $this->clean($row["Internal ID"]),
    ];

    /** @var CRM_Inventory_BAO_InventoryProductVariant $newDevice */
    $newDevice = $this->getNewDevice($row);

    /** @var CRM_Inventory_BAO_InventoryProductVariant $oldDevice */
    $oldDevice = $this->getOldDevice($row);

    if ($oldDevice->id === NULL) {
      $this->message("WARNING: No previous device specified for [device:{$newDevice->product_variant_unique_id}]");
    }

    if ($oldDevice->id && $oldDevice->membership_id === NULL) {
      $newDevice->is_problem = TRUE;
      $this->message("ERROR: Could not identify membership for device {$newDevice->product_variant_unique_id}.", $newDevice, TRUE);
    }

    if (!$this->dryRun) {
      $params = [
        'new_device_id' => $newDevice->id,
        'old_device_id' => $oldDevice->id,
        'shipped_on' => $row['shipped_on'],
        'external_id' => $row['internal_id'],
        'source' => self::SOURCE,
      ];
      CRM_Inventory_BAO_InventoryProductVariantReplacement::writeRecord($params);
    }

    if ($newDevice->id && $oldDevice->id) {
      $this->assignMembership($newDevice, $oldDevice);
    }
  }

  /**
   * Update device.
   *
   * @param CRM_Inventory_BAO_InventoryProductVariant $device
   *   Device object.
   * @param array $attrs
   *   Attribute.
   *
   * @return void
   *   Nothing.
   */
  public function updateDevice(CRM_Inventory_BAO_InventoryProductVariant $device, array $attrs = []): void {
    if (!$this->dryRun) {
      $device->copyValues($attrs);
    }

    if (!$device->save()) {
      $this->message("WARNING: Error saving {$device->product_variant_unique_id}", $device);
    }
  }

  /**
   * Get new device details.
   *
   * @param array $row
   *   Row data.
   *
   * @return CRM_Inventory_BAO_InventoryProductVariant
   *   Device object.
   */
  public function getNewDevice(array $row) {
    /** @var CRM_Inventory_BAO_InventoryProductVariant $device */
    $device = $this->findEntityById('product_variant_unique_id', $row["new_meid"], 'InventoryProductVariant', TRUE);
    if ($device->id) {
      $this->updateDevice($device, [
        'product_variant_unique_id' => $row['new_meid'],
        'product_variant_phone_number' => $row['new_ptn'],
        'shipped_on' => $row['shipped_on'],
      ]);
    }
    else {
      $device = $this->createDevice([
        'product_variant_unique_id' => $row['new_meid'],
        'product_variant_phone_number' => $row['new_ptn'],
        'shipped_on' => $row['shipped_on'],
      ]);
    }
    return $device;
  }

  /**
   * Get old device details.
   *
   * @param array $row
   *   Row data.
   *
   * @return CRM_Inventory_BAO_InventoryProductVariant|null
   *   Device object.
   */
  public function getOldDevice(array $row): ?CRM_Inventory_BAO_InventoryProductVariant {
    if (empty($row['old_meid'])) {
      return NULL;
    }

    /** @var CRM_Inventory_BAO_InventoryProductVariant $device */
    $device = $this->findEntityById('product_variant_unique_id', $row["old_meid"], 'InventoryProductVariant', TRUE);
    if ($device->id) {
      $this->updateDevice($device, [
        'product_variant_unique_id' => $row['old_meid'],
        'product_variant_phone_number' => $row['old_ptn'],
      ]);
    }
    else {
      $device = $this->createDevice([
        'product_variant_unique_id' => $row['old_meid'],
        'product_variant_phone_number' => $row['old_ptn'],
      ]);
    }
    return $device;
  }

  /**
   * Assign Membership.
   *
   * @param CRM_Inventory_BAO_InventoryProductVariant $newDevice
   *   New Device.
   * @param CRM_Inventory_BAO_InventoryProductVariant $oldDevice
   *   Old Device.
   *
   * @return void
   *   Nothing.
   */
  public function assignMembership(CRM_Inventory_BAO_InventoryProductVariant $newDevice, CRM_Inventory_BAO_InventoryProductVariant $oldDevice): void {
    $membership_id = $oldDevice->membership_id;
    /** @var CRM_Member_BAO_Membership $membership */
    $membership = $this->findEntityById('id', $membership_id, 'Membership');
    $assignNewPrimary = $oldDevice->is_primary &&
      $oldDevice->created_at &&
      $newDevice->created_at &&
      $oldDevice->created_at < $newDevice->created_at;

    if ($membership_id) {
      if ($this->dryRun) {
        $this->message("Warranty replacement device [device:{$newDevice->product_variant_unique_id}] assigned to membership [membership:{$oldDevice->membership_id}]", $newDevice);
      }
      else {
        $newDevice->membership_id = $membership_id;
        $newDevice->is_primary = TRUE;
        // $newDevice->memo = "Warranty replacement device assigned to
        // membership [membership:{$membership->id}]";
        try {
          $newDevice->save();
        }
        catch (Exception $e) {
          $this->message("ERROR: Could not save device[device:{$newDevice->product_variant_unique_id}]");
        }
        $activeStatusList = CRM_Member_BAO_MembershipStatus::getMembershipStatusCurrent();
        if (!in_array($membership->status_id, $activeStatusList) &&
          $newDevice->is_active) {
          $newDevice->changeStatus($newDevice->id, 'TERMINATE', "New warranty replacement device is assigned to an inactive membership.");
        }
      }
    }

    if ($assignNewPrimary) {
      $oldDevice->is_active = FALSE;
      $oldDevice->is_suspended = TRUE;
      $oldDevice->placement = "broken";

      if ($this->dryRun) {
        $this->message("INFO: Terminated defective device [device:{$oldDevice->product_variant_unique_id}]. " .
          "Replaced by [device:{$newDevice->product_variant_unique_id}].", $oldDevice);
      }
      else {
        $this->message("INFO: Terminated defective device [device:{$oldDevice->product_variant_unique_id}]. " .
          "Replaced by device [device:{$newDevice->product_variant_unique_id}].", $oldDevice, TRUE);

        if (!$oldDevice->save()) {
          $this->message("ERROR: Could not save device [device:{$oldDevice->product_variant_unique_id}]", $oldDevice);
        }
      }
    }
  }

}
