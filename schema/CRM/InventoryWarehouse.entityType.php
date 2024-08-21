<?php
return [
  'name' => 'InventoryWarehouse',
  'table' => 'civicrm_inventory_warehouse',
  'class' => 'CRM_Inventory_DAO_InventoryWarehouse',
  'getInfo' => fn() => [
    'title' => ts('Inventory Warehouse'),
    'title_plural' => ts('Inventory Warehouses'),
    'description' => ts('WareHouse Table'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Inventory Warehouse ID'),
      'unique_name' => 'inventory_warehouse_id',
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'name' => [
      'title' => ts('Warehouse Name'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_warehouse_name',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'address_id' => [
      'title' => ts('Address ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Contact'),
      'unique_name' => 'inventory_warehouse_address_id',
      'entity_reference' => [
        'entity' => 'Address',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'is_refrigerated' => [
      'title' => ts('refrigeration'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'unique_name' => 'inventory_warehouse_is_refrigerated',
      'default' => FALSE,
      'usage' => ['export'],
    ],
    'size' => [
      'title' => ts('Warehouse Size'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_warehouse_size',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'unused_size' => [
      'title' => ts('Warehouse Un-used Size'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_warehouse_unused_size',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
  ],
];
