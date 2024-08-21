<?php
return [
  'name' => 'InventoryCategory',
  'table' => 'civicrm_inventory_category',
  'class' => 'CRM_Inventory_DAO_InventoryCategory',
  'getInfo' => fn() => [
    'title' => ts('Inventory Category'),
    'title_plural' => ts('Inventory Categories'),
    'description' => ts('FIXME'),
    'log' => TRUE,
    'label_field' => 'title',
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique InventoryCategory ID'),
      'unique_name' => 'inventory_category_id',
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'parent_id' => [
      'title' => ts('Parent ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Parent Category'),
      'unique_name' => 'inventory_category_parent_id',
      'entity_reference' => [
        'entity' => 'InventoryCategory',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'title' => [
      'title' => ts('Title'),
      'sql_type' => 'varchar(256)',
      'input_type' => 'Text',
      'required' => TRUE,
      'add' => '5.63',
      'unique_name' => 'inventory_category_title',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 256,
      ],
    ],
    'meta_title' => [
      'title' => ts('Meta Title'),
      'sql_type' => 'varchar(256)',
      'input_type' => 'Text',
      'required' => TRUE,
      'add' => '5.63',
      'unique_name' => 'inventory_category_meta_title',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 256,
      ],
    ],
    'slug' => [
      'title' => ts('Slug'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'required' => TRUE,
      'add' => '5.63',
      'unique_name' => 'inventory_category_slug',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'content' => [
      'title' => ts('content'),
      'sql_type' => 'text',
      'input_type' => 'TextArea',
      'description' => ts('Category Content.'),
      'add' => '5.63',
      'unique_name' => 'inventory_category_content',
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
