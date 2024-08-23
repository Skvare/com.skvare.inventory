<?php
namespace Civi\Api4;

/**
 * InventoryProductVariantReplacement entity.
 *
 * Provided by the Inventory System extension.
 *
 * @package Civi\Api4
 */
class InventoryProductVariantReplacement extends Generic\DAOEntity {

  /**
   * Provides more-open permissions that will be further restricted by checkAccess
   *
   * @return array
   */
  public static function permissions():array {
    $permissions = parent::permissions();

    return [
        'default' => [['access device replacement', 'administer Inventory']],
        'get' => [['access device replacement',]],
        'delete' => [['delete device replacement', 'administer Inventory',]],
        'create' => [['create device replacement', 'administer Inventory',]],
        'update' => [['edit device replacement', 'administer Inventory',]],
      ] + $permissions;
  }
}
