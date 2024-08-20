<?php
namespace Civi\Api4;

/**
 * Inventory entity.
 *
 * Provided by the FIXME extension.
 *
 * @package Civi\Api4
 */
class Inventory extends Generic\DAOEntity {

  /**
   * Provides more-open permissions that will be further restricted by checkAccess
   *
   * @return array
   */
  public static function permissions():array {
    $permissions = parent::permissions();

    return [
        'default' => [['access Inventory',]],
        'get' => [['access Inventory',]],
        'delete' => [['access Inventory', 'access Inventory',]],
        'create' => [['access Inventory', 'access Inventory',]],
        'update' => [['access Inventory', 'access Inventory',]],
      ] + $permissions;
  }
}
