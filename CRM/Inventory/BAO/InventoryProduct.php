<?php
use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_BAO_InventoryProduct extends CRM_Inventory_DAO_InventoryProduct {

  /**
   * Create a new InventoryProduct based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Inventory_DAO_InventoryProduct|NULL
   *
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
  } */

}
