<?php
namespace Civi\Api4;

/**
 * InventoryBatch entity.
 *
 * Provided by the Inventory System extension.
 *
 * @package Civi\Api4
 */
class InventoryBatch extends Generic\DAOEntity {

  /**
   * Provides more-open permissions that will be further restricted by checkAccess
   *
   * @return array
   */
  public static function permissions():array {
    $permissions = parent::permissions();

    return [
        'meta' => ['access device batch'],
        'default' => [['access device batch', 'administer Inventory']],
        'get' => [['access device batch',]],
        'delete' => [['delete device batch', 'administer Inventory',]],
        'create' => [['create device batch', 'administer Inventory',]],
        'update' => [['edit device batch', 'administer Inventory',]],
      ] + $permissions;
  }
}
