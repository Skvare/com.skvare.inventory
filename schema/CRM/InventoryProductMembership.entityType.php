<?php
return [
  'name' => 'InventoryProductMembership',
  'table' => 'civicrm_inventory_product_membership',
  'class' => 'CRM_Inventory_DAO_InventoryProductMembership',
  'getInfo' => fn() => [
    'title' => ts('Inventory Product Membership'),
    'title_plural' => ts('Inventory Product Memberships'),
    'description' => ts('FIXME'),
    'log' => TRUE,
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => ts('Unique Inventory Product Membership ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'product_variant_sku_code' => [
      'title' => ts('Product Variant SKU Code'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_product_variant_sku_code',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'product_name' => [
      'title' => ts('Product Name'),
      'sql_type' => 'varchar(100)',
      'input_type' => 'Text',
      'add' => '5.63',
      'unique_name' => 'inventory_product_name',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'maxlength' => 100,
      ],
    ],
    'membership_type_id' => [
      'title' => ts('Membership ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'required' => TRUE,
      'description' => ts('Membership Type Associated with product.'),
      'add' => '5.63',
      'unique_name' => 'inventory_membership_type_id',
      'usage' => [
        'import',
        'export',
        'duplicate_matching',
      ],
      'input_attrs' => [
        'label' => ts('Membership Type'),
      ],
      'entity_reference' => [
        'entity' => 'MembershipType',
        'key' => 'id',
        'on_delete' => 'RESTRICT',
      ],
    ],
    'contact_id' => [
      'title' => ts('Contact ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
  ],
];
