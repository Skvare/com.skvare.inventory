<?php

use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_BAO_InventoryProductChangelog extends CRM_Inventory_DAO_InventoryProductChangelog {


  /**
   * Log change request for product.
   *
   * @param $productID
   * @param $changeAction
   * @return array|void|null
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function logStatusChange($productID, $changeAction) {
    $changeActionList = CRM_Inventory_Utils::productChangeLogStatus();

    if ($productID && $changeAction && array_key_exists($changeAction, $changeActionList)) {
      // Get Open Status Batch ID.
      $batchID = CRM_Inventory_BAO_InventoryBatch::getOpenBatchID();
      if (empty($getOpenBatch)) {
        // Create Open Status Batch.
        $batchID = CRM_Inventory_BAO_InventoryBatch::createOpenBatch();
      }
      $contactID = CRM_Core_Session::getLoggedInContactID();
      $results = \Civi\Api4\InventoryProductChangelog::create(TRUE)
        ->addValue('batch_id', $batchID)
        ->addValue('status_id', $changeAction)
        ->addValue('contact_id', $contactID)
        ->addValue('product_variant_id', 1)
        ->addValue('created_date', date('YmdHis'))
        ->execute();
      return $results->first();
    }
  }
}
