<?php
return [
  'name' => 'InventoryProductMeta',
  'table' => 'civicrm_inventory_product_meta',
  'class' => 'CRM_Inventory_DAO_InventoryProductMeta',
  'getInfo' => fn() => [
    'title' => ts('Inventory Product Meta'),
    'title_plural' => ts('Inventory Product Metas'),
    'description' => ts('Product Meta details.'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique InventoryProductMeta ID'),
      'unique_name' => 'inventory_product_meta_id',
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'product_id' => [
      'title' => ts('Product ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Product'),
      'entity_reference' => [
        'entity' => 'InventoryProduct',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'product_meta_key' => [
      'title' => ts('Product Key'),
      'sql_type' => 'varchar(50)',
      'input_type' => 'Text',
      'required' => TRUE,
      'add' => '5.63',
      'unique_name' => 'inventory_product_meta_key',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 50,
      ],
    ],
    'product_meta_content' => [
      'title' => ts('Product Content'),
      'sql_type' => 'text',
      'input_type' => 'TextArea',
      'description' => ts('Product Meta Content.'),
      'add' => '5.63',
      'unique_name' => 'inventory_product_meta_content',
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
  ],
];
