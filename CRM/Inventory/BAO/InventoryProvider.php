<?php
use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_BAO_InventoryProvider extends CRM_Inventory_DAO_InventoryProvider {

  /**
   * Create a new InventoryProvider based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Inventory_DAO_InventoryProvider|NULL
   *
  public static function create($params) {
    $className = 'CRM_Inventory_DAO_InventoryProvider';
    $entityName = 'InventoryProvider';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
