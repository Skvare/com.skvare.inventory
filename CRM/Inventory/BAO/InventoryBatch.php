<?php

use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_BAO_InventoryBatch extends CRM_Inventory_DAO_InventoryBatch {

  /**
   * Generate batch name.
   *
   * @return string
   *   batch name
   */
  public static function generateBatchName() {
    $sql = "SELECT max(id) FROM civicrm_inventory_batch";
    $batchNo = CRM_Core_DAO::singleValueQuery($sql) + 1;

    return ts('Batch %1', [1 => $batchNo]) . ': ' . date('Y-m-d');
  }
}
