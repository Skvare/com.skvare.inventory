<?php

/**
 *
 */

use Civi\API\Exception\UnauthorizedException;
use Civi\Api4\InventorySales;
use Civi\Api4\InventoryShipment;

/**
 *
 */
class CRM_Inventory_BAO_InventoryShipment extends CRM_Inventory_DAO_InventoryShipment {

  /**
   * Error message for validation.
   *
   * @var string
   */
  public string $error = '';

  const MAX_ORDERS = 50;

  const CSV_COLUMNS = [
    "Contact Name",
    "Company or Name",
    "Country",
    "Address 1",
    "Address 2",
    "City",
    "State/Province/Other",
    "Postal Code",
    "Telephone",
  ];

  private static $COLUMN_INDEX;

  /**
   * Function to create new shipment.
   *
   * @return array|null
   *   Shipment array.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function createOpenShipment(): ?array {
    $contactID = CRM_Core_Session::getLoggedInContactID() ?? NULL;
    try {
      $results = InventoryShipment::create(TRUE)
        ->addValue('contact_id', $contactID)
        ->addValue('created_date', date('YmdHis'))
        ->addValue('is_shipped', FALSE)
        ->addValue('is_finished', FALSE)
        ->execute();
      return $results->first();
    }
    catch (UnauthorizedException $e) {
      CRM_Core_Error::debug_var('getMessage 1', $e->getMessage());
    }
    catch (CRM_Core_Exception $e) {
      CRM_Core_Error::debug_var('getMessage 1', $e->getMessage());
    }
    return [];
  }

  /**
   * Function to get Shipment using id.
   *
   * @param int $id
   *   Shipment id.
   * @param bool $returnObject
   *   Return object or array.
   *
   * @return array|object|null
   *   Shipment details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function findById($id, bool $returnObject = FALSE) {
    if ($returnObject) {
      $shipmentObj = new CRM_Inventory_BAO_InventoryShipment();
      $shipmentObj->id = $id;
      $shipmentObj->find(TRUE);
      return $shipmentObj;
    }
    else {
      $shipment = InventoryShipment::get(TRUE)
        ->addSelect('*')
        ->addWhere('id', '=', $id)
        ->setLimit(1)
        ->execute();
      return $shipment->first();
    }
  }

  /**
   * Function to add shipment to sale table.
   *
   * @param int $saleID
   *   Sale ID.
   *
   * @return CRM_Inventory_DAO_InventorySales
   *   Object.
   */
  public static function addShipmentToSale(int $saleID): CRM_Inventory_DAO_InventorySales {
    $shipment = self::findOpen();
    $saleParams = ['id' => $saleID, 'shipment_id' => $shipment->id];
    return CRM_Inventory_BAO_InventorySales::create($saleParams);
  }

  /**
   * Function to get shipment which is not closed and sales order sale than 50.
   *
   * @return CRM_Inventory_BAO_InventoryShipment
   *   Object.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   * @throws \Civi\Core\Exception\DBQueryException
   */
  public static function findOpen(): CRM_Inventory_BAO_InventoryShipment {
    $maxCount = self::MAX_ORDERS;
    $sql = "SELECT shipment.id
      FROM civicrm_inventory_shipment shipment
      INNER JOIN (civicrm_inventory_sales inventory_sales) ON inventory_sales.shipment_id = shipment.id
      WHERE (shipment.is_shipped = '0')
      AND (shipment.is_finished = '0')
      GROUP BY shipment.id
      having COUNT(inventory_sales.id) < {$maxCount}
      ORDER BY shipment.created_date DESC
      LIMIT 1";
    $shipmentObjectID = CRM_Core_DAO::singleValueQuery($sql);
    if (!$shipmentObjectID) {
      // Create new shipment and get the id.
      $shipment = self::createOpenShipment();
      if (!empty($shipment)) {
        $shipmentObjectID = $shipment['id'];
      }
    }
    // Get the data with shipment object.
    return self::findById($shipmentObjectID, TRUE);
  }

  /**
   * Function to reset is_fulfilled flag on all shipment order.
   *
   *  If shipment is not yet shipped.
   *
   * @return void
   *   Nothing.
   */
  public function resetAllOrders():void {
    if (!$this->is_shipped) {
      $salesParams = ['shipment_id' => $this->id];
      $values = [];
      CRM_Inventory_BAO_InventorySales::getValues($salesParams, $values);
      foreach ($values as $saleID => $value) {
        $value['is_fulfilled'] = FALSE;
        CRM_Inventory_BAO_InventorySales::create($value);
      }
    }
  }

  /**
   * Function to remove the sale id from shipment.
   *
   * @param int $saleID
   *   Sale ID.
   *
   * @return void
   *   Nothing.
   *
   * @throws Exception
   */
  public function remove(int $saleID): void {
    if ($this->is_shipped) {
      throw new \Exception('This shipment is already shipped');
    }
    try {
      CRM_Inventory_BAO_InventorySales::detachFromShipment($this->id, $saleID);
    }
    catch (UnauthorizedException $e) {

    }
    catch (CRM_Core_Exception $e) {

    }
  }

