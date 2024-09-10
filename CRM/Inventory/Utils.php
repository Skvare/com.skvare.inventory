<?php

// phpcs:disable
use Civi\Api4\InventoryBillingPlans;
use CRM_Inventory_ExtensionUtil as E;
// phpcs:enable


/**
 * CRM_Inventory_Utils.
 *
 * Utility functions.
 */
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
  public static function productVariantStatus(): array {
    return [
      '' => E::ts('Unknown Placement'),
      'new_inventory' => E::ts('New Inventory'),
      'assigned_to_member' => E::ts('Assigned to member'),
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
  public static function productInventoryStatus(): array {
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
  public static function productChangeLogStatus(): array {
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
  public static function productSalesStatus(): array {
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
  public static function getMembershipTypeSettings(int $typeID): array {
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

  /**
   *
   */
  public static function getBillingPlanForMemberhshipType($typeID) {
    $inventoryBillingPlanses = InventoryBillingPlans::get(TRUE)
      ->addWhere('membership_type_id', '=', $typeID)
      ->setLimit(25)
      ->execute();
    $billingPlans = [];
    foreach ($inventoryBillingPlanses as $inventoryBillingPlans) {
      $billingPlans[] = $inventoryBillingPlans;
    }
    return $billingPlans;
  }

  /**
   * Get Setting values.
   *
   * @return array|mixed
   *   Setting data.
   *
   * @throws CRM_Core_Exception
   */
  public static function getInventorySettingInfo(): mixed {
    $cacheKey = "CRM_Inventory_custom_field";
    $cache = CRM_Utils_Cache::singleton();
    $params = $cache->get($cacheKey);
    if (!isset($params)) {
      $setting = ['inventory_referral_code', 'inventory_referral_consumed_code'];
      $domainID = CRM_Core_Config::domainID();
      $settings = Civi::settings($domainID);
      $params = [];
      foreach ($setting as $key) {
        $customField = $settings->get($key);
        if (empty($customField)) {
          continue;
        }
        $customFieldInfo = CRM_Core_BAO_CustomField::getKeyID($customField);
        [$table_name, $column_name, $custom_group_id, $cgName, $cf_name] = self::getTableColumnGroup($customFieldInfo);
        /*
        Example for output format:
        This is meta-data about custom field.

        // Custom Table name.
        [table] => civicrm_value_referral_code_8
        // Custom Group name
        [inventory_referral_code_cg_name] => Referral_Code

        // Custom Field name.
        [inventory_referral_code_cf_name] => Referral_Code
        // Custom Field name with CustomGroup + Custom field name (separated by dot)
        [inventory_referral_code_cf_name_full] => Referral_Code.Referral_Code
        // Custom field column name in custom group table.
        [inventory_referral_code] => referral_code_27
        // widely used custom field name in form.
        [inventory_referral_code_key_name] => custom_27
         */
        $params['table'] = $table_name;
        $params[$key . '_cg_name'] = $cgName;
        $params[$key . '_cf_name'] = $cf_name;
        $params[$key . '_cf_name_full'] = "{$cgName}.{$cf_name}";
        $params[$key] = $column_name;
        $params[$key . '_key_name'] = 'custom_' . $customFieldInfo;
        $params['custom_' . $customFieldInfo] = $column_name;
        $cache->set($cacheKey, $params);
      }
    }

    return $params;
  }

  /**
   * Get CiviCRM field list.
   *
   * @return array
   *   Array of field.
   */
  public static function getCiviCRMFields():array {
    $civicrmFields = CRM_Contact_Form_Search_Builder::fields();
    $cleanFields = [];
    foreach ($civicrmFields as $fieldName => $fieldDetail) {
      $cleanFields[$fieldName] = $fieldDetail['title'];
    }

    return $cleanFields;
  }

  /**
   * Get the database table name and column name for a custom field.
   *
   * @param int $fieldID
   *   The fieldID of the custom field.
   *
   * @return array
   *   fatal is fieldID does not exist, else array of tableName, columnName
   *
   * @throws \CRM_Core_Exception
   */
  public static function getTableColumnGroup($fieldID): array {
    global $tsLocale;
    // Check if we can get the field values from the system cache.
    $cacheKey = "Inventory_CustomGroup_TableColumn_{$fieldID}_$tsLocale";
    if (Civi::cache('metadata')->has($cacheKey)) {
      return Civi::cache('metadata')->get($cacheKey);
    }

    $query = "
        SELECT cg.table_name, cg.name as 'cg_name', cf.column_name, cg.id, cf.name as 'cf_name'
        FROM   civicrm_custom_group cg,
            civicrm_custom_field cf
        WHERE  cf.custom_group_id = cg.id
            AND    cf.id = %1";
    $params = [1 => [$fieldID, 'Integer']];
    $dao = CRM_Core_DAO::executeQuery($query, $params);

    if (!$dao->fetch()) {
      throw new CRM_Core_Exception('Cannot find table and column information for Custom Field ' . $fieldID);
    }
    $fieldValues = [$dao->table_name, $dao->column_name, $dao->id, $dao->cg_name, $dao->cf_name];
    Civi::cache('metadata')->set($cacheKey, $fieldValues);

    return $fieldValues;
  }

  /**
   * Function to deactivate contact.
   *
   * @param int $contactID
   *   Contact ID.
   *
   * @return void
   *   Nothing.
   */
  public function deactivateContact(int $contactID):void {
    $values = [];
    $activeMembership = CRM_Member_BAO_Membership::getValues(['contact_id' => $contactID], $values, TRUE);
    if (empty($activeMembership)) {
      // Set is_active = false for Contact/Member record.
      // $this->update(['is_active' => false]);
      // is_active should be custom field.
    }
  }

}
