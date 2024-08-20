<?php
namespace Civi\Api4;

/**
 * InventorySales entity.
 *
 * Provided by the FIXME extension.
 *
 * @package Civi\Api4
 */
class InventorySales extends Generic\DAOEntity {

  /**
   * Provides more-open permissions that will be further restricted by checkAccess
   *
   * @return array
   */
  public static function permissions():array {
    $permissions = parent::permissions();

    return [
        'default' => [['access inventory sales', 'create inventory sales', 'edit inventory sales']],
        'get' => [['access inventory sales',]],
        'delete' => [['access inventory sales', 'delete inventory sales',]],
        'create' => [['access inventory sales', 'create inventory sales',]],
        'update' => [['access inventory sales', 'edit inventory sales']],
      ] + $permissions;
  }
}
