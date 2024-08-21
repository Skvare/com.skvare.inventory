<?php

// phpcs:disable
use CRM_Inventory_ExtensionUtil as E;
// phpcs:enable

class CRM_Inventory_Utils {

  /**
   * Mmbership Type available to limited countries.
   *
   * @return string[]
   */
  public static function membershipTypeShipableTo() {
    return ['USA' => 'United States', 'PRI' => 'Puerto Rico', 'CAN' => 'Canada'];
  }

  /**
   * Product Variant Status.
   *
   * @return string[]
   */
  public static function productVariantStatus() {
    return [
      'new_inventory' => E::ts('New Inventory'),
      'assinged_to_member' => E::ts('Assigned to member'),
      'lost' => E::ts('Lost'),
      'broken' => E::ts('Broken'),
      'returned' => E::ts('Returned'),
      'loander' => E::ts('Loander'),
    ];
  }

  /**
   * Product Variant, change log status.
   *
   * @return string[]
   */
  public static function productChangeLogStatus() {
    return [
      'UPDATE' => E::ts('UPDATE'),
      'REACTIVATE' => E::ts('REACTIVATE'),
      'TERMINATE' => E::ts('TERMINATE'),
      'SUSPEND' => E::ts('SUSPEND'),
    ];
  }

  /**
   * Product Sales Status.
   *
   * @return string[]
   */
  public static function productSalesStatus() {
    return [
      'placed' => E::ts('Placed'),
      'placed' => E::ts('Shipped'),
      'completed' => E::ts('Completed'),
      'cancelled' => E::ts('Cancelled'),
      'hold' => E::ts('Hold'),
    ];
  }

  /**
   * Get Membership Type settings.
   *
   * @param $typeID
   * @return array
   */
  public static function getMembershipTypeSettings($typeID) {
    $result = civicrm_api3('MembershipType', 'getsingle', [
      'sequential' => 1,
      'return' => ["signup_fee", "renewal_fee", "shippable_to"],
      'id' => $typeID,
    ]);
    unset($result['id']);
    return $result;
  }
}
