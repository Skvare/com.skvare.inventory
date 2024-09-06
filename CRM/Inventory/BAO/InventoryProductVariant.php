<?php

use Civi\Api4\InventoryProductVariant;
use Civi\Core\Event\PreEvent;

/**
 *
 */
class CRM_Inventory_BAO_InventoryProductVariant extends CRM_Inventory_DAO_InventoryProductVariant {


  /**
   * Takes an associative array and creates a product variant object.
   *
   * @param array $params
   *   An assoc array of name/value pairs.
   *
   * @return CRM_Inventory_DAO_InventoryProductVariant
   *   Object of ProductVariant.
   *
   * @throws \CRM_Core_Exception
   */
  public static function create($params = NULL) {
    if ($params['id']) {
      CRM_Utils_Hook::pre('edit', 'InventoryProductVariant', $params['id'], $params);
    }
    else {
      CRM_Utils_Hook::pre('create', 'InventoryProductVariant', NULL, $params);
    }
    $id = $params['id'];
    $inventoryReferralsObj = new CRM_Inventory_DAO_InventoryProductVariant();
    $inventoryReferralsObj->copyValues($params);
    $inventoryReferralsObj->id = $id;

    $inventoryReferralsObj->save();
    CRM_Utils_Hook::post('create', 'InventoryProductVariant', $inventoryReferralsObj->id, $inventoryReferralsObj);
    return $inventoryReferralsObj;
  }

  /**
   * Callback for hook_civicrm_pre().
   *
   * @param \Civi\Core\Event\PreEvent $event
   *   Object event.
   *
   * @throws \CRM_Core_Exception
   */
  public static function self_hook_civicrm_pre(PreEvent $event): void {
    if ($event->action === 'update' || $event->action === 'edit') {
      // CRM_Inventory_BAO_InventoryProductChangelog::logStatusChange($event->id, $event->params);.
    }
  }

  /**
   * Fetch the object and store the values in the values array.
   *
   * @param array $params
   *   Input parameters to find object.
   * @param array $values
   *   Output values of the object.
   * @param bool $active
   *   Get active product variant only.
   *
   * @return array|null
   *   The found object or null
   */
  public static function getValues(array $params, array &$values, bool $active = FALSE): ?array {
    if (empty($params)) {
      return NULL;
    }
    $inventoryProductVariant = new CRM_Inventory_BAO_InventoryProductVariant();
    $inventoryProductVariant->copyValues($params);
    $inventoryProductVariant->find();
    $inventoryProductVariants = [];
    while ($inventoryProductVariant->fetch()) {
      if ($active && !$inventoryProductVariant->is_active) {
        continue;
      }

      CRM_Core_DAO::storeValues($inventoryProductVariant, $values[$inventoryProductVariant->id]);
      $inventoryProductVariants[$inventoryProductVariant->id] = $inventoryProductVariant;
    }
    return $inventoryProductVariants;
  }

  /**
   * Function to get Product variant details.
   *
   * @param int $id
   *   Product variant id.
   * @param bool $allRelatedEntity
   *   Load component data.
   *
   * @return array
   *   Product Details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function getProductVariant($id, $allRelatedEntity = FALSE): array {
    if ($allRelatedEntity) {
      $inventoryProductVariants = InventoryProductVariant::get(TRUE)
        ->addSelect('*', 'product_id.*', 'membership_id.*')
        ->addWhere('id', '=', $id)
        ->setLimit(1)
        ->execute();
    }
    else {
      $inventoryProductVariants = InventoryProductVariant::get(TRUE)
        ->addSelect('*')
        ->addWhere('id', '=', $id)
        ->setLimit(1)
        ->execute();
    }
    $result = $inventoryProductVariants->first();
    if ($allRelatedEntity) {
      $resultComponent = [];
      foreach ($result as $key => $value) {
        [$pre, $newKey] = explode('.', $key, 2);
        if ($pre == 'product_id' && !empty($newKey)) {
          $resultComponent['product'][$newKey] = $value;
        }
        elseif ($pre == 'membership_id' && !empty($newKey)) {
          $resultComponent['membership'][$newKey] = $value;
        }
        else {
          $resultComponent['product_variant'][$key] = $value;
        }
      }
      $result = $resultComponent;
    }
    return $result;
  }

  /**
   * Function to change product status.
   *
   * @param int $productVariantID
   *   Product variant id.
   * @param string $change
   *   Status string.
   * @param string $msg
   *   Message for reason.
   *
   * @return void
   *   No return.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function change($params, $change, $msg = NULL): void {
    $productVariantID = $this->id;
    $productVariantObj = $this;
    $productVariant = self::getProductVariant($productVariantID);
    if ($productVariant['product_id.has_sim']) {
      CRM_Inventory_BAO_InventoryProductChangelog::logStatusChange($productVariantID, $change);
      if ($msg) {
        $this->memo = $msg;
      }
      switch ($change) {
        case "REACTIVATE":
          $productVariantObj->is_active = TRUE;
          $productVariantObj->is_suspended = FALSE;
          $productVariantObj->expire_on = NULL;
          // TOD Custom field.
          if ($productVariantObj->membership_id || !$productVariant['membership_id.primary_device']) {
            // $this->setPrimary(x);
          }
          break;

        case "TERMINATE":
        case "LOST":
          $productVariantObj->is_active = FALSE;
          $productVariantObj->is_suspended = TRUE;
          $productVariantObj->expire_on = NULL;
          break;

        case "SUSPEND":
          $productVariantObj->is_suspended = TRUE;
          $productVariantObj->expire_on = now()->addDays(Conf::days_until_terminate() - Conf::days_until_suspend() + 1);
          break;
      }
      $productVariantObj->save();
    }
  }

  /**
   *
   */
  public function reactivateIt($msg = NULL) {
    $this->change("REACTIVATE", $msg);
    $this->save();
  }

  /**
   *
   */
  public function terminateIt($params, $msg = NULL) {
    $this->change($params, "TERMINATE", $msg);
    $this->save();
  }

  /**
   *
   */
  public function suspendIt($msg = NULL) {
    $this->change("SUSPEND", $msg);
    $this->save();
  }

  /**
   *
   */
  public function updateIt($msg = NULL) {
    $this->change("UPDATE", $msg);
    $this->save();
  }

  /**
   *
   */
  public function lostIt($productVariantID, $msg = NULL) {
    $this->change($productVariantID, "LOST", $msg);
    $this->save();
  }

  /**
   *
   */
  public function expireIt($msg = NULL) {
    $this->update([
      'memo' => $msg,
      'expire_on' => now()->addDays(Conf::days_until_suspend_replaced_devices()),
    ]);
  }

}
