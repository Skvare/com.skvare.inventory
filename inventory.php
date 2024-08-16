<?php

require_once 'inventory.civix.php';
// phpcs:disable
use CRM_Inventory_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function inventory_civicrm_config(&$config) {
  _inventory_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function inventory_civicrm_install() {
  _inventory_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function inventory_civicrm_enable() {
  _inventory_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function inventory_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function inventory_civicrm_navigationMenu(&$menu) {
//  _inventory_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _inventory_civix_navigationMenu($menu);
//}

/**
 * Implementation of hook_civicrm_permission.
 *
 * @param array $permissions Does not contain core perms -- only extension-defined perms.
 */
function inventory_civicrm_permission(array &$permissions) {
  if (CRM_Core_Config::singleton()->userPermissionClass->isModulePermissionSupported()) {
    $permissions = array_merge($permissions, CRM_Inventory_Permission::getInventoryPermissions());
  }
}


/**
 * Implements hook_civicrm_entityType().
 */
function inventory_civicrm_entityTypes(&$entityTypes) {
  $entityTypes['CRM_Member_DAO_MembershipType']['fields_callback'][]
    = function ($class, &$fields) {
    $fields['shippable_to'] = [
      'name' => 'shippable_to',
      'type' => CRM_Utils_Type::T_STRING,
      'title' => ts('Membership Shipable to Country'),
      'description' => 'List of country where this membership is shipable.',
      'localizable' => 0,
      'maxlength' => 128,
      'size' => CRM_Utils_Type::HUGE,
      'usage' => [
        'import' => TRUE,
        'export' => TRUE,
        'duplicate_matching' => FALSE,
        'token' => TRUE,
      ],
      'import' => TRUE,
      'where' => 'civicrm_membership_type.shippable_to',
      'export' => TRUE,
      'table_name' => 'civicrm_membership_type',
      'entity' => 'MembershipType',
      'bao' => 'CRM_Member_BAO_MembershipType',
      'localizable' => 1,
      'html' => [
        'type' => 'Select',
        'multiple' => TRUE,
        'label' => ts("Shipable To Country."),
      ],
      'pseudoconstant' => [
        'callback' => 'CRM_Inventory_Utils::membershipTypeShipableTo',
      ],
    ];
  };
}
