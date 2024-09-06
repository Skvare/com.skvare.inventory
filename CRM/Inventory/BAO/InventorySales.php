<?php

use CRM_Inventory_ExtensionUtil as E;

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

}
