<?php
return [
  'name' => 'InventoryShipment',
  'table' => 'civicrm_inventory_shipment',
  'class' => 'CRM_Inventory_DAO_InventoryShipment',
  'getInfo' => fn() => [
    'title' => ts('Inventory Shipment'),
    'title_plural' => ts('Inventory Shipments'),
    'description' => ts('Shipment Details'),
    'log' => TRUE,
  ],
  'getIndices' => fn() => [
    'index_created_date' => [
      'fields' => [
        'created_date' => TRUE,
      ],
    ],
    'index_updated_date' => [
      'fields' => [
        'updated_date' => TRUE,
      ],
    ],
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique InventoryShipment ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'contact_id' => [
      'title' => ts('Created By'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Contact'),
      'unique_name' => 'inventory_shipment_contact_id',
      'input_attrs' => [
        'label' => ts('Created By'),
      ],
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'created_date' => [
      'title' => ts('Created Date'),
      'sql_type' => 'timestamp',
      'input_type' => 'Select Date',
      'readonly' => TRUE,
      'description' => ts('When was the shipment was created.'),
      'unique_name' => 'inventory_shipment_created_date',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'format_type' => 'activityDateTime',
        'label' => ts('Created Date'),
      ],
    ],
    'modified_id' => [
      'title' => ts('Modified By Contact ID'),
      'sql_type' => 'int unsigned',
      'input_type' => NULL,
      'readonly' => TRUE,
      'description' => ts('FK to Contact ID of person under whose credentials this data modification was made.'),
      'unique_name' => 'inventory_shipment_modified_id',
      'input_attrs' => [
        'label' => ts('Modified By'),
      ],
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'updated_date' => [
      'title' => ts('Updated date'),
      'sql_type' => 'timestamp',
      'input_type' => 'Select Date',
      'readonly' => TRUE,
      'unique_name' => 'inventory_shipment_updated_date',
      'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'format_type' => 'activityDateTime',
        'label' => ts('Modified Date'),
      ],
    ],
    'shipped_date' => [
      'title' => ts('Shipped At'),
      'sql_type' => 'datetime',
      'input_type' => 'Select Date',
      'unique_name' => 'inventory_shipment_shippe_date',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'format_type' => 'activityDateTime',
      ],
    ],
    'is_shipped' => [
      'title' => ts('Is shipped?'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_shipment_is_shipped',
      'default' => FALSE,
      'usage' => ['export'],
    ],
    'is_finished' => [
      'title' => ts('Is Finished'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_shipment_is_finished',
      'default' => FALSE,
      'usage' => ['export'],
    ],
  ],
];
