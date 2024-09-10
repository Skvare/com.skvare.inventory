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
 * Implements hook_civicrm_qType().
 */
function inventory_civicrm_entityTypes(&$entityTypes) {
  $civiVersion = CRM_Utils_System::version();
  $membershipType = 'CRM_Member_DAO_MembershipType';
  if (version_compare($civiVersion, '5.75.0') >= 0) {
    $membershipType = 'MembershipType';
  }
  $entityTypes[$membershipType]['fields_callback'][]
    = function ($class, &$fields) {
    $fields['may_renew'] = [
      'name' => 'may_renew',
      'type' => CRM_Utils_Type::T_BOOLEAN,
      'title' => E::ts('May Renew?'),
      'description' => E::ts('If true, a member may renew at this membership level.'),
      'required' => FALSE,
      'usage' => [
        'import' => TRUE,
        'export' => TRUE,
        'duplicate_matching' => FALSE,
        'token' => FALSE,
      ],
      'where' => 'civicrm_membership_type.may_renew',
      'export' => TRUE,
      'default' => '1',
      'table_name' => 'civicrm_membership_type',
      'entity' => 'MembershipType',
      'bao' => 'CRM_Member_BAO_MembershipType',
      'localizable' => 0,
      'html' => [
        'type' => 'CheckBox',
      ],
      'add' => NULL,
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
      'input_attrs' => [
        'multiple' => '1',
      ],
      'html' => [
        'type' => 'Select',
        'multiple' => TRUE,
        'label' => ts("Shippable To Country."),
      ],
      'pseudoconstant' => [
        'callback' => 'CRM_Inventory_Utils::membershipTypeShippableTo',
      ],
      'serialize' => CRM_Core_DAO::SERIALIZE_SEPARATOR_BOOKEND,
    ];
  };

  $lineItem = 'CRM_Price_DAO_LineItem';
  if (version_compare($civiVersion, '5.75.0') >= 0) {
    $lineItem = 'LineItem';
  }
  $entityTypes[$lineItem]['fields_callback'][]
    = function ($class, &$fields) {
    $fields['product_variant_id'] = [
      'name' => 'product_variant_id',
      'title' => ts('Product ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'type' => CRM_Utils_Type::T_INT,
      'description' => ts('Product ID.'),
      'add' => '5.75',
      'default' => '0',
      'input_attrs' => [
        'label' => ts('Product ID'),
      ],
      'html' => [
        'type' => 'Number',
      ],
      'entity_reference' => [
        'entity' => 'InventoryProductVariant',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
      'where' => 'civicrm_line_item.product_variant_id',
      'table_name' => 'civicrm_line_item',
      'entity' => 'LineItem',
      'bao' => 'CRM_Price_BAO_LineItem',
    ];
    $fields['sale_id'] = [
      'name' => 'sale_id',
      'title' => ts('Sale ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'type' => CRM_Utils_Type::T_INT,
      'description' => ts('Sale ID.'),
      'add' => '5.75',
      'default' => '0',
      'input_attrs' => [
        'label' => ts('Sale ID'),
      ],
      'html' => [
        'type' => 'Number',
      ],
      'entity_reference' => [
        'entity' => 'InventorySales',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
      'where' => 'civicrm_line_item.sale_id',
      'table_name' => 'civicrm_line_item',
      'entity' => 'LineItem',
      'bao' => 'CRM_Price_BAO_LineItem',
    ];

    $fields['additional_details'] = [
      'name' => 'additional_details',
      'title' => ts('Product Additional Details'),
      'type' => CRM_Utils_Type::T_STRING,
      'sql_type' => 'varchar(255)',
      'input_type' => 'Text',
      'description' => ts('Product Additional Details.'),
      'add' => '5.75',
      'default' => 'NULL',
      'html' => [
        'type' => 'Text',
      ],
      'input_attrs' => [
        'label' => ts('Product Additional Details.'),
      ],
      'where' => 'civicrm_line_item.additional_details',
      'table_name' => 'civicrm_line_item',
      'entity' => 'LineItem',
      'bao' => 'CRM_Price_BAO_LineItem',
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
  _inventory_civix_insert_navigation_menu($menu, 'inventory_main', [
    'label' => E::ts('Warehouse shelf'),
    'name' => 'warehouse_shelf',
    'url' => 'civicrm/admin/options/warehouse_shelf',
    'permission' => 'administer Inventory,administer CiviCRM',
    'operator' => NULL,
    'separator' => 1,
  ]);
  _inventory_civix_insert_navigation_menu($menu, 'inventory_main', [
    'label' => E::ts('Product Brand'),
    'name' => 'product_brand',
    'url' => 'civicrm/admin/options/product_brand',
    'permission' => 'administer Inventory,administer CiviCRM',
    'operator' => NULL,
    'separator' => 1,
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

  $permissions['inventory_batch'] = [
    'default' => [['access device batch', 'administer Inventory']],
    'get' => [['access device batch',]],
    'delete' => [['delete device batch', 'administer Inventory',]],
    'create' => [['create device batch', 'administer Inventory',]],
    'update' => [['edit device batch', 'administer Inventory',]],
  ];

  $permissions['inventory_product_variant_replacement'] = [
    'default' => [['access device replacement', 'administer Inventory']],
    'get' => [['access device replacement',]],
    'delete' => [['delete device replacement', 'administer Inventory',]],
    'create' => [['create device replacement', 'administer Inventory',]],
    'update' => [['edit device replacement', 'administer Inventory',]],
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
    $shippableTo = CRM_Inventory_Utils::membershipTypeShippableTo();
    $form->addElement('checkbox', 'may_renew', ts('May Renew?'));
    $form->add('select', 'shippable_to', E::ts('Product Shippable to Country(s)'),
      $shippableTo, FALSE, ['class' => 'crm-select2 huge', 'multiple' => 1]);
    if ($form->_action & CRM_Core_Action::UPDATE) {
      $membershipExtras = CRM_Inventory_Utils::getMembershipTypeSettings($form->_id);
      $form->setDefaults($membershipExtras);
      \Civi::service('angularjs.loader')->addModules('afsearchMembershipBillingPlan');
      \Civi::service('angularjs.loader')->addModules('afsearchProductMembershipMappingList');
    }
  }
}

function inventory_civicrm_links(string $op, ?string $objectName, $objectID, array &$links, ?int &$mask, array &$values): void {
  if ($op == 'membershipType.manage.action' && $objectName == 'MembershipType') {
    foreach ($links as &$link) {
      if ($link['bit'] == CRM_Core_Action::UPDATE) {
        $link['f'] = '?membership_type_id=' . $values['id'];
        $link['class'] = 'no-popup';
      }
    }
  }
}

/**
 * Implements hook_civicrm_post().
 */
function inventory_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($objectName === 'Membership' && in_array($op, ['create', 'edit'])) {
    // Get current membership status.
    $activeMembershipStatus = CRM_Member_PseudoConstant::membershipStatus(NULL, "(is_current_member = 1)", 'id');
    // Check our status fit in current membership status list.
    if (isset($objectRef->status_id) && in_array($objectRef->status_id, $activeMembershipStatus)) {
      // Get the Setting field, where we mapped civicrm field with the fields.
      $settingInfo = CRM_Inventory_Utils::getInventorySettingInfo();
      if (!empty($settingInfo['inventory_referral_code_key_name'])) {
        // Get the current value of referral code on membership record.
        $custom_params = [];
        $custom_params['entityID'] = $objectId;
        $custom_params['entityType'] = 'Membership';
        $custom_params[$settingInfo['inventory_referral_code_key_name']] = 1;
        $customFieldValues = CRM_Core_BAO_CustomValueTable::getValues($custom_params);
      }
      // If the value is empty then generate the new code.
      if (empty($customFieldValues[$settingInfo['inventory_referral_code_key_name']])) {
        // Get the code.
        $code = CRM_Inventory_BAO_InventoryReferrals::getNewCode();
        $custom_params[$settingInfo['inventory_referral_code_key_name']] = $code;
        CRM_Core_BAO_CustomValueTable::setValues($custom_params);
      }
    }
  }
}

/**
 * Function to get  activity list.
 *
 * @return array|mixed
 *   Activity list.
 *
 * @throws CRM_Core_Exception
 */
function _inventory_activities() {
  static $activities;

  if (!$activities) {
    $activities = [
      'activity_referral_membership_extend' => civicrm_api3('OptionValue', 'getvalue', [
        'option_group_id' => 'activity_type',
        'name' => 'Referral-Membership Extended',
        'return' => 'value',
      ]),
      'activity_completed' => civicrm_api3('OptionValue', 'getvalue', [
        'option_group_id' => 'activity_status',
        'name' => 'Completed',
        'return' => 'value',
      ]),
    ];
  }

  return $activities;
}
