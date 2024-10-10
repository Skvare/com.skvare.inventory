<?php
use CRM_Inventory_ExtensionUtil as E;

/**
 * Job.Inventorystats API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_job_Inventorystats_spec(&$spec) {
}

/**
 * Job.Inventorystats API
 * Reorder point = (Average daily usage x Average lead time in days) + Safety stock
 *
 * @param array $params
 *  Input Params.
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_job_Inventorystats($params) {
  $domainID = CRM_Core_Config::domainID();
  $settings = Civi::settings($domainID);
  $leadTime = $settings->get('inventory_lead_time') ?? 1;
  $dashboardStats = CRM_Inventory_Utils::deviceModelStats();
  // Reorder point = (Average daily usage x Average lead time in days) +
  // Safety stock.
  foreach ($dashboardStats as $productID => $productStats) {
    $avgDailySale = CRM_Inventory_BAO_InventoryProduct::getAvgDailyCountForProduct($productID);
    $recorderPoint = (($avgDailySale * $leadTime) + $productStats['minimum_quantity_stock_level']);
    $productList = ['id' => $productID];
    if (($productStats['available_inventory'] - $productStats['pendingOrder']) <= 0) {
      $productList['inventory_status'] = 'out';
    }
    elseif (($productStats['available_inventory'] < $productStats['minimum_quantity_stock_level'])) {
      $productList['inventory_status'] = 'low';
    }
    elseif (($productStats['available_inventory'] < $recorderPoint) &&
      ($productStats['available_inventory'] > $productStats['minimum_quantity_stock_level'])) {
      $productList['inventory_status'] = 'reorder';
    }
    elseif (($productStats['available_inventory'] > $recorderPoint)) {
      $productList['inventory_status'] = 'full';
    }

    $productList['quantity_available'] = $productStats['available_inventory'];
    $productList['reorder_point'] = $recorderPoint;
    CRM_Inventory_BAO_InventoryProduct::create($productList);
  }
  $returnValues = 'Updated inventory stats';
  return civicrm_api3_create_success($returnValues, $params, 'Job', 'Inventorystats');
}
