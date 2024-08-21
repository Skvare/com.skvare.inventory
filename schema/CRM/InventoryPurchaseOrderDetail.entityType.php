<?php
return [
  'name' => 'InventoryPurchaseOrderDetail',
  'table' => 'civicrm_inventory_purchase_order_detail',
  'class' => 'CRM_Inventory_DAO_InventoryPurchaseOrderDetail',
  'getInfo' => fn() => [
    'title' => ts('Inventory Purchase Order Detail'),
    'title_plural' => ts('Inventory Purchase Order Details'),
    'description' => ts('FIXME'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique InventoryPurchaseOrderDetail ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'order_id' => [
      'title' => ts('Order ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Order'),
      'entity_reference' => [
        'entity' => 'InventoryPurchaseOrder',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'product_variant_id' => [
      'title' => ts('Product Variant ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Product Variant'),
      'entity_reference' => [
        'entity' => 'InventoryProductVariant',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'supplier_id' => [
      'title' => ts('Supplier ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Supplier'),
      'entity_reference' => [
        'entity' => 'InventorySupplier',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'warehouse_id' => [
      'title' => ts('Warehouse ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Warehouse'),
      'entity_reference' => [
        'entity' => 'InventoryWarehouse',
        'key' => 'id',
        'on_delete' => 'RESTRICT',
      ],
    ],
    'order_quantity' => [
      'title' => ts('Order Quantity'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'description' => ts('Order quantiy to supplier'),
      'unique_name' => 'inventory_orderdetail_order_quantity',
    ],
    'expected_date' => [
      'title' => ts('Expected Date'),
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
    'actual_date' => [
      'title' => ts('Actual Date'),
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
  ],
];
