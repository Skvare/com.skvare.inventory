<?php
return [
  'name' => 'InventorySalesDetail',
  'table' => 'civicrm_inventory_sales_detail',
  'class' => 'CRM_Inventory_DAO_InventorySalesDetail',
  'getInfo' => fn() => [
    'title' => ts('Inventory Sales Detail'),
    'title_plural' => ts('Inventory Sales Details'),
    'description' => ts('Sale order details'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique Inventory Sales Detail ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
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
    'product_quantity' => [
      'title' => ts('Product quantity'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'description' => ts('The quantity sold.'),
      'unique_name' => 'inventory_salesdetail_quantity_available',
    ],
    'warehouse_id' => [
      'title' => ts('Warehouse ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Warehouse'),
      'entity_reference' => [
        'entity' => 'InventoryWarehouse',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'purchase_price' => [
      'title' => ts('Purchase Price'),
      'sql_type' => 'decimal(20,2)',
      'input_type' => 'Text',
      'required' => TRUE,
      'description' => ts('Product Purchase price'),
      'unique_name' => 'inventory_salesdetail_purchase_price',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'label' => ts('Purchase Price'),
      ],
    ],
    'product_title' => [
      'title' => ts('Product Title'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_salesdetail_product_title',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'product_sub_title' => [
      'title' => ts('Product Sub-Title'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_salesdetail_product_sub_title',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'additional_details' => [
      'title' => ts('Product Additional Details'),
      'sql_type' => 'text',
      'input_type' => 'TextArea',
      'description' => ts('Additional product details'),
      'add' => '5.63',
      'unique_name' => 'inventory_sales_detail_additional',
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
    'membership_id' => [
      'title' => ts('Membership ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('Membership Associated with product.'),
      'add' => '5.63',
      'unique_name' => 'inventory_sales_membership_id',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'label' => ts('Membership'),
      ],
      'entity_reference' => [
        'entity' => 'Membership',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'contribution_id' => [
      'title' => ts('Contribution ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to contribution table.'),
      'input_attrs' => [
        'label' => ts('Contribution'),
      ],
      'entity_reference' => [
        'entity' => 'Contribution',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'type' => [
      'title' => ts('Product Type'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_salesdetail_type',
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
