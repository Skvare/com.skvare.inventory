<?php

/**
 * @file
 */

require_once 'inventory.civix.php';
require_once 'vendor/autoload.php';
// phpcs:disable
use Civi\Core\DAO\Event\PreUpdate;
use Civi\Core\DAO\Event\PostUpdate;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
// Function inventory_civicrm_preProcess($formName, &$form) {
//
// }.

/**
 * Implementation of hook_civicrm_permission.
 *
 * @param array $permissions
 *   Does not contain core perms -- only extension-defined perms.
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

      $fields['fair_value'] = [
        'name' => 'fair_value',
        'type' => CRM_Utils_Type::T_MONEY,
        'title' => E::ts('Fair Price?'),
        'description' => E::ts('Fair Price for membership level.'),
        'required' => FALSE,
        'usage' => [
          'import' => TRUE,
          'export' => TRUE,
          'duplicate_matching' => FALSE,
          'token' => FALSE,
        ],
        'precision' => [
          20,
          2,
        ],
        'where' => 'civicrm_membership_type.fair_value',
        'export' => TRUE,
        'default' => '0',
        'table_name' => 'civicrm_membership_type',
        'entity' => 'MembershipType',
        'bao' => 'CRM_Member_BAO_MembershipType',
        'localizable' => 0,
        'html' => [
          'type' => 'Text',
          'label' => E::ts("Fair Price"),
        ],
        'add' => NULL,
      ];
    };

  $lineItem = 'CRM_Price_DAO_LineItem';
  if (version_compare($civiVersion, '5.75.0') >= 0) {
    $lineItem = 'LineItem';
  }
  $entityTypes[$lineItem]['fields_callback'][]
    = function ($class, &$fields) {
      $fields['product_id'] = [
        'name' => 'product_id',
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
          'entity' => 'InventoryProduct',
          'key' => 'id',
          'on_delete' => 'SET NULL',
        ],
        'where' => 'civicrm_line_item.product_id',
        'table_name' => 'civicrm_line_item',
        'entity' => 'LineItem',
        'bao' => 'CRM_Price_BAO_LineItem',
      ];

      $fields['product_variant_id'] = [
        'name' => 'product_variant_id',
        'title' => ts('Product Variant ID'),
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

      $fields['membership_id'] = [
        'name' => 'membership_id',
        'type' => CRM_Utils_Type::T_INT,
        'title' => E::ts('Membership ID'),
        'description' => E::ts('Membership ID Associated with product.'),
        'required' => FALSE,
        'usage' => [
          'import' => TRUE,
          'export' => TRUE,
          'duplicate_matching' => TRUE,
          'token' => FALSE,
        ],
        'import' => TRUE,
        'where' => 'civicrm_line_item.membership_id',
        'export' => TRUE,
        'table_name' => 'civicrm_line_item',
        'entity' => 'LineItem',
        'bao' => 'CRM_Price_BAO_LineItem',
        'localizable' => 0,
        'FKClassName' => 'CRM_Member_DAO_Membership',
        'html' => [
          'type' => 'EntityRef',
          'label' => E::ts("Membership ID"),
        ],
        'add' => '5.63',
      ];

      $fields['subtitle'] = [
        'name' => 'subtitle',
        'title' => ts('Subtitle'),
        'type' => CRM_Utils_Type::T_STRING,
        'sql_type' => 'varchar(255)',
        'input_type' => 'Text',
        'description' => ts('Subtitle.'),
        'add' => '5.75',
        'default' => 'NULL',
        'html' => [
          'type' => 'Text',
        ],
        'input_attrs' => [
          'label' => ts('Subtitle.'),
        ],
        'where' => 'civicrm_line_item.subtitle',
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
      'permission' => 'access Inventory',
    ],
  ],
  ];
  array_splice($menu, 6, 0, $parentMenu);

  _inventory_civix_insert_navigation_menu($menu, 'inventory_main', [
    'label' => E::ts('Dashboard'),
    'name' => 'inventory_dashboard',
    'url' => 'civicrm/inventory',
    'permission' => NULL,
    'operator' => NULL,
    'separator' => 0,
    'permission' => 'access Inventory',
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
 */
