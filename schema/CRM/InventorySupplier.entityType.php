<?php
return [
  'name' => 'InventorySupplier',
  'table' => 'civicrm_inventory_supplier',
  'class' => 'CRM_Inventory_DAO_InventorySupplier',
  'getInfo' => fn() => [
    'title' => ts('Inventory Supplier'),
    'title_plural' => ts('Inventory Suppliers'),
    'description' => ts('FIXME'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Inventory Supplier ID'),
      'unique_name' => 'inventory_supplier_id',
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'supplier_name' => [
      'title' => ts('Supplier Name'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'required' => TRUE,
      'add' => '5.63',
      'unique_name' => 'inventory_supplier_name',
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
      'title' => ts('Supplier Location'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('Supplier Location'),
      'add' => '5.63',
      'unique_name' => 'inventory_supplier_address_id',
      'usage' => ['export'],
      'input_attrs' => [
        'label' => ts('Supplier Location'),
      ],
      'entity_reference' => [
        'entity' => 'Address',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'contact_id' => [
      'title' => ts('Supplier Name'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'RESTRICT',
      ],
    ],
  ],
];
