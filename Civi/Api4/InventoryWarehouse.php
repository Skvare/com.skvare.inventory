<?php
namespace Civi\Api4;

/**
 * InventoryWarehouse entity.
 *
 * Provided by the FIXME extension.
 *
 * @package Civi\Api4
 */
class InventoryWarehouse extends Generic\DAOEntity {

  /**
   * Provides more-open permissions that will be further restricted by checkAccess
   *
   * @return array
   */
  public static function permissions():array {
    $permissions = parent::permissions();

    return [
        'default' => [['access warehouse', 'create warehouse', 'edit warehouse']],
        'get' => [['access warehouse',]],
        'delete' => [['access warehouse', 'delete warehouse',]],
        'create' => [['access warehouse', 'create warehouse',]],
        'update' => [['access warehouse', 'edit warehouse',]],
      ] + $permissions;
  }
}
