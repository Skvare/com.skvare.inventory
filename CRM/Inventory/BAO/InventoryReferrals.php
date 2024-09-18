<?php

/**
 *
 */
class CRM_Inventory_BAO_InventoryReferrals extends CRM_Inventory_DAO_InventoryReferrals {

  const EXTEND_BY = '1 month';
  const CODE_LENGTH = 6;

  /**
   * The membership values if an existing membership.
   *
   * @var array
   */
  protected array $creator = [];

  /**
   * The membership values if an existing membership.
   *
   * @var array
   */
  protected array $consumer = [];

  /**
   * Function to add referral to membership and extend the memberships.
   *
   * @param int $membershipID
   *   Referral consume membership details.
   *
   * @return bool
   *   Status of extend using boolean.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function extendMembershipForReferral(int $membershipID):bool {
    // Get Active membership status list.
    $activeMembershipStatus = CRM_Member_PseudoConstant::membershipStatus(NULL, "(is_current_member = 1)", 'id');
    $settingInfo = CRM_Inventory_Utils::getInventorySettingInfo();
    // Get consume membership details using id or code used.
    $consumer = self::findMembership($membershipID ?? NULL);
    // Check consume membership is active.
    $isSuccessful = FALSE;
    if (in_array($consumer['status_id'], $activeMembershipStatus)) {
      // Check consume membership added referral code.
      if (!empty($consumer[$settingInfo['inventory_referral_consumed_code_cf_name_full']])) {
        // Find membership of creator of referral code.
        // Note: Membership should be active.
        $creator = self::findMembership(NULL, $consumer[$settingInfo['inventory_referral_consumed_code_cf_name_full']] ?? NULL);
        if (in_array($creator['status_id'], $activeMembershipStatus)) {
          // Validate the referral code, that same person is not consuming code.
          $error = self::validateConsumer($creator, $consumer);
          if (!empty($error)) {
            $error .= ", Creator ID " . $creator['id'] . ", " . "Consumer ID " . $consumer['id'];
            CRM_Core_Error::debug_var('Referral', $error);
            throw new CRM_Core_Exception($error);
          }
          $referralParams = [];
          $referralParams['creator_id'] = $creator['id'];
          $referralParams['consumer_id'] = $consumer['id'];
          $referralParams['created_date'] = date('Y-m-d H:i:s');
          $referralParams['before_end_date'] = $creator['end_date'];
          // Add 1 month in end date in log table.
          $referralParams['after_end_date'] = date('Y-m-d', strtotime("+" . self::EXTEND_BY, strtotime($creator['end_date'])));
          // Use the referral code used.
          $referralParams['referral_code'] = $consumer[$settingInfo['inventory_referral_consumed_code_cf_name_full']] ?? NULL;
          // Add entry to referral table for creator
          // membership end date and extended end date.
          $result = self::create($referralParams);

          // Extend the both membership records.
          self::extendMembership($creator['id'], $consumer['id']);
          $isSuccessful = TRUE;
        }
      }
    }
    return $isSuccessful;
  }

  /**
   * Takes an associative array and creates a referrals object.
   *
   * @param array $params
   *   An assoc array of name/value pairs.
   *
   * @return CRM_Inventory_DAO_InventoryReferrals
   *   Object of referrals.
   *
   * @throws \CRM_Core_Exception
   */
  public static function create($params = NULL) {
    if ($params['id']) {
      CRM_Utils_Hook::pre('edit', 'InventoryReferrals', $params['id'], $params);
    }
    else {
      CRM_Utils_Hook::pre('create', 'InventoryReferrals', NULL, $params);
    }
    $id = $params['id'];
    $inventoryReferralsObj = new CRM_Inventory_DAO_InventoryReferrals();
    $inventoryReferralsObj->copyValues($params);
    $inventoryReferralsObj->id = $id;

    $inventoryReferralsObj->save();
    CRM_Utils_Hook::post('create', 'InventoryReferrals', $inventoryReferralsObj->id, $inventoryReferralsObj);
    return $inventoryReferralsObj;
  }