function inventory_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
  $permissions['warehouse'] = [
    'default' => [['access warehouse', 'create warehouse', 'edit warehouse']],
    'get' => [['access warehouse']],
    'delete' => [['access warehouse', 'delete warehouse']],
    'create' => [['access warehouse', 'create warehouse']],
    'update' => [['access warehouse', 'edit warehouse']],
  ];
  $permissions['inventory_product'] = [
    'default' => [['access inventory product', 'create inventory product', 'edit inventory product']],
    'get' => [['access inventory product']],
    'delete' => [['access inventory product', 'delete inventory product']],
    'create' => [['access inventory product', 'create inventory product']],
    'update' => [['access inventory product', 'edit inventory product']],
  ];

  $permissions['inventory_product_variant'] = [
    'default' => [['access inventory product', 'create inventory product', 'edit inventory product']],
    'get' => [['access inventory product']],
    'delete' => [['access inventory product', 'delete inventory product']],
    'create' => [['access inventory product', 'create inventory product']],
    'update' => [['access inventory product', 'edit inventory product']],
  ];

  $permissions['inventory_sales'] = [
    'default' => [['access inventory sales', 'create inventory sales', 'edit inventory sales']],
    'get' => [['access inventory sales']],
    'delete' => [['access inventory sales', 'delete inventory sales']],
    'create' => [['access inventory sales', 'create inventory sales']],
    'update' => [['access inventory sales', 'edit inventory sales']],
  ];

  $permissions['inventory_shipment'] = [
    'default' => [['access shipment', 'create shipment', 'edit shipment']],
    'get' => [['access shipment']],
    'delete' => [['access shipment', 'delete shipment']],
    'create' => [['access shipment', 'create shipment']],
    'update' => [['access shipment', 'edit shipment']],
  ];

  $permissions['inventory_batch'] = [
    'default' => [['access device batch', 'administer Inventory']],
    'get' => [['access device batch']],
    'delete' => [['delete device batch', 'administer Inventory']],
    'create' => [['create device batch', 'administer Inventory']],
    'update' => [['edit device batch', 'administer Inventory']],
  ];

  $permissions['inventory_product_variant_replacement'] = [
    'default' => [['access device replacement', 'administer Inventory']],
    'get' => [['access device replacement']],
    'delete' => [['delete device replacement', 'administer Inventory']],
    'create' => [['create device replacement', 'administer Inventory']],
    'update' => [['edit device replacement', 'administer Inventory']],
  ];
  // Allow fairly liberal access to the InventoryProduct, InventoryProductVariant.
  if (_inventory_ApiCall($entity, $action)) {
    $params['check_permissions'] = FALSE;
  }
}