  /**
   * Function to get sale by provider.
   *
   * @param array $includes
   *   Array of search parameter.
   *
   * @return array
   *   Sale list by shipment Provider group.
   */
  public function ordersByShippingProvider(array $includes): array {
    $valuesSale = [];
    $sales = CRM_Inventory_BAO_InventorySales::getValues($includes, $valuesSale);
    $result = [];
    foreach ($sales as $sale) {
      /** @var  CRM_Inventory_BAO_InventorySales $sale */
      $shipmentLabelParams = ['sale_id' => $sale->id];
      $values = [];
      $shipments = CRM_Inventory_BAO_InventoryShipmentLabels::getValues($shipmentLabelParams, $values);
      /** @var  CRM_Inventory_BAO_InventoryShipmentLabels $shipment */
      $shipment = reset($shipments);
      $provider = $shipment->provider ?? 'None';
      $result[$provider][] = $sale;
    }

    return $result;
  }

  /**
   * Pay for shipment labels.
   *
   * @param int $shipmentID
   *   Shipment ID.
   *
   * @return void
   *   Noting.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function payForLabels(int $shipmentID): void {
    foreach ($this->ordersWithUnpaidShippingLabels($shipmentID) as &$sale) {
      // Create shipping label record.
      if (!$sale['inventory_shipment_labels.id']) {
        $paramsLabels = [
          'sale_id' => $sale['id'],
          'is_valid' => FALSE,
          'is_paid' => FALSE,
          'amount' => 0,
        ];
        $labelObject = CRM_Inventory_BAO_InventoryShipmentLabels::create($paramsLabels);
        $sale['inventory_shipment_labels.id'] = $labelObject->id;
      }
      if ($sale['shipment_id'] && $sale['inventory_shipment_labels.id']) {
        $shipment = new CRM_Inventory_BAO_InventoryShipmentLabels();
        $shipment->load('id', $sale['id']);
        $shipment->asyncGetRatesAndPay();
      }
    }
  }

  /**
   *
   */
  public function refundLabels() {
    $this->shippingLabels()->where('is_paid', TRUE)->get()->each->asyncRefund();
  }

