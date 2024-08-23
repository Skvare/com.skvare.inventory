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


  /**
   * @return mixed|string
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function getOpenBatchID() {
    $inventoryBatches = \Civi\Api4\InventoryBatch::get(TRUE)
      ->addSelect('id')
      ->addWhere('status_id:name', '=', 'Open')
      ->setLimit(1)
      ->execute();
    return $inventoryBatches->first()['id'];
  }

  /**
   * @return int
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function checkOpenBatchExist() {
    $inventoryBatchesCount = \Civi\Api4\InventoryBatch::get(TRUE)
      ->selectRowCount()
      ->addWhere('status_id:name', '=', 'Open')
      ->execute();

    return $inventoryBatchesCount->rowCount;
  }

  /**
   * @return mixed
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function createOpenBatch() {
    $contactID = CRM_Core_Session::getLoggedInContactID() ?? NULL;
    $results = \Civi\Api4\InventoryBatch::create(TRUE)
      ->addValue('name', self::generateBatchName())
      ->addValue('created_id', $contactID)
      ->addValue('created_date', date('YmdHis'))
      ->addValue('status_id', 1)
      ->execute();
    return $results->first()['id'];
  }
}