/**
 * This is a helper function to inventory_civicrm_alterAPIPermissions.
 *
 * @param string $entity
 *   The noun in an API call (e.g., volunteer_project)
 * @param string $action
 *   The verb in an API call (e.g., get)
 *
 * @return bool
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
  elseif ($formName == 'CRM_Member_Form_MembershipView') {
    $values = [];
    /** @var  CRM_Member_Form_MembershipView $form */
    $membershipID = CRM_Utils_Request::retrieve('id', 'Positive', NULL, TRUE);
    $membershipDevice = CRM_Inventory_BAO_InventoryProductVariant::getValues(['membership_id' => $membershipID], $values);
    $permissions = [CRM_Core_Permission::VIEW];
    $permissions[] = CRM_Core_Permission::EDIT;
    $permissions[] = CRM_Core_Permission::DELETE;

    $mask = CRM_Core_Action::mask($permissions);
    $links = [];
    $links[CRM_Core_Action::VIEW] = [
      'name' => ts('View'),
      'url' => 'civicrm/contact/view/inventory-productvariant',
      'qs' => 'action=view&reset=1&cid=%%cid%%&id=%%id%%',
      'f' => '?id=%%id%%',
      'title' => ts('Details'),
      // 'class' => 'popup',
      'target' => 'crm-popup',
    ];
    $links[CRM_Core_Action::UPDATE] = [
      'name' => ts('Update'),
      'url' => 'civicrm/inventory/device-from',
      'qs' => 'reset=1',
      'f' => '?InventoryProductVariant1=%%id%%',
      'title' => ts('Update'),
      // 'class' => 'popup',
      'target' => 'crm-popup',
    ];
    foreach ($values as $variantID => &$value) {
      $value['tag'] = CRM_Inventory_BAO_InventoryProductVariant::getTagsForVariant($variantID);
      $currentMask = $mask;
      $value['action'] = CRM_Core_Action::formLink($links,
        $currentMask,
        [
          'id' => $variantID,
          'cid' => $value['contact_id'],
        ],
        ts('more') . '...',
        FALSE,
        'productVariant.tab.row',
        'InventoryProductVariant',
        $variantID
      );
    }
    $form->assign('membershipDevices', $values);
  }
}

/**
 * Implements hook_civicrm_links().
 */
function inventory_civicrm_links(string $op, ?string $objectName, $objectID, array &$links, ?int &$mask, array &$values): void {
  if ($op == 'membershipType.manage.action' && $objectName == 'MembershipType') {
    foreach ($links as &$link) {
      if ($link['bit'] == CRM_Core_Action::UPDATE) {
        $link['f'] = '?membership_type_id=' . $values['id'];
        $link['class'] = 'no-popup';
      }
    }
  }
  if ($op == 'membership.tab.row' && $objectName == 'Membership') {
    // Get Device Linked with membership.
    [$status, $deviceID] = CRM_Inventory_BAO_Membership::getDeviceStatus($values['id']);
    if (!empty($deviceID)) {
      $label = E::ts('Activate Device');
      $statusAction = 'active';
      if ($status) {
        $label = E::ts('Terminate Device');
        $statusAction = 'terminate';
      }
      $links[] = [
        'name' => $label,
        'url' => "civicrm/inventory/device-action",
        'qs' => 'reset=1&mid=%%id%%&cid=%%cid%%&product_id=%%product_id%%&status=' . $statusAction,
        'title' => $label,
        'class' => '',
        'weight' => 25,
      ];
      $values['product_id'] = $deviceID;
    }
  }
}

/**
 * Implements hook_civicrm_post().
 */
