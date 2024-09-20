<?php

/**
 *
 */

use Civi\Api4\InventorySales;

/**
 *
 */
class CRM_Inventory_BAO_InventorySales extends CRM_Inventory_DAO_InventorySales {

  /**
   * Create a new InventorySales based on array-data.
   *
   * @param array $params
   *   Key-value pairs.
   *
   * @return CRM_Inventory_DAO_InventorySales|null
   *   Object of sales.
   */
  public static function create($params) {
    $className = 'CRM_Inventory_DAO_InventorySales';
    $entityName = 'InventorySales';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Function to generate random code for sale order.
   *
   * @param int $length
   *   Length of code.
   *
   * @return string
   *   Randomly generated code.
   *
   * @throws \Random\RandomException
   */
  public static function generateCode($length = 10):string {
    return strtoupper(bin2hex(random_bytes($length / 2)));
  }

  /**
   * Function to get New order code.
   *
   * @return string
   *   Randomly generated code.
   *
   * @throws \Random\RandomException
   */
  public static function getNewCode():string {
    do {
      $code = self::generateCode();
    } while (!CRM_Core_DAO::objectExists($code, 'CRM_Inventory_DAO_InventorySales', 'code'));

    return $code;
  }

  /**
   * Fetch the object and store the values in the values array.
   *
   * @param array $params
   *   Input parameters to find object.
   * @param array $values
   *   Output values of the object.
   *
   * @return array|null
   *   The found object or null
   */
  public static function getValues(array $params, array &$values): ?array {
    if (empty($params)) {
      return NULL;
    }
    $inventorySales = new CRM_Inventory_BAO_InventorySales();
    $inventorySales->copyValues($params);
    $inventorySales->find();
    $inventorySalesArray = [];
    while ($inventorySales->fetch()) {
      CRM_Core_DAO::storeValues($inventorySales, $values[$inventorySales->id]);
      $inventorySalesArray[$inventorySales->id] = $inventorySales;
    }
    return $inventorySalesArray;
  }

  /**
   * Function to detach the sale from shipment.
   *
   * @param int $shipmentID
   *   Shipment ID.
   * @param int $saleID
   *   Sale ID.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function detachFromShipment(int $shipmentID, int $saleID):void {
    $results = InventorySales::update(TRUE)
      ->addValue('shipment_id', NULL)
      ->addValue('is_fulfilled', FALSE)
      ->addWhere('id', '=', $saleID)
      ->addWhere('shipment_id', '=', $shipmentID)
      ->execute();
  }


  /**
   * Function to create shipping labels.
   *
   * @param array $params
   *   Sale params.
   *
   * @return void
   *   Nothing.
   */
  public static function createShippingLabel(array $params):void {

  }

  /**
   * Function to pay for shipping labels.
   *
   * @param array $params
   *   Sale params.
   *
   * @return void
   *   Nothing.
   */
  public static function asyncGetRatesAndPay(array $params):void {

  }

}
