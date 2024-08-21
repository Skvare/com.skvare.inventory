<?php
return [
  'name' => 'InventorySales',
  'table' => 'civicrm_inventory_sales',
  'class' => 'CRM_Inventory_DAO_InventorySales',
  'getInfo' => fn() => [
    'title' => ts('Inventory Sales'),
    'title_plural' => ts('Inventory Saleses'),
    'description' => ts('FIXME'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique InventorySales ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'contact_id' => [
      'title' => ts('Contact ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'sale_date' => [
      'title' => ts('Sale Date'),
      'sql_type' => 'datetime',
      'input_type' => 'Select Date',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'format_type' => 'activityDateTime',
      ],
    ],
    'status_id' => [
      'title' => ts('Sales Status'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'required' => TRUE,
      'description' => ts('Sales Status: \'placed\', \'shipped\', \'completed\''),
      'add' => '5.63',
      'unique_name' => 'inventory_sales_status_id',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'is_shipping_required' => [
      'title' => ts('Is Shipping Required'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_sales_is_shipping_required',
      'default' => FALSE,
      'usage' => ['export'],
    ],
    'shipment_id' => [
      'title' => ts('Shipment ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to shipments'),
      'entity_reference' => [
        'entity' => 'InventoryShipment',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'is_paid' => [
      'title' => ts('Is Paid'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_sales_is_paid',
      'default' => FALSE,
      'usage' => ['export'],
    ],
    'is_fulfilled' => [
      'title' => ts('Is fulfilled'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_sales_is_fulfilled',
      'default' => FALSE,
      'usage' => ['export'],
    ],
    'needs_assignment' => [
      'title' => ts('Needs Assignment'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_sales_needs_assignment',
      'default' => FALSE,
      'usage' => ['export'],
    ],
    'has_assignment' => [
      'title' => ts('Has Assignment'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_sales_has_assignment',
      'default' => FALSE,
      'usage' => ['export'],
    ],
    'is_tracking_sent' => [
      'title' => ts('Tracking Sent?'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_sales_is_tracking_sent',
      'default' => FALSE,
      'usage' => ['export'],
    ],
  ],
];
