<?php
use CRM_Inventory_ExtensionUtil as E;

/**
 * InventoryBatch.create API specification (optional).
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_inventory_batch_create_spec(&$spec) {
  // $spec['some_parameter']['api.required'] = 1;
}

/**
 * InventoryBatch.create API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_batch_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params, 'InventoryBatch');
}

/**
 * InventoryBatch.delete API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_batch_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * InventoryBatch.get API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_inventory_batch_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params, TRUE, 'InventoryBatch');
}
