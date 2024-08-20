<?php
namespace Civi\Api4;

/**
 * InventoryProductVariant entity.
 *
 * Provided by the FIXME extension.
 *
 * @package Civi\Api4
 */
class InventoryProductVariant extends Generic\DAOEntity {

  /**
   * Provides more-open permissions that will be further restricted by checkAccess
   *
   * @return array
   */
  public static function permissions():array {
    $permissions = parent::permissions();

    return [
        'meta' => ['access inventory product'],
        'default' => [['access inventory product', 'create inventory product', 'edit inventory product',]],
        'get' => [['access inventory product',]],
        'delete' => [['access inventory product', 'delete inventory product',]],
        'create' => [['access inventory product', 'create inventory product',]],
        'update' => [['access inventory product', 'edit inventory product',]],
      ] + $permissions;
  }
}
