<?php
return [
  'name' => 'InventoryProductChangelog',
  'table' => 'civicrm_inventory_product_changelog',
  'class' => 'CRM_Inventory_DAO_InventoryProductChangelog',
  'getInfo' => fn() => [
    'title' => ts('Inventory Product Changelog'),
    'title_plural' => ts('Inventory Product Changelogs'),
    'description' => ts('FIXME'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique InventoryProductChangelog ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'contact_id' => [
      'title' => ts('Modified By'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
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
    'batch_id' => [
      'title' => ts('Batch ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'description' => ts('FK to Contact'),
      'unique_name' => 'inventory_product_changelog_batch_id',
    ],
    'created_date' => [
      'title' => ts('Created Date'),
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
      'description' => ts('UPDATE,REACTIVATE,TERMINATE,SUSPEND'),
      'add' => '5.63',
      'unique_name' => 'inventory_product_changelog_status_id',
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