  /**
   * Function generate random code.
   *
   * @return string
   *   Referral code.
   *
   * @throws \Random\RandomException
   */
  public static function generateCode():string {
    // Define CODE_LENGTH as needed.
    $codeLength = 5;
    return strtolower(bin2hex(random_bytes(self::CODE_LENGTH / 2)));
  }

  /**
   * Function to get referral code.
   *
   * @return string
   *   Randomly generated code.
   *
   * @throws \Random\RandomException
   */
  public static function getNewCode():string {
    $settingInfo = CRM_Inventory_Utils::getInventorySettingInfo();
    do {
      $code = self::generateCode();
    } while (self::checkDuplicate($code, $settingInfo));

    return $code;
  }

  /**
   * Check referral code is not duplicate.
   *
   * @param string $code
   *   Referral code.
   * @param array $settingInfo
   *   Setting info.
   *
   * @return bool
   *   Is duplicate.
   *
   * @throws CRM_Core_Exception
   */
  public static function checkDuplicate($code, $settingInfo):bool {
    if (empty($settingInfo)) {
      throw new CRM_Core_Exception('Referral Code Setting missing.');
    }
    // E.G. : SELECT COUNT(CUSTOMFIELD-Referral-CODE) FROM CUSTOM-TABLE WHERE
    //  CUSTOMFIELD-Referral-CODE = 'ABC';

    // This field myst be indexed, it gets used heavily.
    $sql = "select count({$settingInfo['inventory_referral_code']}) from {$settingInfo['inventory_referral_code_table']}  where {$settingInfo['inventory_referral_code']} = %1";
    $count = CRM_Core_DAO::singleValueQuery($sql, [1 => [$code, 'String']]);
    return (bool) $count;
  }

  /**
   * Check Code is valid.
   *
   * @param string $code
   *   Referral code.
   *
   * @return false|int
   *   Is valid code.
   */
  public static function isCode(string $code): bool|int {
    return preg_match('/^[a-z0-9]{' . self::CODE_LENGTH . '}$/', $code);
  }

  /**
   * Function to get membership details.
   *
   * @param int|null $id
   *   Membership id.
   * @param string|null $code
   *   Membership referral code.
   *
   * @return array|null
   *   Membership Details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function findMembership(int $id = NULL, string $code = NULL) {
    if ($id) {
      return CRM_Inventory_BAO_Membership::findById($id);
    }
    elseif ($code) {
      return CRM_Inventory_BAO_Membership::findByReferralCode($code);
    }
    else {
      throw new InvalidArgumentException("Referrals require one of: membership id, referral code.");
    }
  }

  /**
   * Extend the membership by certain period.
   *
   * @param int $creatorMembershipID
   *   Membership id.
   * @param int $consumerMembershipID
   *   Membership id.
   *
   * @return void
   *   Extend the Membership period.
   *
   * @throws CRM_Core_Exception
   *
   * @todo Check consumer membership already extended end date or not.
   */
  public static function extendMembership(int $creatorMembershipID, int $consumerMembershipID):void {
    // Extend creator membership end date.
    CRM_Inventory_BAO_Membership::extendEnrollment($creatorMembershipID, self::EXTEND_BY);
    // Extend consume membership end date.
    // Check @TODO.
    CRM_Inventory_BAO_Membership::extendEnrollment($consumerMembershipID, self::EXTEND_BY);
  }

  /**
   * Function to validate the referral.
   *
   * @param array $creator
   *   Membership details.
   * @param array $consumer
   *   Membership details.
   *
   * @return string
   *   Error message.
   */
  public static function validateConsumer(array $creator, array $consumer): string {
    $errors = '';
    // You cannot refer to your own membership record.
    if ($creator['id'] && $consumer['id'] && $creator['id'] == $consumer['id']) {
      $errors = "You cannot refer yourself";
    }
    elseif ($creator && $consumer && $creator['contact_id'] == $consumer['contact_id']) {
      // You cannot refer to your own contact.
      $errors = "You cannot refer yourself";
    }
    return $errors;
  }

}
