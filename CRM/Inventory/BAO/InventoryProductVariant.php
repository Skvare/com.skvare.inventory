<?php

use Civi\API\Exception\UnauthorizedException;
use Civi\Api4\InventoryProductVariant;
use Civi\Core\Event\PreEvent;

/**
 * CRM_Inventory_BAO_InventoryProductVariant.
 *
 * Product variant class.
 */
class CRM_Inventory_BAO_InventoryProductVariant extends CRM_Inventory_DAO_InventoryProductVariant {
  use CRM_Inventory;
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
  public static function getProductVariant(int $id, bool $allRelatedEntity = FALSE): array {
    if ($allRelatedEntity) {
      $inventoryProductVariants = InventoryProductVariant::get(TRUE)
        ->addSelect('*', 'status:label', 'product_id.*', 'membership_id.*', 'membership_id.status_id:name', 'contact_id.display_name')
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
        $newKey = str_replace(':', '_', $newKey);
        $key = str_replace(':', '_', $key);
        if ($pre == 'product_id' && !empty($newKey)) {
          $resultComponent['product'][$newKey] = $value;
        }
        elseif ($pre == 'membership_id' && !empty($newKey)) {
          $resultComponent['membership'][$newKey] = $value;
        }
        elseif ($pre == 'contact_id' && !empty($newKey)) {
          $resultComponent['contact'][$newKey] = $value;
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
   * @param string|null $msg
   *   Message for reason.
   *
   * @return object
   *   No return.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function changeStatus(int $productVariantID, string $change, string $msg = NULL): object {
    $productVariantObj = new CRM_Inventory_BAO_InventoryProductVariant();
    $productVariantObj->id = $productVariantID;
    if ($productVariantObj->find(TRUE)) {
      $productVariant = self::getProductVariant($productVariantID, TRUE);
      if ($productVariant['product']['has_sim']) {
        CRM_Inventory_BAO_InventoryProductChangelog::logStatusChange($productVariantID, $change);
        if (!empty($msg)) {
          // $productVariant->memo = $msg;
        }
        switch ($change) {
          case "REACTIVATE":
            $productVariantObj->is_active = TRUE;
            $productVariantObj->is_suspended = FALSE;
            $productVariantObj->expire_on = NULL;
            // If membership present and device is not set primary then set it.
            if ($productVariantObj->membership_id &&
              !$productVariantObj->is_primary) {
              $productVariantObj->setPrimary(TRUE);
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
            $productVariantObj->expire_on = date('Y-m-d H:i:s');
            break;

          case "EXPIRE":
            // $productVariantObj->memo = $msg;
            // The number of days to keep a device active after it has been
            // replaced.
            $productVariantObj->expire_on = date('Y-m-d', strtotime('+14days'));
            break;
        }
        $productVariantObj->save();
      }
    }

    return $productVariantObj;
  }

  /**
   * Function to set device as primary.
   *
   * @param bool $newPrimaryValue
   *   Set device primary.
   * @param bool $autoExpire
   *   Auto Expire device.
   * @param string|null $expireMessage
   *   Message.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function setPrimary(bool $newPrimaryValue, bool $autoExpire = TRUE, string $expireMessage = NULL): void {
    if ($newPrimaryValue) {
      $this->is_primary = TRUE;
      $this->expire_on = NULL;
      // $this->memo = 'Assigned as primary';
      if ($this->membership_id) {
        // $this->membership->update(['primary_device_id' => $this->id]);
        $value = [];
        $membershipDevice = self::getValues(['membership_id' => $this->membership_id], $value, TRUE);
        foreach ($membershipDevice as $device) {
          /** @var CRM_Inventory_BAO_InventoryProductVariant $device */
          if ($device->id !== $this->id) {
            $device->is_primary = FALSE;
            if ($autoExpire) {
              $expireMessage = $expireMessage ?? "Expiring because device [device:{$this->id}] made primary";
              // Is this device is not terminated then expire it.
              if (!$device->isTerminated()) {
                try {
                  $device->changeStatus($device->id, 'EXPIRE', $expireMessage);
                }
                catch (UnauthorizedException $e) {

                }
                catch (CRM_Core_Exception $e) {

                }
              }
            }
          }
        }
      }
    }
    else {
      $this->is_primary = FALSE;
      if ($this->membership_id) {
        $newPrimaryBestGuess = InventoryProductVariant::get(TRUE)
          ->addWhere('membership_id', '=', $this->membership_id)
          ->addWhere('id', '!=', $this->id)
          ->addWhere('is_active', '=', 1)
          ->addOrderBy('created_at', 'DESC')
          ->setLimit(1)
          ->execute()->first();
        if ($newPrimaryBestGuess) {
          $newPrimaryBestGuess['is_primary'] = TRUE;
          InventoryProductVariant::save($newPrimaryBestGuess);
          // @todo
          // $this->membership->update(['primary_device_id' => $newPrimaryBestGuess->id]);
          if ($autoExpire) {
            $expireMessage = $expireMessage ?? "Expiring because device [device:{$this->id}] made primary";
            if (!$this->isTerminated()) {
              try {
                $this->changeStatus($this->id, 'EXPIRE', $expireMessage);
              }
              catch (UnauthorizedException $e) {

              }
              catch (CRM_Core_Exception $e) {

              }
            }
          }
        }
      }
    }
    $this->save();
  }

