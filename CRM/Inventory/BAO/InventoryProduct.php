<?php

use Civi\API\Exception\UnauthorizedException;
use Civi\Api4\InventoryProductVariant;
use Civi\Api4\InventorySales;

/**
 *
 */
class CRM_Inventory_BAO_InventoryProduct extends CRM_Inventory_DAO_InventoryProduct {

  /**
   * Create a new InventoryProduct based on array-data.
   *
   * @param array $params
   *   Key-value pairs.
   *
   * @return CRM_Inventory_DAO_InventoryProduct|null
   */
  public static function create($params) {
    $className = 'CRM_Inventory_DAO_InventoryProduct';
    $entityName = 'InventoryProduct';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Get Avg daily count from last 30 day for product.
   *
   * @param int $product
   *   Product ID.
   *
   * @return int|string
   *   Avg Count.
   *
   * @throws CRM_Core_Exception
   */
  public static function getAvgDailyCountForProduct(int $product): int|string {
    $sql = 'SELECT avg(per_day_count) FROM (
      SELECT DATE_FORMAT(sale_date, "%Y-%m-%d") as per_day, count(DATE_FORMAT(sale_date, "%Y-%m-%d")) as per_day_count
      FROM civicrm_inventory_sales
      WHERE   sale_date > DATE_ADD(NOW(), INTERVAL -1 MONTH)

      group by DATE_FORMAT(sale_date, "%Y-%m-%d")
      ) as avg_sale';
    return CRM_Core_DAO::singleValueQuery($sql) ?? 0;
  }

  /**
   * Get available product in inventory.
   *
   * Returns the devices of this model that are not assigned.
   *
   * @return int
   *   Available count.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function availableInventory(): int {
    return InventoryProductVariant::get(TRUE)
      ->selectRowCount()
      ->addWhere('product_id', '=', $this->id)
      ->addWhere('is_active', '=', TRUE)
      ->addWhere('status', '=', 'new_inventory')
      ->execute()->count() ?? 0;
  }

  /**
   * Get Pending order count for model.
   *
   * Returns the order_items for this device that have been paid but not yet
   * shipped or assigned a device.
   *
   * @return int
   *   Count.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function pendingOrders(): int {
    return InventorySales::get(TRUE)
      ->addJoin('InventoryProductVariant AS inventory_product_variant', 'LEFT')
      ->addWhere('product_id', '=', $this->id)
      ->addWhere('is_paid', '=', TRUE)
      ->addWhere('inventory_product_variant.product_id', 'IS NULL')
      ->setLimit(25)
      ->execute()->count() ?? 0;
  }

  /**
   * Inventory Check.
   *
   * Updates the inventory status of this model based on the current devices
   * in the database and the orders that have already been paid but not yet
   * shipped.
   *
   * @param bool $allowFull
   *   Allow full.
   * @param bool $force
   *   Force check.
   *
   * @return void
   *   Nothing.
   */
  public function inventoryCheck(bool $allowFull = FALSE, bool $force = FALSE): void {
    if ($force || !$this->has_sim) {
      try {
        if (($this->availableInventory() - $this->pendingOrders()) <= 0) {
          $this->inventory_status = "out";
          $this->save();
        }
        elseif ($allowFull && $this->inventory_status === "out") {
          // Only automatically make the inventory full if it was not
          // manually changed to low or delayed, and allow_full is true.
          $this->inventory_status = "full";
          $this->save();
        }
      }
      catch (UnauthorizedException | CRM_Core_Exception $e) {

      }
    }
  }

  /**
   * Get badge class based on status.
   *
   * @param string $status
   *   Inventory status.
   *
   * @return string
   *   class name.
   */
  public static function deviceModelBadge($status) {
    if ($status == 'out') {
      return 'badge-danger';
    }
    elseif ($status == 'delayed') {
      return 'badge-warning';
    }
    elseif ($status == 'low') {
      return 'badge-secondary';
    }
    elseif ($status == 'full') {
      return 'badge-success';
    }
    return 'badge-default';
  }

  /**
   * Product Id list.
   *
   * @param $is_serialize
   *   Is device.
   *
   * @return array
   *   Product id.
   *
   * @throws \Civi\Core\Exception\DBQueryException
   */
  public static function productIds($is_serialize = 1) {
    $sql = "SELECT id, product_code FROM `civicrm_inventory_product`";
    if ($is_serialize) {
      $sql .= ' WHERE is_serialize = 1';
    }
    else {
      $sql .= ' WHERE is_serialize = 0';
    }
    $productObject = CRM_Core_DAO::executeQuery($sql);
    $list = [];
    while ($productObject->fetch()) {
      $list[$productObject->product_code] = $productObject->id;
    }
    return $list;
  }

}
