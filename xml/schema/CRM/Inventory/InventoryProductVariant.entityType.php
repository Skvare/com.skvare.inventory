<?php
// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
return [
  [
    'name' => 'InventoryProductVariant',
    'class' => 'CRM_Inventory_DAO_InventoryProductVariant',
    'table' => 'civicrm_inventory_warehouse',
  ],
];
