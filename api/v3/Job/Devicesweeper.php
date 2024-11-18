<?php

/**
 * @file
 */

use Civi\Api4\InventoryProductVariant;

/**
 * Job.Devicesweeper API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec
 *   description of fields supported by this API call.
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_job_Devicesweeper_spec(&$spec) {
}

/**
 * Job.Devicesweeper API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_job_Devicesweeper($params) {
  DeviceSweeper::sweep();
  $returnValues = 'Updated Device Sweeper';
  return civicrm_api3_create_success($returnValues, $params, 'Job', 'Devicesweeper');
}

/**
 *
 */
class DeviceSweeper {

  /**
   * Sweep.
   *
   * @return void
   *   Noting.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function sweep(): void {
    self::expireDevices();
    self::flagProblemDevices();
  }

  /**
   * Expire device.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function expireDevices(): void {
    $inventoryProductVariants = new CRM_Inventory_BAO_InventoryProductVariant();
    $inventoryProductVariants->whereAdd('expire_on > ' . date('Y-m-d H:i:s'));
    $inventoryProductVariants->find();
    while ($inventoryProductVariants->fetch()) {
      $inventoryProductVariantList[$inventoryProductVariants->id] = $inventoryProductVariants;
      $inventoryProductVariants->expire_on = NULL;
      if ($inventoryProductVariants->isTerminated()) {
        $inventoryProductVariants->save();
      }
      else {
        $inventoryProductVariants->changeStatus($inventoryProductVariants->id, 'TERMINATE', "Terminated because device has expired.");
      }
    }
  }

  /**
   * Flag problem on device.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function flagProblemDevices(): void {
    // DEVICES MISSING EXPIRATION.
    $inventoryProductVariants = new CRM_Inventory_BAO_InventoryProductVariant();
    $inventoryProductVariants->whereAdd('is_suspended = 1');
    $inventoryProductVariants->whereAdd('is_problem = 1');
    $inventoryProductVariants->whereAdd('expire_on is null');
    $inventoryProductVariants->find();
    while ($inventoryProductVariants->fetch()) {
      $inventoryProductVariants->is_problem = 1;
      $inventoryProductVariants->memo = 'Missing expiration date';
      $inventoryProductVariants->save();
    }

    // DEVICES MISSING MEMBERSHIPS.
    $inventoryProductVariants = new CRM_Inventory_BAO_InventoryProductVariant();
    $inventoryProductVariants->whereAdd('is_active = 1');
    $inventoryProductVariants->whereAdd('is_problem = 0');
    $inventoryProductVariants->whereAdd('membership_id is null = 1');
    $inventoryProductVariants->whereAdd('member_id is null');
    $inventoryProductVariants->whereAdd('status is null');
    $inventoryProductVariants->find();
    while ($inventoryProductVariants->fetch()) {
      $inventoryProductVariants->is_problem = 1;
      $inventoryProductVariants->memo = 'Missing membership';
      $inventoryProductVariants->save();
    }

    // MEMBERSHIP WITH MULTIPLE DEVICES (that are not expiring)
    $inventoryProductVariants = InventoryProductVariant::get(TRUE)
      ->addSelect('membership_id')
      ->addSelect('COUNT(membership_id)')
      ->addWhere('is_active', '=', TRUE)
      ->addWhere('is_problem', '=', FALSE)
      ->addWhere('expire_on', 'IS NULL')
      ->setLimit(0)
      ->addGroupBy('membership_id')
      ->setHaving([
        ['COUNT(membership_id)', '>', 1],
      ])
      ->execute()->getArrayCopy();
    foreach ($inventoryProductVariants as $inventoryProductVariant) {
      if ($inventoryProductVariant['membership_id']) {
        $inventoryProductVariants = new CRM_Inventory_BAO_InventoryProductVariant();
        $inventoryProductVariants->whereAdd('is_active = 1');
        $inventoryProductVariants->whereAdd('is_problem = ' . $inventoryProductVariant['membership_id']);
        $inventoryProductVariants->orderBy('created_at asc');
        $inventoryProductVariants->find();
        while ($inventoryProductVariants->N > 1 && $inventoryProductVariants->fetch()) {
          $inventoryProductVariants->is_problem = 1;
          $inventoryProductVariants->memo = 'Membership has a newer device';
          $inventoryProductVariants->save();
          break;
        }
      }
    }

    // ACTIVE DEVICES WITH SUSPENDED MEMBERSHIPS.
    $inventoryProductVariants = InventoryProductVariant::get(TRUE)
      ->addJoin('Membership AS membership', 'INNER')
      ->addWhere('is_active', '=', TRUE)
      ->addWhere('expire_on', 'IS NULL')
      ->addWhere('membership.status_id', 'IN', [1, 2, 3])
      ->setLimit(5)
      ->execute();
    foreach ($inventoryProductVariants as $inventoryProductVariant) {
      $variantObj = new CRM_Inventory_BAO_InventoryProductVariant();
      $variantObj->changeStatus($inventoryProductVariant['membership_id'], 'TERMINATE', "Terminated because membership is inactive.");
    }

    // DEVICES WITH WRONG STATE.
    $devices = self::withWrongState();
    foreach ($devices as $device) {
      /** @var CRM_Inventory_BAO_InventoryProductVariant $device */
      $device->is_problem = TRUE;
      $device->memo = 'Device active state does not match the last device change record';
      $device->save();
    }
  }

  /**
   * Finds devices where the active state is different from what is indicated in device_changes
   *
   * @return array
   *   List of device object.
   *
   * @throws \Civi\Core\Exception\DBQueryException
   */
  public static function withWrongState(): array {
    $sql = "
            SELECT devices.*
            FROM civicrm_inventory_product_variant devices
            INNER JOIN (
                SELECT product_variant_id, status_id, ROW_NUMBER() OVER(PARTITION BY product_variant_id ORDER BY created_date DESC) AS rn
                FROM civicrm_inventory_product_changelog
                WHERE status_id IN ('TERMINATE', 'LOST', 'REACTIVATE')
            ) AS changes
            ON devices.id = changes.product_variant_id AND changes.rn = 1
            WHERE (devices.is_problem = FALSE)
            AND ((devices.is_active = TRUE AND changes.status_id IN ('TERMINATE', 'LOST')) OR (devices.is_active = FALSE AND changes.status_id = 'REACTIVATE'));
        ";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $deviceList = [];
    while ($dao->fetch()) {
      $inventoryProductVariants = new CRM_Inventory_BAO_InventoryProductVariant();
      $deviceList[$dao->id] = $inventoryProductVariants->findEntityById('id', $dao->id,
        'InventoryProductVariant');
    }
    return $deviceList;
  }

}
