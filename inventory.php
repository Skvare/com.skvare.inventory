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
  $civiVersion = CRM_Utils_System::version();
  $type = 'CRM_Member_DAO_MembershipType';
  if (version_compare($civiVersion, '5.75.0') >= 0) {
    $type = 'MembershipType';
  }
  $entityTypes[$type]['fields_callback'][]
    = function ($class, &$fields) {
    $fields['signup_fee'] = [
      'name' => 'signup_fee',
      'title' => ts('Signup Fee'),
      'sql_type' => 'decimal(18,9)',
      'type' => CRM_Utils_Type::T_FLOAT,
      'input_type' => 'Text',
      'description' => ts('This is the fee charged for the first time while buying the product.'),
      'add' => '5.75',
      'default' => '0',
      'input_attrs' => [
        'label' => ts('Signup Fee'),
      ],
      'where' => 'civicrm_membership_type.signup_fee',
      'table_name' => 'civicrm_membership_type',
      'entity' => 'MembershipType',
      'bao' => 'CRM_Member_BAO_MembershipType',
    ];
    $fields['renewal_fee'] = [
      'name' => 'renewal_fee',
      'title' => ts('Renewal Fee'),
      'sql_type' => 'decimal(18,9)',
      'type' => CRM_Utils_Type::T_FLOAT,
      'input_type' => 'Text',
      'description' => ts('This fee will be charged for renewal of membership.'),
      'add' => '5.75',
      'default' => '0',
      'input_attrs' => [
        'label' => ts('Renewal Fee'),
      ],
      'where' => 'civicrm_membership_type.renewal_fee',
      'table_name' => 'civicrm_membership_type',
      'entity' => 'MembershipType',
      'bao' => 'CRM_Member_BAO_MembershipType',
    ];
    $fields['shippable_to'] = [
      'name' => 'shippable_to',
      'type' => CRM_Utils_Type::T_STRING,
      'title' => ts('Membership Shippable to Country'),
      'description' => 'List of country where this membership is shippable.',
      'localizable' => 0,
      'maxlength' => 128,
      'size' => CRM_Utils_Type::HUGE,
      'import' => TRUE,
      'where' => 'civicrm_membership_type.shippable_to',
      'export' => TRUE,
      'table_name' => 'civicrm_membership_type',
      'entity' => 'MembershipType',
      'bao' => 'CRM_Member_BAO_MembershipType',
      'localizable' => 1,
      'input_attrs' => [
        'multiple' => '1',
      ],
      'html' => [
        'type' => 'Select',
        'multiple' => TRUE,
        'label' => ts("Shippable To Country."),
      ],
      'pseudoconstant' => [
        'callback' => 'CRM_Inventory_Utils::membershipTypeShipableTo',
      ],
      'serialize' => CRM_Core_DAO::SERIALIZE_SEPARATOR_BOOKEND,
    ];
  };
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function inventory_civicrm_navigationMenu(&$menu) {
  $parentMenu = [[
    'attributes' => [
      'label' => E::ts('Inventory'),
      'name' => 'inventory_main',
      'url' => NULL,
      'operator' => NULL,
      'separator' => 0,
      'active' => 1,
      'icon' => 'crm-i fa-ticket',
      'weight' => 35,
      'permission' => 'access Inventory'
    ]]];
  array_splice($menu, 6, 0, $parentMenu);

  _inventory_civix_insert_navigation_menu($menu, 'inventory_main', [
    'label' => E::ts('Dashboard'),
    'name' => 'inventory_dashboard',
    'url' => 'civicrm/inventory',
    'permission' => NULL,
    'operator' => NULL,
    'separator' => 0,
    'permission' => 'access Inventory'
  ]);

  _inventory_civix_insert_navigation_menu($menu, 'inventory_main', [
    'label' => E::ts('Shipping'),
    'name' => 'inventory_shipping',
    'url' => 'civicrm/inventory/shipping',
    'permission' => NULL,
    'operator' => NULL,
    'separator' => 1,
    'permission' => 'access shipment',
    'icon' => 'crm-i fa-truck',
  ]);

  _inventory_civix_insert_navigation_menu($menu, 'inventory_main', [
    'label' => E::ts('Inventory Settings'),
    'name' => 'inventory_settings',
    'url' => 'civicrm/inventory/setting',
    'permission' => NULL,
    'operator' => NULL,
    'separator' => 1,
    'permission' => 'administer Inventory',
    'icon' => 'crm-i fa-wrench',
  ]);

  _inventory_civix_navigationMenu($menu);
}