function inventory_civicrm_post($op, $objectName, $objectId, &$objectRef, $params) {
  if ($objectName === 'InventorySales' && in_array($op, ['create', 'edit'])) {
    /** @var CRM_Inventory_BAO_InventorySales $saleObject */
    $saleObject = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventorySales', ['id' => $objectId], TRUE);
    if (empty($saleObject->code)) {
      $saleObject->code = CRM_Inventory_BAO_InventorySales::getNewCode();
      $saleObject->save();
    }
    // When sale order is crated andi it is paid , assign it to shipment.
    if ($saleObject->is_paid && !$saleObject->shipment_id) {
      CRM_Inventory_BAO_InventoryShipment::addShipmentToSale($saleObject->id);
    }
  }
  if ($objectName === 'Membership' && in_array($op, ['create', 'edit'])) {
    // Get current membership status.
    /** @var CRM_Member_BAO_Membership $objectRef */
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
  elseif ($objectName === 'Activity' && $op == 'create') {
    /** @var  CRM_Activity_BAO_Activity $objectRef */
    $machineNames = CRM_Core_OptionGroup::values('activity_type', FALSE, FALSE, FALSE, 'AND v.value = ' . $objectRef->activity_type_id, 'name');
    if (array_key_exists($objectRef->activity_type_id, $machineNames) &&
      $machineNames[$objectRef->activity_type_id] == 'Membership Renewal' &&
      $objectRef->source_record_id) {
      // source_record_id is membership ID.
      // Reactivate the device along with membership status.
      CRM_Inventory_BAO_Membership::resume($objectRef->source_record_id);

      $settingInfo = CRM_Inventory_Utils::getInventorySettingInfo();
      if (array_key_exists('inventory_membership_renewal_date_key_name', $settingInfo) &&
        !empty($settingInfo['inventory_membership_renewal_date'])) {
        // Update custom field which set about renewal date.
        $custom_params = [];
        $custom_params['entityID'] = $objectRef->source_record_id;
        $custom_params['entityType'] = 'Membership';
        $custom_params[$settingInfo['inventory_membership_renewal_date_key_name']] = date('Ymd');
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

/**
 * Implements hook_civicrm_searchKitTasks().
 */
function inventory_civicrm_searchKitTasks(array &$tasks, bool $checkPermissions, ?int $userID) {
  $tasks['InventoryProductChangelog']['export'] = [
    'title' => E::ts('Export Device'),
    'icon' => 'fa-random',
    'apiBatch' => [
      'action' => 'export',
      'confirmMsg' => E::ts('Are you sure you want to run export for %1 batch?'),
      'runMsg' => E::ts('Running export on %1 batch...'),
      'successMsg' => E::ts('Successfully ran export on %1 batch.'),
      'errorMsg' => E::ts('An error occurred while attempting to run export on %1 batch.'),
    ],
  ];
}

/**
 * Hook to add the symfony event listeners.
 *
 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
 *
 * @throws \CRM_Core_Exception
 */
function inventory_civicrm_container(ContainerBuilder $container) {
  $container->findDefinition('dispatcher')
    ->addMethodCall('addListener', [
      'civi.dao.preUpdate',
      'inventory_trigger_preupdate',
    ])
    ->addMethodCall('addListener', [
      'civi.dao.postUpdate',
      'inventory_trigger_postupdate',
    ]);
}

/**
 * This event is called before an entity is updated in the database.
 *
 * @param \Civi\Core\DAO\Event\PreUpdate $event
 */
function inventory_trigger_preupdate(PreUpdate $event) {
  try {
    $objectName = CRM_Inventory_Utils::getObjectNameFromObject($event->object);
    // Convert array into json string before storing into database.
    if ($objectName == 'InventoryShipmentLabels') {
      /** @var CRM_Inventory_BAO_InventoryShipmentLabels $event->object */
      if (is_array($event->object->shipment)) {
        $event->object->shipment = json_encode($event->object->shipment);
      }
      if (is_array($event->object->purchase)) {
        $event->object->purchase = json_encode($event->object->purchase);
      }
    }
  }
  catch (\Exception $ex) {
    // Do nothing.
  }
}

/**
 * This event is called after an entity is updated in the database.
 *
 * @param Civi\Core\DAO\Event\PostUpdate $event
 */
function inventory_trigger_postupdate(PostUpdate $event) {
  try {
    $objectName = CRM_Inventory_Utils::getObjectNameFromObject($event->object);
    // Convert json string into array after storing into database.
    if ($objectName == 'InventoryShipmentLabels') {
      /** @var CRM_Inventory_BAO_InventoryShipmentLabels $event->object */
      if (!is_array($event->object->shipment)) {
        if (!empty($event->object->shipment)) {
          $event->object->shipment = json_decode($event->object->shipment, TRUE);
        }
        else {
          $event->object->shipment = [];
        }
      }
      if (!is_array($event->object->purchase)) {
        if (!empty($event->object->purchase)) {
          $event->object->purchase = json_decode($event->object->purchase, TRUE);
        }
        else {
          $event->object->purchase = [];
        }
      }
    }
  }
  catch (\Exception $ex) {

  }
}
