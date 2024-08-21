<?php
return [
  'name' => 'InventoryPurchaseOrder',
  'table' => 'civicrm_inventory_purchase_order',
  'class' => 'CRM_Inventory_DAO_InventoryPurchaseOrder',
  'getInfo' => fn() => [
    'title' => ts('Inventory Purchase Order'),
    'title_plural' => ts('Inventory Purchase Orders'),
    'description' => ts('FIXME'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique InventoryPurchaseOrder ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'order_date' => [
      'title' => ts('Order Date'),
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
    'contact_id' => [
      'title' => ts('Order Created By'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'supplier_id' => [
      'title' => ts('Supplier Contact'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Supplier'),
      'entity_reference' => [
        'entity' => 'InventorySupplier',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
  ],
];
