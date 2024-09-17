<?php

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */

use Civi\Api4\Membership;

/**
 * CRM_Inventory_BAO_Membership
 *
 * Extension of CiviCRM core membership BAO class.
 */
class CRM_Inventory_BAO_Membership extends CRM_Member_BAO_Membership {

  /**
   * Function to get Membership using id.
   *
   * @param int $id
   *   Membership id.
   * @param bool $returnObject
   *   Return object or array.
   *
   * @return array|object|null
   *   Membership details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function findById($id, bool $returnObject = FALSE) {
    if ($returnObject) {
      $membershipObj = new CRM_Member_DAO_Membership();
      $membershipObj->id = $id;
      $membershipObj->find(TRUE);
      return $membershipObj;
    }
    else {
      $memberships = Membership::get(TRUE)
        ->addSelect('*', 'custom.*')
        ->addWhere('id', '=', $id)
        ->setLimit(1)
        ->execute();
      return $memberships->first();
    }
  }

  /**
   * Function to get Membership using referral code which is custom field.
   *
   * @param string $code
   *   Referral code.
   *
   * @return array|null
   *   Membership details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function findByReferralCode(string $code): ?array {
    $settingInfo = CRM_Inventory_Utils::getInventorySettingInfo();
    $memberships = Membership::get(TRUE)
      ->addSelect('*', 'custom.*')
      ->addJoin('MembershipStatus AS membership_status', 'INNER')
      ->addWhere('membership_status.is_current_member', '=', TRUE)
      ->addWhere($settingInfo['inventory_referral_code_cf_name_full'], '=', "{$code}")
      ->setLimit(1)
      ->execute();
    return $memberships->first();
  }

  /**
   * Function to terminate membership.
   *
   * @param int $id
   *   Membership ID.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function terminate(int $id): void {
    /** @var CRM_Member_BAO_Membership $membershipObject */
    $membershipObject = self::findById($id, TRUE);
    $membershipStatus = CRM_Member_BAO_Membership::buildOptions('status_id', 'validate');
    if (!empty($membershipObject->id)) {
      $membershipObject->status_id = array_search('Suspended', $membershipStatus);
      $productVariantParams = [];
      $productVariantParams['membership_id'] = $id;
      $values = [];
      $productVariants = CRM_Inventory_BAO_InventoryProductVariant::getValues($productVariantParams, $values, TRUE);
      // Terminate the Device linked with membership.
      foreach ($productVariants as $productVariantID => $productVariant) {
        /** @var CRM_Inventory_BAO_InventoryProductVariant $productVariant */
        $productVariant->changeStatus($values[$productVariantID],
          'TERMINATE', "Terminated because membership [membership:{$id}] terminated.");
      }
      $membershipObject->save();
    }
  }

  /**
   * Function to resume membership.
   *
   * @param int $id
   *   Membership ID.
   * @param bool $reactivateDevice
   *   Reactivate Device.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function resume(int $id, bool $reactivateDevice = TRUE): void {
    /** @var CRM_Member_BAO_Membership $membershipObject */
    $membershipObject = self::findById($id, TRUE);
    $membershipStatus = CRM_Member_BAO_Membership::buildOptions('status_id', 'validate');
    if (!empty($membershipObject->id)) {
      $current_status_id = array_search('Current', $membershipStatus);
      if ($current_status_id != $membershipObject->status_id) {
        $membershipObject->status_id = $current_status_id;
      }
      if ($reactivateDevice) {
        $productVariantParams = [];
        $productVariantParams['membership_id'] = $id;
        $productVariantParams['is_primary'] = TRUE;
        $values = [];
        $productVariants = CRM_Inventory_BAO_InventoryProductVariant::getValues($productVariantParams, $values);
        // Activate the Primary Device linked with membership.
        foreach ($productVariants as $productVariantID => $productVariant) {
          /** @var CRM_Inventory_BAO_InventoryProductVariant $productVariant */
          if (!$productVariant->is_active || $productVariant->is_suspended) {
            $productVariant->changeStatus($productVariantID,
              'REACTIVATE', "Reactivated because membership [membership:{$id}] resumed.");
          }
        }
      }
      // $membershipObject->save();
    }
  }

  /**
   * Extend Membership End date.
   *
   * @param int $membershipID
   *   Membership id.
   * @param string $extendBy
   *   Date offset info.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   */
  public static function extendEnrollment(int $membershipID, string $extendBy = ''): void {
    $memberDAO = new CRM_Member_DAO_Membership();
    $memberDAO->id = $membershipID;
    if (!$memberDAO->find(TRUE)) {
      // If no membership then just return from here.
      return;
    }
    $beforeEndDate = $memberDAO->end_date;
    $memberDAO->end_date = date('Y-m-d', strtotime("+$extendBy", strtotime($memberDAO->end_date)));
    $subjectForActivity = "Membership Extended from {$beforeEndDate} to {$memberDAO->end_date} ({$extendBy})";
    $memberDAO->save();

    // Log the membership extent details.
    $logStartDate = CRM_Utils_Date::isoToMysql($memberDAO->start_date);
    $values = self::getStatusANDTypeValues($memberDAO->id);

    $membershipLog = [
      'membership_id' => $memberDAO->id,
      'status_id' => $memberDAO->status_id,
      'start_date' => $logStartDate,
      'end_date' => CRM_Utils_Date::isoToMysql($memberDAO->end_date),
      'modified_date' => CRM_Utils_Time::date('Ymd'),
      'membership_type_id' => $values[$memberDAO->id]['membership_type_id'],
      'max_related' => $memberDAO->max_related,
    ];

    if (CRM_Core_Session::singleton()->get('userID')) {
      $membershipLog['modified_id'] = CRM_Core_Session::singleton()->get('userID');
    }
    else {
      $membershipLog['modified_id'] = $memberDAO->contact_id;
    }
    // Add membership log.
    CRM_Member_BAO_MembershipLog::add($membershipLog);

    // Create Activity for Membership extend.
    $activities = _inventory_activities();
    $activityParams = [
      'source_record_id' => $memberDAO->contact_id,
      'target_contact_id' => $memberDAO->contact_id,
      'source_contact_id' => CRM_Core_Session::getLoggedInContactID() ?? $membershipLog['modified_id'],
      'activity_type_id' => $activities['activity_referral_membership_extend'],
      'status_id' => $activities['activity_completed'],
      'subject' => $subjectForActivity,
      'check_permissions' => 0,
      'details' => "Membership was extended by {$extendBy} ",
    ];
    civicrm_api3('Activity', 'create', $activityParams);
  }

  /**
   * Get device status on membership.
   *
   * @param int $mid
   *   Membership ID.
   *
   * @return array
   *   Device status and id.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function getDeviceStatus(int $mid): array {
    /** @var CRM_Inventory_BAO_Membership $membershipObject */
    $membershipObject = self::findById($mid, TRUE);
    $inputParams = ['membership_id' => $membershipObject->id];
    $values = [];
    $deviceObject = CRM_Inventory_BAO_InventoryProductVariant::getValues($inputParams, $values);
    foreach ($deviceObject as $productVariantID => $productVariant) {
      /** @var CRM_Inventory_BAO_InventoryProductVariant $productVariant */
      if ($productVariant->id) {
        if ($productVariant->isTerminated() || $productVariant->isSuspended()) {
          return [FALSE, $productVariant->id];
        }
        return [TRUE, $productVariant->id];
      }
    }
    return [FALSE, NULL];
  }

}