  /**
   * Returns a set of orders for this shipment that either have shipping.
   *
   * Labels that are unpaid, or have no shipping label at all.
   *
   * @param int $shipmentID
   *   Shipment ID.
   *
   * @return array
   *   Sale Order list.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function ordersWithUnpaidShippingLabels(int $shipmentID): array {
    $inventorySales = InventorySales::get(TRUE)
      ->addSelect('*', 'inventory_shipment_labels.*')
      ->addJoin('InventoryShipmentLabels AS inventory_shipment_labels',
        'LEFT', ['inventory_shipment_labels.is_paid', '=', 1])
      ->addWhere('inventory_shipment_labels.id', 'IS NULL')
      ->addWhere('shipment_id', '=', $shipmentID)
      ->setLimit(0)
      ->execute();
    $inventorySalesList = [];
    foreach ($inventorySales as $inventorySale) {
      $inventorySalesList[] = $inventorySale;
    }
    return $inventorySalesList;
  }

  /**
   * Function to validate the shipment.
   *
   * @return bool
   *
   * @throws CRM_Core_Exception
   */
  public function validate(): bool {
    if ($this->is_shipped) {
      $sql = "SELECT count(*)
        FROM `civicrm_inventory_sales` sales
        INNER JOIN civicrm_inventory_product_variant product_variant on ( sales.id = product_variant.sales_id)
        where product_variant.contact_id is null and sales.shipment_id = {$this->id}
        group by sales.shipment_id";
      $getCount = CRM_Core_DAO::singleValueQuery($sql);
      if ($getCount) {
        $this->is_shipped = FALSE;
        $this->errors = 'This shipment still has orders that are missing device assignments';
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Get Shipment Sale details.
   *
   * @param int $shipmentID
   *   Shipment ID.
   *
   * @return array
   *   Shipment sale details.
   *
   * @throws \Civi\Core\Exception\DBQueryException
   */
  public static function shipmentSalesListing(int $shipmentID): array {
    $sql = "SELECT
        `a`.`id` AS `id`,
        `sales`.`id` AS `sale_id`,
        `sales`.`code`,
        `sales`.`sale_date`,
        `sales`.`contact_id`,
        `sales`.`needs_assignment`,
        `sales`.`has_assignment`,
        `shipment_labels`.`label_url`,
        `product`.`label`,
        `product`.`product_code`,
        `contact`.`sort_name`

        FROM civicrm_inventory_shipment a
        INNER JOIN (civicrm_inventory_sales sales) ON a.id = sales.shipment_id
        LEFT JOIN (civicrm_inventory_shipment_labels shipment_labels) ON sales.id = shipment_labels.sales_id
        LEFT JOIN civicrm_line_item li ON (li.sale_id = sales.id)
        LEFT JOIN civicrm_membership membership ON (membership.id = li.entity_id = li.entity_table = 'civicrm_membership')
        LEFT JOIN civicrm_inventory_product_membership mem_prod ON (mem_prod.membership_type_id = membership.membership_type_id and mem_prod.is_product_serialize and mem_prod.is_active)
        INNER JOIN civicrm_inventory_product product ON (product.id = mem_prod.product_id and mem_prod.is_product_serialize and mem_prod.is_active)
        LEFT JOIN civicrm_contact contact ON sales.contact_id =  contact.id
        where a.id = %1
        order by `product`.`product_code`, `sales`.`sale_date` desc
        LIMIT 100
        OFFSET 0";
    $inputParams = [1 => [$shipmentID, 'Integer']];
    $resultDAO = CRM_Core_DAO::executeQuery($sql, $inputParams);
    $shipmentSalesList = [];
    while ($resultDAO->fetch()) {
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['code'] = $resultDAO->code;
      $formatedSaleDate = CRM_Utils_Date::customFormat($resultDAO->sale_date, '%Y-%m-%d');
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['sale_date'] = $formatedSaleDate;
      /*
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['sale_date_age']
      = CRM_Utils_Date::calculateAge($resultDAO->sale_date);
       */
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['contact_id'] = $resultDAO->contact_id;
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['needs_assignment'] = $resultDAO->needs_assignment;
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['has_assignment'] = $resultDAO->has_assignment;
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['label_url'] = $resultDAO->label_url;
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['product_code'] = $resultDAO->product_code;
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['product_label'] = $resultDAO->label;
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['sort_name'] = $resultDAO->sort_name;

    }
    return $shipmentSalesList;
  }

  public static function manifestForBatch(int $shipmentID) {

  }
  /**
   *
   */
  public static function exportForBatch(int $shipmentID, bool $download = TRUE) {
    $inventorySales = InventorySales::get(TRUE)
      ->addSelect('contact.first_name', 'contact.last_name', 'contact.display_name', 'contact.organization_name', 'contact.employer_id', 'address.street_address', 'address.supplemental_address_1', 'address.city', 'address.postal_code', 'state_province.abbreviation', 'country.iso_code', 'country.name')
      ->addJoin('Contact AS contact', 'INNER', ['contact_id', '=', 'contact.id'])
      ->addJoin('Address AS address', 'LEFT', ['address.is_primary', '=', 1], ['address.contact_id', '=', 'contact.id'])
      ->addJoin('Country AS country', 'LEFT', ['country.id', '=', 'address.country_id'])
      ->addJoin('StateProvince AS state_province', 'LEFT', ['state_province.id', '=', 'address.state_province_id'])
      ->addWhere('shipment_id', '=', $shipmentID)
      ->setLimit(100)
      ->execute();
    $fp = fopen('php://temp', 'r+');
    fputcsv($fp, self::CSV_COLUMNS);
    foreach ($inventorySales as $inventorySale) {
      $row = self::csv_row(
        [
          "Contact Name" => CRM_Inventory_Utils::csv_str($inventorySale['contact.display_name']),
          "Company or Name" => CRM_Inventory_Utils::csv_str($inventorySale['contact.organization_name'] ?? $inventorySale['contact.display_name']),
          "Country" => CRM_Inventory_Utils::csv_str($inventorySale['country.iso_code']),
          "Address 1" => CRM_Inventory_Utils::csv_str($inventorySale['address.street_address']),
          "Address 2" => CRM_Inventory_Utils::csv_str($inventorySale['address.supplemental_address_1']),
          "City" => CRM_Inventory_Utils::csv_str($inventorySale['address.city']),
          "State/Province/Other" => CRM_Inventory_Utils::csv_str($inventorySale['state_province.abbreviation']),
          "Postal Code" => CRM_Inventory_Utils::csv_str($inventorySale['address.postal_code']),
          "Telephone" => '212-966-1900',
        ]
      );
      fputcsv($fp, $row);
    }
    rewind($fp);
    if ($download) {
      CRM_Utils_System::setHttpHeader('Content-Type', 'text/csv');
      CRM_Utils_System::setHttpHeader('Content-Disposition', 'attachment; filename="shipment-' . $shipmentID . '.csv"');
      fpassthru($fp);
      \CRM_Utils_System::civiExit();
    }
    else {
      $csv_string = stream_get_contents($fp);
      fclose($fp);
      return $csv_string;
    }
    return NULL;
  }

  /**
   * Map csv row with header.
   *
   * @param array $data
   *   CSV row.
   *
   * @return array
   *   CSV row.
   */
  public static function csv_row($data) {
    if (self::$COLUMN_INDEX === NULL) {
      self::$COLUMN_INDEX = array_flip(self::CSV_COLUMNS);
    }

    $row = array_fill(0, count(self::CSV_COLUMNS), "");
    foreach ($data as $key => $value) {
      $row[self::$COLUMN_INDEX[$key]] = $value;
    }
    return $row;
  }

}
