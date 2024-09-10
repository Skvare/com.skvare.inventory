<?php
namespace Civi\Api4;

/**
 * InventoryReferrals entity.
 *
 * Provided by the Inventory System extension.
 *
 * @package Civi\Api4
 */
class InventoryReferrals extends Generic\DAOEntity {

  /**
   * @param bool $checkPermissions
   *   Permission Check.
   *
   * @return Action\InventoryReferrals\Create
   *   Information.
   */
  public static function create($checkPermissions = TRUE) {
    return (new Action\InventoryReferrals\Create(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   *   Permission Check.
   *
   * @return Action\InventoryReferrals\Update
   *   Information.
   */
  public static function update($checkPermissions = TRUE) {
    return (new Action\InventoryReferrals\Update(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   *   Permission Check.
   *
   * @return Action\InventoryReferrals\Save
   *   Information.
   */
  public static function save($checkPermissions = TRUE) {
    return (new Action\InventoryReferrals\Save(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   *   Permission Check.
   *
   * @return Action\InventoryReferrals\Delete
   *   Information.
   */
  public static function delete($checkPermissions = TRUE) {
    return (new Action\InventoryReferrals\Delete(self::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   *   Permission Check.
   *
   * @return Action\InventoryReferrals\ValidateCode
   *   Information.
   */
  public static function validateCode($checkPermissions = TRUE) {
    return (new Action\InventoryReferrals\ValidateCode(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

}
