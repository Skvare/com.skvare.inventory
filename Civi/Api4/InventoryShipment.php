<?php
namespace Civi\Api4;

/**
 * InventoryShipment entity.
 *
 * Provided by the Inventory System extension.
 *
 * @package Civi\Api4
 */
class InventoryShipment extends Generic\DAOEntity {

  /**
   * Provides more-open permissions that will be further restricted by checkAccess
   *
   * @return array
   */
  public static function permissions():array {
    $permissions = parent::permissions();

    return [
        'default' => [['access shipment', 'create shipment', 'edit shipment']],
        'get' => [['access shipment',]],
        'delete' => [['access shipment', 'delete shipment',]],
        'create' => [['access shipment', 'create shipment',]],
        'update' => [['access shipment', 'edit shipment']],
      ] + $permissions;
  }
}