/**
 * Implements hook_civicrm_alterAPIPermissions().
 *
 * Set Inventory permissions for APIv3.
 */
function inventory_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
  $permissions['warehouse'] = [
    'default' => [['access warehouse', 'create warehouse', 'edit warehouse']],
    'get' => [['access warehouse',]],
    'delete' => [['access warehouse', 'delete warehouse',]],
    'create' => [['access warehouse', 'create warehouse',]],
    'update' => [['access warehouse', 'edit warehouse',]],
  ];
  $permissions['inventory_product'] = [
    'default' => [['access inventory product', 'create inventory product', 'edit inventory product',]],
    'get' => [['access inventory product',]],
    'delete' => [['access inventory product', 'delete inventory product',]],
    'create' => [['access inventory product', 'create inventory product',]],
    'update' => [['access inventory product', 'edit inventory product',]],
  ];

  $permissions['inventory_product_variant'] = [
    'default' => [['access inventory product', 'create inventory product', 'edit inventory product',]],
    'get' => [['access inventory product',]],
    'delete' => [['access inventory product', 'delete inventory product',]],
    'create' => [['access inventory product', 'create inventory product',]],
    'update' => [['access inventory product', 'edit inventory product',]],
  ];

  $permissions['inventory_sales'] = [
    'default' => [['access inventory sales', 'create inventory sales', 'edit inventory sales']],
    'get' => [['access inventory sales',]],
    'delete' => [['access inventory sales', 'delete inventory sales',]],
    'create' => [['access inventory sales', 'create inventory sales',]],
    'update' => [['access inventory sales', 'edit inventory sales']],
  ];

  $permissions['inventory_shipment'] = [
    'default' => [['access shipment', 'create shipment', 'edit shipment']],
    'get' => [['access shipment',]],
    'delete' => [['access shipment', 'delete shipment',]],
    'create' => [['access shipment', 'create shipment',]],
    'update' => [['access shipment', 'edit shipment']],
  ];

  $permissions['inventory'] = [
    'default' => [['access Inventory', 'administer Inventory']],
    'get' => [['access Inventory',]],
    'delete' => [['access Inventory', 'administer Inventory',]],
    'create' => [['access Inventory', 'administer Inventory',]],
    'update' => [['access Inventory', 'administer Inventory',]],
  ];
  // allow fairly liberal access to the InventoryProduct, InventoryProductVariant.
  if (_inventory_ApiCall($entity, $action)) {
    $params['check_permissions'] = FALSE;
  }
}

/**
 * This is a helper function to inventory_civicrm_alterAPIPermissions.
 * @param string $entity
 *   The noun in an API call (e.g., volunteer_project)
 * @param string $action
 *   The verb in an API call (e.g., get)
 * @return boolean
 *   True if the API call is of the type that the vol opps UI depends on.
 */
function _inventory_ApiCall($entity, $action) {
  $actions = [
    'get',
    'getlist',
    'getsingle',
  ];
  $entities = ['InventoryProduct', 'InventoryProductVariant'];

  return (in_array($entity, $entities) && in_array($action, $actions));
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * Add Inventory related fields to form.
 */
function inventory_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Member_Form_MembershipType') {
    $attributes = CRM_Core_DAO::getAttribute('CRM_Member_DAO_MembershipType');
    $shippableTo = CRM_Inventory_Utils::membershipTypeShipableTo();
    $form->addMoney('signup_fee',
      ts('Signup Fee'),
      FALSE,
      $attributes['signup_fee'],
      FALSE, 'currency', NULL, FALSE
    );

    $form->addMoney('renewal_fee',
      ts('Renewal Fee'),
      FALSE,
      $attributes['renewal_fee'],
      FALSE, 'currency', NULL, FALSE
    );

    $form->add('select', 'shippable_to', E::ts('Product Shippable to Country(s)'),
      $shippableTo, FALSE, ['class' => 'crm-select2 huge', 'multiple' => 1]);
    if ($form->_action & CRM_Core_Action::UPDATE) {
      $membershipExtras = CRM_Inventory_Utils::getMembershipTypeSettings($form->_id);
      $form->setDefaults($membershipExtras);
    }
  }
}
