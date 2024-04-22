<?php
use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_BAO_InventoryCategory extends CRM_Inventory_DAO_InventoryCategory {

  /**
   * Create a new InventoryCategory based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Inventory_DAO_InventoryCategory|NULL
   *
  public static function create($params) {
    $className = 'CRM_Inventory_DAO_InventoryCategory';
    $entityName = 'InventoryCategory';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
