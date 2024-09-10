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
   * @param bool $checkPermissions
   * @return Action\InventoryProductVariant\Create
   */
  public static function create($checkPermissions = TRUE) {
    return (new Action\InventoryProductVariant\Create(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\InventoryProductVariant\Update
   */
  public static function update($checkPermissions = TRUE) {
    return (new Action\InventoryProductVariant\Update(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\InventoryProductVariant\Save
   */
  public static function save($checkPermissions = TRUE) {
    return (new Action\InventoryProductVariant\Save(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\InventoryProductVariant\Delete
   */
  public static function delete($checkPermissions = TRUE) {
    return (new Action\InventoryProductVariant\Delete(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   *
   * @return Action\InventoryProductVariant\ChangeStatus
   */
  public static function changeStatus($checkPermissions = TRUE) {
    return (new Action\InventoryProductVariant\ChangeStatus(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * Provides more-open permissions that will be further restricted by checkAccess
   *
   * @return array
   */
  public static function permissions(): array {
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
