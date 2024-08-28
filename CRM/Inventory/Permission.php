<?php

// phpcs:disable
use CRM_Inventory_ExtensionUtil as E;
// phpcs:enable

class CRM_Inventory_Permission extends CRM_Core_Permission {

  /**
   * Returns an array of permissions defined by this extension. Modeled off of
   * CRM_Core_Permission::getCorePermissions().
   *
   * @return array Keyed by machine names with human-readable labels for values
   */
  public static function getInventoryPermissions() {

    $prefix = E::ts('Inventory', ['domain' => 'com.skvare.inventory']) . ': ';

    return [
      // Access Inventory Component.
      'access Inventory' => [
        'label' => $prefix . E::ts('access Inventory', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Access Inventory Component', ['domain' => 'com.skvare.inventory']),
      ],
      'access My Contact Product' => [
        'label' => $prefix . E::ts('access My Contact Product', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Access my purchased product', ['domain' => 'com.skvare.inventory']),
      ],
      'access All Contact Product' => [
        'label' => $prefix . E::ts('access All Contact Product', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Access all purchased product', ['domain' => 'com.skvare.inventory']),
      ],
      // Inventory Admin.
      'administer Inventory' => [
        'label' => $prefix . E::ts('administer Inventory', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('administer Inventory Component', ['domain' => 'com.skvare.inventory']),
      ],
      // Warehouse Entity type.
      'create warehouse' => [
        'label' => $prefix . E::ts('create warehouse', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Create a new warehouse record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'edit warehouse' => [
        'label' => $prefix . E::ts('edit warehouse', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Edit a warehouse record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'access warehouse' => [
        'label' => $prefix . E::ts('access warehouse', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Access a warehouse record from Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'delete warehouse' => [
        'label' => $prefix . E::ts('delete warehouse', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Delete a warehouse record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],

      // Inventory Product and variaant.
      'create inventory product' => [
        'label' => $prefix . E::ts('create inventory product', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Create an inventory product record.', ['domain' => 'com.skvare.inventory']),
      ],
      'edit inventory product' => [
        'label' => $prefix . E::ts('edit inventory product', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Edit an inventory product record.', ['domain' => 'com.skvare.inventory']),
      ],
      'access inventory product' => [
        'label' => $prefix . E::ts('access inventory product', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Access an inventory product record.', ['domain' => 'com.skvare.inventory']),
      ],
      'delete inventory product' => [
        'label' => $prefix . E::ts('delete inventory product', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Delete an inventory product record.', ['domain' => 'com.skvare.inventory']),
      ],

      // Inventory sales.
      'create inventory sales' => [
        'label' => $prefix . E::ts('create inventory sales', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Create an inventory sales record.', ['domain' => 'com.skvare.inventory']),
      ],
      'edit inventory sales' => [
        'label' => $prefix . E::ts('edit inventory sales', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Edit an inventory sales record.', ['domain' => 'com.skvare.inventory']),
      ],
      'access inventory sales' => [
        'label' => $prefix . E::ts('access inventory sales', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Access an inventory sales record.', ['domain' => 'com.skvare.inventory']),
      ],
      'delete inventory sales' => [
        'label' => $prefix . E::ts('delete inventory sales', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Delete an inventory sales record.', ['domain' => 'com.skvare.inventory']),
      ],

      // Shipment and label
      'create shipment' => [
        'label' => $prefix . E::ts('create shipment', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Create a shipment record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'edit shipment' => [
        'label' => $prefix . E::ts('edit shipment', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Edit a shipment record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'access shipment' => [
        'label' => $prefix . E::ts('access shipment', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Access a shipment record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'delete shipment' => [
        'label' => $prefix . E::ts('delete shipment', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Delete a shipment record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],

      // Inventory batch.
      'create device batch' => [
        'label' => $prefix . E::ts('create device batch', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Create an inventory device batch record.', ['domain' => 'com.skvare.inventory']),
      ],
      'edit device batch' => [
        'label' => $prefix . E::ts('edit device batch', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Edit an inventory device batch record.', ['domain' => 'com.skvare.inventory']),
      ],
      'access device batch' => [
        'label' => $prefix . E::ts('access device batch', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Access an inventory device batch record.', ['domain' => 'com.skvare.inventory']),
      ],
      'delete device batch' => [
        'label' => $prefix . E::ts('delete device batch', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Delete an inventory device batch record.', ['domain' => 'com.skvare.inventory']),
      ],

      // Inventory replacement.
      'create device replacement' => [
        'label' => $prefix . E::ts('create device replacement', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Create an inventory device replacement record.', ['domain' => 'com.skvare.inventory']),
      ],
      'edit device replacement' => [
        'label' => $prefix . E::ts('edit device replacement', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Edit an inventory device replacement record.', ['domain' => 'com.skvare.inventory']),
      ],
      'access device replacement' => [
        'label' => $prefix . E::ts('access device replacement', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Access an inventory device replacement record.', ['domain' => 'com.skvare.inventory']),
      ],
      'delete device replacement' => [
        'label' => $prefix . E::ts('delete device replacement', ['domain' => 'com.skvare.inventory']),
        'description' => E::ts('Delete an inventory device replacement record.', ['domain' => 'com.skvare.inventory']),
      ],
    ];
  }

  /**
   * Given a permission string or array, check for access requirements.
   *
   * @param mixed $permissions
   *   The permission(s) to check as an array or string. See parent class for examples.
   * @return boolean
   */
  public static function check($permissions, $contactId = NULL) {
    $permissions = (array)$permissions;

    $permClass = CRM_Core_Config::singleton()->userPermissionClass;
    $skipCheck = !$permClass->isModulePermissionSupported() && !is_a($permClass, 'CRM_Core_Permission_UnitTests');

    array_walk_recursive($permissions, function (&$v, $k) use ($skipCheck) {
      if ($skipCheck) {
        if (array_key_exists($v, CRM_Inventory_Permission::getInventoryPermissions())) {
          $v = CRM_Core_Permission::ALWAYS_ALLOW_PERMISSION;
        }
      }

      // Ensure that checks for "edit own" pass if user has "edit all."
      if ($v === 'administer Inventory' && self::check('administer Inventory')) {
        $v = CRM_Core_Permission::ALWAYS_ALLOW_PERMISSION;
      }
    });

    return parent::check($permissions, $contactId);
  }
}
