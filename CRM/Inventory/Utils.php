<?php

// phpcs:disable
use Civi\Api4\InventorySales;
use Civi\Api4\InventoryProductVariant;
use Civi\Api4\InventoryProduct;
use Civi\Api4\LineItem;
use Civi\API\Exception\UnauthorizedException;
use Civi\Api4\InventoryBillingPlans;
use Civi\Api4\Address;
use CRM_Inventory_ExtensionUtil as E;
// phpcs:enable


/**
 * CRM_Inventory_Utils.
 *
 * Utility functions.
 */
class CRM_Inventory_Utils {

  const US_STATES = [
    'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY',
  ];
  const US_TERRITORIES = [
    'AA', 'AE', 'AE', 'AE', 'AE', 'AP', 'AS', 'FM', 'GU', 'MH', 'MP', 'PR', 'PW', 'VI',
  ];

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
      'reorder' => E::ts('Reorder'),
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
      $setting = ['inventory_referral_code', 'inventory_referral_consumed_code', 'inventory_membership_renewal_date'];
      $domainID = CRM_Core_Config::domainID();
      $settings = Civi::settings($domainID);
      $params = [];
      foreach ($setting as $key) {
        $customField = $settings->get($key);
        if (empty($customField)) {
          continue;
        }
        $customFieldInfo = CRM_Core_BAO_CustomField::getKeyID($customField);
        if (empty($customFieldInfo)) {
          continue;
        }
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
        $params[$key . '_table'] = $table_name;
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

  /**
   * Perform action on device through url request.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   */
  public static function action(): void {
    $productId = CRM_Utils_Request::retrieve('product_id', 'Integer', NULL, TRUE);
    $contactID = CRM_Utils_Request::retrieve('cid', 'Integer', NULL, TRUE);
    $status = CRM_Utils_Request::retrieve('status', 'String', NULL, TRUE);
    $variantObject = new CRM_Inventory_BAO_InventoryProductVariant();
    try {
      if ($status == 'active') {

        $variantObject->changeStatus($productId, 'REACTIVATE');
      }
      elseif ($status == 'terminate') {
        $variantObject->changeStatus($productId, 'TERMINATE');
      }
    }
    catch (UnauthorizedException | CRM_Core_Exception $e) {
      // Do nothing.
    }
    $viewContact = CRM_Utils_System::url('civicrm/contact/view',
      "action=view&reset=1&cid={$contactID}&selectedChild=member"
    );
    CRM_Core_Error::statusBounce(ts('Product details updated.'), $viewContact,
      'Updated');
  }

  /**
   * Perform action on device through url request.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   */
  public static function shipmentAction(): void {
    $entity = CRM_Utils_Request::retrieve('entity', 'String', NULL, TRUE);
    if ($entity == 'shipment') {
      $params = [];
      $params['id'] = CRM_Utils_Request::retrieve('id', 'Integer', NULL, TRUE);
      $is_shipped = CRM_Utils_Request::retrieve('is_shipped', 'String', NULL);
      if (isset($is_shipped)) {
        $params['is_shipped'] = $is_shipped;
        if (!empty($params['is_shipped'])) {
          $params['shipped_date'] = date('Y-m-d');
        }
      }
      $is_finished = CRM_Utils_Request::retrieve('is_finished', 'String', NULL);
      if (isset($is_finished)) {
        $params['is_finished'] = $is_finished;
      }
      civicrm_api3('InventoryShipment', 'create', $params);
      $viewPage = CRM_Utils_System::url('civicrm/inventory/shipment-details',
        "reset=1&id={$params['id']}"
      );
    }
    if ($entity == 'shipment_label') {
      $params = [];
      $params['id'] = CRM_Utils_Request::retrieve('id', 'Integer', NULL, TRUE);
      $is_shipped = CRM_Utils_Request::retrieve('is_shipped', 'String', NULL);
      if (isset($is_shipped)) {
        $params['is_shipped'] = $is_shipped;
        if (!empty($params['is_shipped'])) {
          $params['shipped_date'] = date('Y-m-d');
        }
      }
      $is_finished = CRM_Utils_Request::retrieve('is_finished', 'String', NULL);
      if (isset($is_finished)) {
        $params['is_finished'] = $is_finished;
      }
      $result = civicrm_api3('InventoryShipmentLabels', 'create', $params);
    }

    CRM_Core_Error::statusBounce(ts('Product details updated.'), $viewPage,
      'Updated');
  }

  /**
   * Function to get object.
   *
   * @param string $daoName
   *   DAO object.
   * @param array $params
   *   Input params.
   *
   * @return array|object
   *   Array of object.
   */
  public static function commonRetrieveAll(string $daoName, array $params, $single = TRUE): array|object {
    $object = new $daoName();
    $object->copyValues($params);
    if ($single) {
      $object->find(TRUE);
      return $object;
    }
    else {
      $object->find();
    }
    $details = [];
    while ($object->fetch()) {
      $details[$object->id] = $object;
    }
    return $details;
  }

  /**
   * Get Address of contact.
   *
   * @param int $contactID
   *   Contact ID.
   *
   * @return array
   *   Address.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function getAddress(int $contactID): array {
    $addresses = Address::get(TRUE)
      ->addSelect('name', 'street_address', 'supplemental_address_1', 'supplemental_address_2', 'city', 'state_province_id:abbr', 'country_id:abbr', 'postal_code')
      ->addWhere('is_primary', '=', TRUE)
      ->addWhere('contact_id', '=', $contactID)
      ->setLimit(1)
      ->execute();
    $addressCorrect = [];
    foreach ($addresses->first() as $key => $value) {
      if ($key == 'state_province_id:abbr') {
        $addressCorrect['state'] = $value;
      }
      elseif ($key == 'country_id:abbr') {
        $addressCorrect['country'] = $value;
      }
      else {
        $addressCorrect[$key] = $value;
      }
    }
    return $addressCorrect;
  }

  /**
   *
   */
  public static function getContact(int $contactID): array {
    $lineItems = LineItem::get(TRUE)
      ->addWhere('sale_id', 'IS NOT NULL')
      ->setLimit(25)
      ->execute();
  }

  /**
   * Get Shipping label.
   *
   * @return void
   *   Download file.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\Core\Exception\DBQueryException
   */
  public static function renderShippingLabelFromCivi(): void {
    $photo = CRM_Utils_Request::retrieve('photo', 'String', CRM_Core_DAO::$_nullObject);
    if (!preg_match('/^[^\/]+\.(jpg|jpeg|png|gif)$/i', $photo)) {
      throw new CRM_Core_Exception(ts('Malformed shipping label'));
    }

    $sql = "SELECT id FROM civicrm_inventory_shipment_labels WHERE label_url like %1;";
    $params = [
      1 => ["%" . $photo, 'String'],
    ];
    $dao = CRM_Core_DAO::executeQuery($sql, $params);
    $id = NULL;
    while ($dao->fetch()) {
      $id = $dao->id;
    }
    if ($id) {
      $config = CRM_Core_Config::singleton();
      $fileExtension = pathinfo($photo, PATHINFO_EXTENSION);
      $path = $config->customFileUploadDir . $photo;

      if (!file_exists($path)) {
        header("HTTP/1.0 404 Not Found");
        return;
      }
      elseif (!is_readable($path)) {
        header('HTTP/1.0 403 Forbidden');
        return;
      }
      $ttl = 43200;
      self::download(
        $path,
        'image/' . (strtolower($fileExtension) == 'jpg' ? 'jpeg' : $fileExtension),
        $ttl
      );
    }
    else {
      header("HTTP/1.0 404 Not Found");
    }
    CRM_Utils_System::civiExit();
  }

  /**
   * Download image.
   *
   * @param string $file
   *   Local file path.
   * @param string $mimeType
   *   Mime Type.
   * @param int $ttl
   *   Time to live (seconds).
   *
   * @return void
   *   Nothing.
   */
  public static function download(string $file, string $mimeType, int $ttl): void {
    if (!file_exists($file)) {
      header("HTTP/1.0 404 Not Found");
      return;
    }
    elseif (!is_readable($file)) {
      header('HTTP/1.0 403 Forbidden');
      return;
    }
    CRM_Utils_System::setHttpHeader('Expires', gmdate('D, d M Y H:i:s \G\M\T', CRM_Utils_Time::getTimeRaw() + $ttl));
    CRM_Utils_System::setHttpHeader("Content-Type", $mimeType);
    CRM_Utils_System::setHttpHeader("Content-Disposition", "inline; filename=\"" . basename($file) . "\"");
    CRM_Utils_System::setHttpHeader("Cache-Control", "max-age=$ttl, public");
    CRM_Utils_System::setHttpHeader('Pragma', 'public');
    readfile($file);
  }

  /**
   * UPS is very picky about what characters it will allow in the CSV.
   */
  public static function csv_str($str) {
    return preg_replace(
        ['/[.\'"`]/', '/ +/', '/\r\n/', '/\n+/'],
        ['', ' ', "\n", "\n"],
        trim($str)
      );
  }

  /**
   * Returns the object name of a certain object.
   * When the object is contact it will try to retrieve the contact type
   * and use this as the object name.
   *
   * @param \CRM_Core_DAO $object
   *
   * @return array|string|null
   */
  public static function getObjectNameFromObject(\CRM_Core_DAO $object) {
    // Array with contact ID and value the contact type.
    static $contact_types = [];
    // Classes renamed in core: https://github.com/civicrm/civicrm-core/pull/29390
    $className = 'CRM_Core_DAO_AllCoreTables::getEntityNameForClass';
    if (!method_exists('CRM_Core_DAO_AllCoreTables', 'getEntityNameForClass')) {
      $className = 'CRM_Core_DAO_AllCoreTables::getBriefName';
    }
    $objectName = $className(get_class($object));
    if ($objectName == 'Contact' && isset($object->contact_type)) {
      $objectName = $object->contact_type;
    }
    elseif ($objectName == 'Contact' && isset($contact_types[$object->id])) {
      $objectName = $contact_types[$object->id];
    }
    elseif ($objectName == 'Contact' && isset($object->id)) {
      try {
        $contact_types[$object->id] = civicrm_api3('Contact', 'getvalue', ['return' => 'contact_type', 'id' => $object->id]);
        $objectName = $contact_types[$object->id];
      }
      catch (\Exception $e) {
        // Do nothing.
      }
    }
    return $objectName;
  }

  /**
   *
   * @throws \Civi\Core\Exception\DBQueryException
   */
  public static function dashboardData(): array {
    $sql = "select product.id, product.label,
       count(variant.product_id) as 'available_product', product.reorder_point,
       product.quantity_available, product.minimum_quantity_stock_level,
       product.maximum_quantity_stock_level, product.inventory_status
      from civicrm_inventory_product product
      inner join civicrm_inventory_product_variant variant on (product.id = variant.product_id)
      where
      variant.is_active = 1 and variant.status = 'new_inventory' and
      product.is_serialize = 1 and product.inventory_display = 1

      group by variant.product_id";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $id = NULL;
    $dashboardArray = [];
    while ($dao->fetch()) {
      $dashboardArray[$dao->id]['id'] = $dao->id;
      $dashboardArray[$dao->id]['available_product'] = $dao->available_product;
      $dashboardArray[$dao->id]['label'] = $dao->label;
      $dashboardArray[$dao->id]['reorder_point'] = $dao->reorder_point;
      $dashboardArray[$dao->id]['quantity_available'] = $dao->quantity_available;
      $dashboardArray[$dao->id]['minimum_quantity_stock_level'] = $dao->minimum_quantity_stock_level;
      $dashboardArray[$dao->id]['maximum_quantity_stock_level'] = $dao->maximum_quantity_stock_level;
      $dashboardArray[$dao->id]['inventory_status'] = $dao->inventory_status;
    }
    return $dashboardArray;
  }

  /**
   * Get Model List.
   *
   * @return array
   *   Model list.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function getProductModels(): array {
    $inventoryProducts = InventoryProduct::get(TRUE)
      ->addSelect('id', 'label', 'minimum_quantity_stock_level', 'maximum_quantity_stock_level', 'reorder_point', 'inventory_status')
      ->addWhere('is_serialize', '=', TRUE)
      ->addWhere('inventory_display', '=', TRUE)
      // Hotspot.
      ->addWhere('product_category_id', '=', 2)
      ->setLimit(150)
      ->execute();
    $models = [];
    foreach ($inventoryProducts as $inventoryProduct) {
      $inventoryProduct['badge'] = CRM_Inventory_BAO_InventoryProduct::deviceModelBadge($inventoryProduct['inventory_status']);
      $models[$inventoryProduct['id']] = $inventoryProduct;
    }
    return $models;
  }

  /**
   * Device Model wise stats.
   *
   * @return array
   *   Stats
   */
  public static function deviceModelStats() {
    $stats = [];
    try {
      foreach (self::getProductModels() as $dm_id => $deviceModel) {
        $totalInventory = InventoryProductVariant::get(TRUE)
          ->selectRowCount()
          ->addWhere('product_id', '=', $dm_id)
          ->execute()->count();

        $totalAvailableInventory = InventoryProductVariant::get(TRUE)
          ->selectRowCount()
          ->addWhere('product_id', '=', $dm_id)
          ->addWhere('is_active', '=', TRUE)
          ->addWhere('status', '=', 'new_inventory')
          ->execute()->count();

        // Estimate days left.
        $total_shipped = InventoryProductVariant::get(TRUE)
          ->selectRowCount()
          ->addWhere('product_id', '=', $dm_id)
          ->addWhere('is_active', '=', TRUE)
          ->addWhere('shipped_on', '>', date("YmdHis", strtotime("-60 day")))
          ->execute()->count();

        $first_shipped_date = InventoryProductVariant::get(TRUE)
          ->addWhere('product_id', '=', $dm_id)
          ->addWhere('is_active', '=', TRUE)
          ->addWhere('shipped_on', '>', date("YmdHis", strtotime("-60 day")))
          ->addOrderBy('shipped_on', 'ASC')
          ->setLimit(1)
          ->execute()->first()['shipped_on'] ?? date('Y-m-d H:i:s', strtotime("-60 day"));

        $active_shipping_days_countCreate = date_create($first_shipped_date);
        $nowDateCreate = date_create(date("YmdHis"));
        $active_shipping_days_count = date_diff($nowDateCreate, $active_shipping_days_countCreate);
        $active_shipping_days_count = $active_shipping_days_count->days ?? 0;
        if ($active_shipping_days_count == 0) {
          $active_shipping_days_count = 1;
        }

        $daily_avg = $total_shipped / $active_shipping_days_count;
        $monthlyAvg = round($daily_avg * 4.2 * 7);
        $daysLeft = $daily_avg == 0 ? 0 : round($totalInventory / $daily_avg);

        // Grab outstanding orders.
        $pendingOrders = InventorySales::get(TRUE)
          ->selectRowCount()
          ->addWhere('is_paid', '=', TRUE)
          ->addWhere('is_fulfilled', '=', FALSE)
          ->addWhere('product_id', '=', $dm_id)
          ->setLimit(25)
          ->execute()->count() ?? 0;

        $stats[$dm_id] = array_merge($deviceModel, [
          'total_inventory' => $totalInventory,
          'available_inventory' => $totalAvailableInventory,
          'pendingOrder' => $pendingOrders,
          'monthly_avg' => $monthlyAvg,
          'days_left' => $daysLeft,
        ]
        );
      }
    }
    catch (UnauthorizedException $e) {

    }
    catch (CRM_Core_Exception $e) {

    }
    return $stats;
  }

  /**
   * Image Base 64.
   *
   * @param string $filePath
   *   File path.
   *
   * @return string
   *   Image Data.
   */
  public static function imageEncodeBase64(string $filePath): string {
    if (file_exists($filePath)) {
      $imageMeta = getimagesize($filePath);
      $data = file_get_contents($filePath);
      $base64 = base64_encode($data);
      $base64 = preg_replace('/\s+/', '', $base64);
      return "data:{$imageMeta['mime']};base64," . rawurlencode($base64);
    }
    return '';
  }

  /**
   * Get image style based on width and height.
   *
   * @param string $filePath
   *   File path.
   *
   * @return string
   *   Style.
   */
  public static function longestSideVertical(string $filePath): string {
    $imageMeta = getimagesize($filePath);
    /*
     = array(
        [0] => 2388 // width
        [1] => 436  // height
        [2] => 3
        [3] => width="2388" height="436"
        [bits] => 8
        [mime] => image/png
     );
     */
    // If width is greater than height then transform the image by 90 degree.
    if (isset($imageMeta['1']) && isset($imageMeta['0']) && $imageMeta['0'] > $imageMeta['1']) {
      return "transform: rotate(90deg)";
    }
    else {
      return "";
    }
  }

}
