<?php
namespace Civi\Api4;

/**
 * InventoryProductChangelog entity.
 *
 * Provided by the Inventory System extension.
 *
 * @package Civi\Api4
 */
class InventoryProductChangelog extends Generic\DAOEntity {

  /**
   * @param bool $checkPermissions
   * @return Action\InventoryProductChangelog\Create
   */
  public static function create($checkPermissions = TRUE) {
    return (new Action\InventoryProductChangelog\Create(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\InventoryProductChangelog\Update
   */
  public static function update($checkPermissions = TRUE) {
    return (new Action\InventoryProductChangelog\Update(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\InventoryProductChangelog\Save
   */
  public static function save($checkPermissions = TRUE) {
    return (new Action\InventoryProductChangelog\Save(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return Action\InventoryProductChangelog\Delete
   */
  public static function delete($checkPermissions = TRUE) {
    return (new Action\InventoryProductChangelog\Delete(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   *
   * @return Action\InventoryProductChangelog\Export
   */
  public static function export($checkPermissions = TRUE) {
    return (new Action\InventoryProductChangelog\Export(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }
}
