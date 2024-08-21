<?php
return [
  'name' => 'InventoryReferrals',
  'table' => 'civicrm_inventory_referrals',
  'class' => 'CRM_Inventory_DAO_InventoryReferrals',
  'getInfo' => fn() => [
    'title' => ts('Inventory Referrals'),
    'title_plural' => ts('Inventory Referralses'),
    'description' => ts('FIXME'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique InventoryReferrals ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'creator_id' => [
      'title' => ts('Creator ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Contact'),
      'unique_name' => 'inventory_referral_created_id',
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'consumer_id' => [
      'title' => ts('Consumer ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Contact'),
      'unique_name' => 'inventory_referral_consumer_id',
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'created_date' => [
      'title' => ts('Created Date'),
      'sql_type' => 'datetime',
      'input_type' => 'Select Date',
      'unique_name' => 'inventory_referral_created_date',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'format_type' => 'activityDateTime',
      ],
    ],
    'before_end_date' => [
      'title' => ts('Before End Date'),
      'sql_type' => 'datetime',
      'input_type' => 'Select Date',
      'unique_name' => 'inventory_referral_before_end_date',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'format_type' => 'activityDateTime',
      ],
    ],
    'after_end_date' => [
      'title' => ts('After End Date'),
      'sql_type' => 'datetime',
      'input_type' => 'Select Date',
      'unique_name' => 'inventory_referral_after_end_date',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'format_type' => 'activityDateTime',
      ],
    ],
    'referral_code' => [
      'title' => ts('Referral Code'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_referral_code',
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
