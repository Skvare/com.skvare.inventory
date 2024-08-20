<?php

class CRM_Inventory_Utils {

  public static function membershipTypeShipableTo() {
    return ['USA' => 'United States', 'PRI' => 'Puerto Rico', 'CAN' => 'Canada'];
  }

  /**
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
