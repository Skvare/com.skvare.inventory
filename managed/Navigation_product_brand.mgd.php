<?php
use CRM_Inventory_ExtensionUtil as E;

return [
  [
    'name' => 'Navigation_Inventory_Navigation_Product_Brand',
    'entity' => 'Navigation',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => E::ts('Product Brand'),
        'name' => 'Product Brand',
        'url' => 'civicrm/admin/options/product_brand?reset=1',
        'icon' => NULL,
        'permission' => 'administer Inventory,administer CiviCRM',
        'permission_operator' => 'AND',
        'parent_id.name' => 'Inventory',
        'is_active' => TRUE,
        'weight' => 10,
        'has_separator' => NULL,
        'domain_id' => 'current_domain',
      ],
    ],
  ],
  [
    'name' => 'Navigation_Inventory_Navigation_warehouse_shelf',
    'entity' => 'Navigation',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => E::ts('Warehouse shelf'),
        'name' => 'Warehouse shelf',
        'url' => 'civicrm/admin/options/warehouse_shelf?reset=1',
        'icon' => NULL,
        'permission' => 'administer Inventory,administer CiviCRM',
        'permission_operator' => 'AND',
        'parent_id.name' => 'Inventory',
        'is_active' => TRUE,
        'weight' => 11,
        'has_separator' => NULL,
        'domain_id' => 'current_domain',
      ],
    ],
  ],
];
