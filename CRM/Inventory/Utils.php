<?php

// phpcs:disable
use CRM_Inventory_ExtensionUtil as E;
// phpcs:enable

class CRM_Inventory_Utils {

  /**
   * Membership Type available to limited countries.
   *
   * @return string[]
   *   Country List.
   */
  public static function membershipTypeShippableTo():array {
    return ['USA' => 'United States', 'PRI' => 'Puerto Rico', 'CAN' => 'Canada'];
  }

  /**
   * Product Variant Status.
   *
   * @return string[]
   *   Status List.
   */
  public static function productVariantStatus() {
    return [
      '' => E::ts('Unknown Placement'),
      'new_inventory' => E::ts('New Inventory'),
      'assinged_to_member' => E::ts('Assigned to member'),
      'lost' => E::ts('Lost'),
      'broken' => E::ts('Broken'),
      'returned' => E::ts('Returned'),
      'loaner' => E::ts('Loaner'),
    ];
  }

  /**
   * Product Inventory Status.
   *
   * @return array
   *   Inventory Status for model list.
   */
  public static function productInventoryStatus() {
    /*
    full: device is fully stocked
    low: limited inventory is reserved for replacement orders only.
    delayed: members see that shipment may be delayed.
    out: no inventory, and members are unable to order.
     */
    return [
      'full' => E::ts('Full'),
      'low' => E::ts('Low'),
      'delayed' => E::ts('Delayed'),
      'out' => E::ts('Out'),
    ];
  }

  /**
   * Product Variant, change log status.
   *
   * @return string[]
   *   Product Change log status list.
   */
  public static function productChangeLogStatus() {
    return [
      'UPDATE' => E::ts('UPDATE'),
      'REACTIVATE' => E::ts('REACTIVATE'),
      'TERMINATE' => E::ts('TERMINATE'),
      'SUSPEND' => E::ts('SUSPEND'),
      'LOST' => E::ts('LOST'),
    ];
  }

  /**
   * Product Sales Status.
   *
   * @return string[]
   *   Product Sales status list.
   */
  public static function productSalesStatus() {
    return [
      'placed' => E::ts('Placed'),
      'shipped' => E::ts('Shipped'),
      'completed' => E::ts('Completed'),
      'cancelled' => E::ts('Cancelled'),
      'hold' => E::ts('Hold'),
    ];
  }

  /**
   * Get Membership Type settings.
   *
   * @param int $typeID
   *   Membership Type ID.
   *
   * @return array
   *   Additional field values.
   */
  public static function getMembershipTypeSettings($typeID) {
    $result = [];
    try {
      $result = civicrm_api3('MembershipType', 'getsingle', [
        'sequential' => 1,
        'return' => ["may_renew", "shippable_to"],
        'id' => $typeID,
      ]);
      unset($result['id']);
    }
    catch (CiviCRM_API3_Exception $e) {
      // Handle the API exception here.
    }
    return $result;
  }

}
