<?php
use CRM_Inventory_ExtensionUtil as E;

/**
 * InventoryReferrals.create API specification (optional).
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_inventory_referrals_create_spec(&$spec) {
  // $spec['some_parameter']['api.required'] = 1;
}

/**
 * InventoryReferrals.create API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_referrals_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params, 'InventoryReferrals');
}

/**
 * InventoryReferrals.delete API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_referrals_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * InventoryReferrals.get API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_referrals_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params, TRUE, 'InventoryReferrals');
}

/**
 * InventoryReferrals.validate_code API specification.
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_inventory_referrals_validate_code_spec(&$spec) {
  $spec['code'] = [
    'api.required' => 1,
    'title' => 'Referral Code',
    'type' => CRM_Utils_Type::T_STRING,
  ];
}

/**
* InventoryReferrals.validate_code API.
 *
 * @param array $params
 *  Input parameter.
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_referrals_validate_code($params) {
  $isMembershipExist = \CRM_Inventory_BAO_Membership::findByReferralCode($params['code']);
  $result = [
    'valid' => !empty($isMembershipExist),
  ];
  return civicrm_api3_create_success($result, $params);
}


/**
 * InventoryReferrals.extend_membership API specification.
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_inventory_referrals_extend_membership_spec(&$spec) {
  $spec['membership_id'] = [
    'api.required' => 1,
    'title' => 'Membership ID',
    'type' => CRM_Utils_Type::T_INT,
  ];
}

/**
 * InventoryReferrals.extend_membership API.
 *
 * @param array $params
 *   Input parameter.
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_referrals_extend_membership($params) {
  $isExtended =
    \CRM_Inventory_BAO_InventoryReferrals::extendMembershipForReferral($params['membership_id']);

  $result = [
    'valid' => $isExtended,
  ];
  return civicrm_api3_create_success($result, $params);
}
