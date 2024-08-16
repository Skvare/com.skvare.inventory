<?php

class CRM_Inventory_Permission extends CRM_Core_Permission {

  /**
   * Returns an array of permissions defined by this extension. Modeled off of
   * CRM_Core_Permission::getCorePermissions().
   *
   * @return array Keyed by machine names with human-readable labels for values
   */
  public static function getInventoryPermissions() {

    $prefix = ts('Inventory', ['domain' => 'com.skvare.inventory']) . ': ';

    return [
      // Acces Inventory Component.
      'access Inventory' => [
        'label' => $prefix . ts('access Inventory', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Access Inventory Component', ['domain' => 'com.skvare.inventory']),
      ],
      'access My Contact Product' => [
        'label' => $prefix . ts('access My Contact Product', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Access my purchased product', ['domain' => 'com.skvare.inventory']),
      ],
      'access All Contact Product' => [
        'label' => $prefix . ts('access All Contact Product', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Access all purchased product', ['domain' => 'com.skvare.inventory']),
      ],
      // Inventory Admin.
      'administer Inventory' => [
        'label' => $prefix . ts('administer Inventory', ['domain' => 'com.skvare.inventory']),
        'description' => ts('administer Inventory Component', ['domain' => 'com.skvare.inventory']),
      ],
      // Warehouse Entity type.
      'create warehouse' => [
        'label' => $prefix . ts('create warehouse', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Create a new warehouse record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'edit warehouse' => [
        'label' => $prefix . ts('edit warehouse', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Edit a warehouse record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'access warehouse' => [
        'label' => $prefix . ts('access warehouse', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Access a warehouse record from Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'delete warehouse' => [
        'label' => $prefix . ts('delete warehouse', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Delete a warehouse record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],

      // Inventory Product and variaant.
      'create inventory product' => [
        'label' => $prefix . ts('create inventory product', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Create a inventory product record.', ['domain' => 'com.skvare.inventory']),
      ],
      'edit inventory product' => [
        'label' => $prefix . ts('edit inventory product', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Edit a inventory product record.', ['domain' => 'com.skvare.inventory']),
      ],
      'access inventory product' => [
        'label' => $prefix . ts('access inventory product', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Get a inventory product record.', ['domain' => 'com.skvare.inventory']),
      ],
      'delete inventory product' => [
        'label' => $prefix . ts('delete inventory product', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Delete a inventory product record.', ['domain' => 'com.skvare.inventory']),
      ],

      // Inventory sales.
      'create inventory sales' => [
        'label' => $prefix . ts('create inventory sales', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Delete a inventory sales record.', ['domain' => 'com.skvare.inventory']),
      ],
      'edit inventory sales' => [
        'label' => $prefix . ts('edit inventory sales', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Delete a inventory sales record.', ['domain' => 'com.skvare.inventory']),
      ],
      'access inventory sales' => [
        'label' => $prefix . ts('access inventory sales', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Access a inventory sales record.', ['domain' => 'com.skvare.inventory']),
      ],
      'delete inventory sales' => [
        'label' => $prefix . ts('delete inventory sales', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Delete a inventory sales record.', ['domain' => 'com.skvare.inventory']),
      ],

      // Shipment and label
      'create shipment' => [
        'label' => $prefix . ts('create shipment', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Create a shipment record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'edit shipment' => [
        'label' => $prefix . ts('edit shipment', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Edit a shipment record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'access shipment' => [
        'label' => $prefix . ts('access shipment', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Edit a shipment record in Inventory', ['domain' => 'com.skvare.inventory']),
      ],
      'delete shipment' => [
        'label' => $prefix . ts('delete shipment', ['domain' => 'com.skvare.inventory']),
        'description' => ts('Delete a shipment record in Inventory', ['domain' => 'com.skvare.inventory']),
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