  /**
   * Function to check status.
   *
   * @return bool
   *   Boolean value.
   */
  public function isTerminated(): bool {
    return !$this->is_active && $this->is_suspended;
  }

  /**
   * Function to check status.
   *
   * @return bool
   *   Boolean value.
   */
  public function isProblem(): bool {
    return $this->is_problem;
  }

  /**
   * Function to check status.
   *
   * @return bool
   *   Boolean value.
   */
  public function isReadyForShipment(): bool {
    return $this->is_active && !$this->is_suspended && is_null($this->expire_on)
      && is_null($this->membership_id) && is_null($this->contact_id);
  }

  /**
   * Function to check status.
   *
   * @return bool
   *   Boolean value.
   */
  public function isInactive(): bool {
    return !$this->is_active || $this->is_suspended;
  }

  /**
   * Function to check status.
   *
   * @return bool
   *   Boolean value.
   */
  public function isSuspended(): bool {
    return $this->is_suspended;
  }

  /**
   * Function to check status.
   *
   * @return bool
   *   Boolean value.
   */
  public function isAssigned(): bool {
    return $this->contact_id && $this->membership_id && $this->status === 'assigned_to_member';
  }

  /**
   * Function to get button label and class.
   *
   * @return array[]
   *   Status array.
   */
  public static function statusTagClass(): array {
    return [
      //'unlink' => ['id' => 'unlink', 'label' => 'Unlink from Member','class' => 'btn btn-danger'],
      'reactivate' => ['id' => 'REACTIVATE', 'label' => 'Reactivate', 'class' => 'btn btn-success'],
      'terminated' => ['id' => 'TERMINATE', 'label' => 'Terminate', 'class' => 'btn btn-danger'],
      'lost' => ['id' => 'LOST', 'label' => 'Lost', 'class' => 'btn btn-danger'],
      'problem' => ['id' => 'PROBLEM', 'label' => 'Problem', 'class' => 'btn btn-danger'],
      'suspend' => ['id' => 'SUSPEND', 'label' => 'Suspend', 'class' => 'btn btn-primary'],
      'update' => ['id' => 'UPDATE', 'label' => 'Update', 'class' => 'btn btn-primary'],
    ];
  }

  /**
   * Function to get tag for product variant.
   *
   * @param int $id
   *   Product ID.
   *
   * @return array
   *   Tags.
   */
  public static function getTagsForVariant($id): array {
    $searchParams = ['id' => $id];
    $values = [];
    $mapping =
      [
        'isInactive' => ['label' => 'inactive', 'class' => 'btn btn-danger'],
        'isAssigned' => ['label' => 'assigned', 'class' => 'btn btn-success'],
        'isReadyForShipment' => ['label' => 'readyforshipment', 'class' => 'btn btn-success'],
        'isTerminated' => ['label' => 'terminated', 'class' => 'btn btn-danger'],
        'isProblem' => ['label' => 'problem', 'class' => 'btn btn-primary'],
        'isSuspended' => ['label' => 'suspended', 'class' => 'btn btn-primary'],
      ];
    $actions = ['isInactive', 'isAssigned', 'isReadyForShipment', 'isTerminated', 'isProblem', 'isSuspended'];
    $productVariantObject =
      CRM_Inventory_BAO_InventoryProductVariant::getValues($searchParams, $values)[$id];
    $htmlForTag = [];
    foreach ($actions as $action) {
      if ($productVariantObject->$action()) {
        $htmlForTag[$action] = "<span class='{$mapping[$action]['class']}' style='border-radius: 2px;margin-right: 4px;'>{$mapping[$action]['label']}</span>";
      }
    }
    return $htmlForTag;
  }

}
