<?php
// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
return [
  [
    'name' => 'Inventory',
    'class' => 'CRM_Inventory_DAO_Inventory',
    'table' => 'civicrm_inventory',
  ],
];
