<?php
return [
  'name' => 'InventoryShipmentLabels',
  'table' => 'civicrm_inventory_shipment_labels',
  'class' => 'CRM_Inventory_DAO_InventoryShipmentLabels',
  'getInfo' => fn() => [
    'title' => ts('Inventory Shipment Labels'),
    'title_plural' => ts('Inventory Shipment Labelses'),
    'description' => ts('FIXME'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique InventoryShipmentLabels ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'created_date' => [
      'title' => ts('Created Date'),
      'sql_type' => 'datetime',
      'input_type' => 'Select Date',
      'unique_name' => 'inventory_shipment_label_created_date',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'format_type' => 'activityDateTime',
      ],
    ],
    'updated_date' => [
      'title' => ts('Updated Date'),
      'sql_type' => 'datetime',
      'input_type' => 'Select Date',
      'unique_name' => 'inventory_shipment_label_updated_date',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'format_type' => 'activityDateTime',
      ],
    ],
    'sales_id' => [
      'title' => ts('Sales ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Sales'),
      'entity_reference' => [
        'entity' => 'InventorySales',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'is_valid' => [
      'title' => ts('Is Valid?'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_shipment_label_is_valid',
      'default' => FALSE,
      'usage' => ['export'],
    ],
    'is_paid' => [
      'title' => ts('Is Paid?'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_shipment_label__is_paid',
      'default' => FALSE,
      'usage' => ['export'],
    ],
    'has_error' => [
      'title' => ts('Has Error?'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_shipment_label_has_error',
      'default' => FALSE,
      'usage' => ['export'],
    ],
    'provider' => [
      'title' => ts('Shipment Provider'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_shipment_label_provider',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'amount' => [
      'title' => ts('Amount'),
      'sql_type' => 'decimal(20,2)',
      'input_type' => 'Text',
      'required' => TRUE,
      'description' => ts('Shipment Amount'),
      'unique_name' => 'inventory_shipment_label_amount',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'label' => ts('Label Amount'),
      ],
    ],
    'currency' => [
      'title' => ts('Currency'),
      'sql_type' => 'varchar(4)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_shipment_label_currency',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 4,
      ],
    ],
    'resource_id' => [
      'title' => ts('Resource ID'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_shipment_label_resource_id',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'tracking_id' => [
      'title' => ts('Tracking ID'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_shipment_label_tracking_id',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'shipment' => [
      'title' => ts('Shipment Details'),
      'sql_type' => 'text',
      'input_type' => 'TextArea',
      'description' => ts('Shipment details.'),
      'add' => '5.63',
      'unique_name' => 'inventory_shipment_label_shipment',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'rows' => 4,
        'cols' => 60,
      ],
    ],
    'purchase' => [
      'title' => ts('Shipment Label Purchase'),
      'sql_type' => 'text',
      'input_type' => 'TextArea',
      'description' => ts('Shipment Purchase details.'),
      'add' => '5.63',
      'unique_name' => 'inventory_shipment_label_purchase',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'rows' => 4,
        'cols' => 60,
      ],
    ],
    'contact_id' => [
      'title' => ts('Contact ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
  ],
];
