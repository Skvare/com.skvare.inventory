<?php
use CRM_Inventory_ExtensionUtil as E;

/**
 * InventoryProductVariant.create API specification (optional).
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_inventory_product_variant_create_spec(&$spec) {
  // $spec['some_parameter']['api.required'] = 1;
}

/**
 * InventoryProductVariant.create API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_product_variant_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params, 'InventoryProductVariant');
}

/**
 * InventoryProductVariant.delete API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_product_variant_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * InventoryProductVariant.get API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_product_variant_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params, TRUE, 'InventoryProductVariant');
}

/**
 * InventoryReferrals.extend_membership API specification.
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_inventory_product_variant_changestatus_spec(&$spec) {
  $spec['id'] = [
    'api.required' => 1,
    'title' => 'Product Variant ID',
    'type' => CRM_Utils_Type::T_INT,
  ];
  $spec['change_action'] = [
    'api.required' => 1,
    'title' => 'Change Action',
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['msg'] = [
    'title' => 'Message',
    'type' => CRM_Utils_Type::T_STRING,
  ];
}

/**
 * InventoryProductVariant.changestatus API.
 *
 * @param array $params
 *   Input parameter.
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_product_variant_changestatus($params) {
  if (in_array(strtoupper($params['change_action']), ['REACTIVATE', 'TERMINATE',
    'SUSPEND', 'UPDATE', 'LOST', 'EXPIRE', 'PROBLEM',
  ])) {
    $a = new \CRM_Inventory_BAO_InventoryProductVariant();
    $result[] = call_user_func_array([$a, 'changeStatus'],
      [$params['id'], strtoupper($params['change_action']), $params['msg']]);
    return civicrm_api3_create_success($result, $params);
  }
  else {
    throw new \CRM_Core_Exception('Invalid Action ' . $params['change_action']);
  }
}
