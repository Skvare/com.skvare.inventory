<?php

/**
 *
 */

use Civi\API\Exception\UnauthorizedException;
use Civi\Api4\InventorySales;
use Civi\Api4\InventoryShipment;
use Civi\Api4\LineItem;
use Picqer\Barcode\Renderers\PngRenderer;
use Picqer\Barcode\Types\TypeCode128B;

/**
 *
 */
class CRM_Inventory_BAO_InventoryShipment extends CRM_Inventory_DAO_InventoryShipment {
  use CRM_Inventory;

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
      $results = InventoryShipment::create(FALSE)
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
   *
   */
  public static function findOpenShipmentList() {
    $sql = "SELECT shipment.id, shipment.created_date, COUNT(inventory_sales.shipment_id) as total_orders
      FROM civicrm_inventory_shipment shipment
      LEFT JOIN (civicrm_inventory_sales inventory_sales) ON inventory_sales.shipment_id = shipment.id
      WHERE (shipment.is_shipped = '0')
      AND (shipment.is_finished = '0')
      GROUP BY shipment.id
      ORDER BY shipment.created_date DESC";
    $shipmentObject = CRM_Core_DAO::executeQuery($sql);
    $list = [];
    while ($shipmentObject->fetch()) {
      $list[$shipmentObject->id] = "Shipment {$shipmentObject->id} • (" .
        $shipmentObject->total_orders . ' orders) • ' .
        CRM_Utils_Date::processDate($shipmentObject->created_date, '', FALSE,
          'Y-m-d');
    }
    return $list;
  }

  /**
   * Function to reset is_fulfilled flag on all shipment order.
   *
   *  If shipment is not yet shipped.
   *
   * @return void
   *   Nothing.
   */
  public function resetAllOrders(): void {
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
      if (empty($sale['inventory_shipment_labels.id'])) {
        $paramsLabels = [
          'sales_id' => $sale['id'],
          'is_valid' => FALSE,
          'is_paid' => FALSE,
          'amount' => 0,
        ];
        $labelObject = CRM_Inventory_BAO_InventoryShipmentLabels::create($paramsLabels);
        $sale['inventory_shipment_labels.id'] = $labelObject->id;
      }
      if ($sale['shipment_id'] && $sale['inventory_shipment_labels.id']) {
        $shipment = new CRM_Inventory_BAO_InventoryShipmentLabels();
        $shipment->load('id', $sale['inventory_shipment_labels.id']);
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
        'LEFT', ['is_paid', '=', 1])
      // ->addWhere('inventory_shipment_labels.id', 'IS NULL')
      ->addWhere('shipment_id', '=', $shipmentID)
      ->addWhere('is_paid', '=', 1)
      ->addWhere('is_shipping_required', '=', 1)
      ->setLimit(0)
      ->execute();
    $inventorySalesList = [];
    foreach ($inventorySales as $inventorySale) {
      if (empty($inventorySale['inventory_shipment_labels.is_paid'])) {
        $inventorySalesList[] = $inventorySale;
      }
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
        `contact`.`sort_name`,
        `membership`.`id` AS `membership_id`

        FROM civicrm_inventory_shipment a
        INNER JOIN (civicrm_inventory_sales sales) ON a.id = sales.shipment_id
        LEFT JOIN (civicrm_inventory_shipment_labels shipment_labels) ON sales.id = shipment_labels.sales_id
        LEFT JOIN civicrm_line_item li ON (li.sale_id = sales.id and li.entity_table = 'civicrm_membership')
        LEFT JOIN civicrm_membership membership ON (membership.id = li.entity_id = li.entity_table = 'civicrm_membership')
        LEFT JOIN civicrm_inventory_product_membership mem_prod ON (mem_prod.membership_type_id = membership.membership_type_id and mem_prod.is_product_serialize and mem_prod.is_active)
        INNER JOIN civicrm_inventory_product product ON (product.id = mem_prod.product_id and mem_prod.is_product_serialize and mem_prod.is_active)
        LEFT JOIN civicrm_contact contact ON sales.contact_id =  contact.id
        where sales.shipment_id = %1
        LIMIT 100
        OFFSET 0";
    $inputParams = [1 => [$shipmentID, 'Integer']];
    $resultDAO = CRM_Core_DAO::executeQuery($sql, $inputParams);
    $shipmentSalesList = [];
    while ($resultDAO->fetch()) {
      $shipmentSalesList[$resultDAO->label][$resultDAO->sale_id]['sale_id'] = $resultDAO->sale_id;
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

  /**
   * Get Manifest for Shipment batch.
   *
   * @param int $shipmentID
   *   Shipment ID.
   *
   * @return array
   *   Shipment sales details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function manifestForBatch(int $shipmentID): array {
    // Get sales record for shipment ID.
    $inventorySales = InventorySales::get(TRUE)
      ->addWhere('shipment_id', '=', $shipmentID)
      ->addWhere('is_shipping_required', '=', 1)
      ->setLimit(0)
      ->execute()->getArrayCopy();
    foreach ($inventorySales as &$inventorySale) {
      // Get Line item details and its payment information.
      if (!empty($inventorySale['contribution_id'])) {
        $inventorySale['total_amount'] = CRM_Core_DAO::getFieldValue('CRM_Contribute_DAO_Contribution', $inventorySale['contribution_id'], 'total_amount');
        $deductible = ($inventorySale['total_amount'] ?? 0) - ($inventorySale['value_amount'] ?? 0);
        $inventorySale['deductible_amount'] = ($deductible < 0) ? 0 : $deductible;

        $inventorySale['line_items'] = LineItem::get(TRUE)
          ->addWhere('contribution_id', '=', $inventorySale['contribution_id'])
          ->setLimit(25)
          ->execute()->getArrayCopy();
        $inventorySale['amount_due'] =
          CRM_Contribute_BAO_Contribution::getContributionBalance($inventorySale['contribution_id']);
        $inventorySale['payment_details'] =
          CRM_Contribute_BAO_Contribution::getPaymentInfo($inventorySale['contribution_id'], 'contribution', TRUE);
        $inventorySale['address'] = CRM_Inventory_Utils::getAddress($inventorySale['contact_id']);
        if (!empty($inventorySale['address'])) {
          $inventorySale['address_display'] = CRM_Utils_Address::formatVCard($inventorySale['address']);
          $inventorySale['display_text'] = CRM_Utils_Address::format($inventorySale['address']);
          $inventorySale['display_text_2'] = CRM_Utils_Address::format($inventorySale['address']);
        }

        $inputParams = ['sales_id' => $inventorySale['id']];
        $productVariant = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_DAO_InventoryProductVariant', $inputParams);
        if ($productVariant->id) {
          $productVariantDetails =
            CRM_Inventory_BAO_InventoryProductVariant::getProductVariant($productVariant->id, TRUE);
          $inventorySale['product'] = $productVariantDetails;
          if ($productVariant->membership_id) {
            // Bar code at footer section.
            $inventorySale['barcode_number'] = $inventorySale['id'] . '-' . $productVariant->membership_id;
            $colorRed = [0, 0, 0];
            $barcode = (new TypeCode128B())->getBarcode($inventorySale['barcode_number']);
            $renderer = new PngRenderer();
            $renderer->setForegroundColor($colorRed);
            // Save PNG to the filesystem, with widthFactor 3 (width of the
            // barcode x 3) and height of 50 pixel.
            $rowBarcode = $renderer->render($barcode, $barcode->getWidth() * 1, 30);
            $mime = 'image/png';
            $base64 = base64_encode($rowBarcode);
            $base64 = preg_replace('/\s+/', '', $base64);
            $inventorySale['barcode_image'] = "data:{$mime};base64," . rawurlencode($base64);
            $inventorySale['barcode_number'] .= ' (' . $productVariantDetails['product']['label'] . ')';
          }
        }
        else {
          $inventorySale['product'] = [];
          $inventorySale['barcode_number'] = FALSE;
          $inventorySale['barcode_image'] = FALSE;
        }

        $paymentInstrument = '';
        if (!empty($inventorySale['payment_details']['transaction'])) {
          $paymentMethod = [];
          foreach ($inventorySale['payment_details']['transaction'] as $transaction) {
            $tmp = '';
            $tmp .= $transaction['payment_instrument'];
            if (!empty($transaction['check_number'])) {
              $tmp .= ' (' . $transaction['check_number'] . ')';
            }
            $tmp .= ' [' . $transaction['status'] . ']';
            $paymentMethod[] = $tmp;
          }
          if (!empty($paymentMethod)) {
            $paymentInstrument = implode('<br/> ', $paymentMethod);
          }
        }
        $inventorySale['payment_instrument'] = $paymentInstrument;
      }
    }
    return $inventorySales;
  }

  /**
   * Print manifest.
   *
   * @param int $shipmentID
   *   Shipment ID.
   * @param array $manifestForBatch
   *   Array details.
   *
   * @return void
   *   Nothing.
   *
   * @throws SmartyException
   * @throws CRM_Core_Exception
   */
  public static function printManifestForBatch(int $shipmentID, array $manifestForBatch): void {
    $htmlArray = [];
    $sendTemplateParams = [
      'groupName' => 'msg_tpl_workflow_manifest',
      'isTest' => 0,
      'tplParams' => [],
    ];
    $pdfFormat = [
      'paper_size' => 'letter',
      'stationery' => NULL,
      'orientation' => 'portrait',
      'metric' => 'in',
      'margin_top' => 0.25,
      'margin_bottom' => 0.25,
      'margin_left' => 0.5,
      'margin_right' => 0.5,
      'weight' => 2,
    ];

    $customCssPath = CRM_Extension_System::singleton()->getMapper()->keyToBasePath('com.skvare.inventory') . '/assets/css/style.css';
    $customCss = file_get_contents($customCssPath);
    CRM_Core_Region::instance('export-document-header')->add(['style' => "{$customCss}"]);
    $headerImagePathUrl = CRM_Extension_System::singleton()->getMapper()->keyToUrl('com.skvare.inventory') . '/assets/image/letterhead-bw.png';
    $headerImagePath = CRM_Extension_System::singleton()->getMapper()->keyToBasePath('com.skvare.inventory') . '/assets/image/letterhead-bw.png';
    $sendTemplateParams['valueName'] = 'shipping_manifest';
    $image = CRM_Inventory_Utils::imageEncodeBase64($headerImagePath);
    $sendTemplateParams['tplParams']['headerImage'] = $image;
    foreach ($manifestForBatch as $sale) {
      $sendTemplateParams['contactId'] = $sale['contact_id'];
      $sendTemplateParams['tplParams']['sale'] = $sale;
      [$sent, $subject, $message, $html] = CRM_Core_BAO_MessageTemplate::sendTemplate($sendTemplateParams);
      $htmlArray[] = $html;
    }
    CRM_Utils_PDF_Utils::html2pdf($htmlArray, "Shipment_Manifest_{$shipmentID}.pdf", FALSE, $pdfFormat);
    CRM_Utils_System::civiExit();
  }

  /**
   * Export shipment batch.
   *
   * @param int $shipmentID
   *   Shipment id.
   * @param bool $download
   *   Download the file.
   *
   * @return false|string|null
   *   Download file or return file string.
   *
   * @throws CRM_Core_Exception
   * @throws UnauthorizedException
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

  /**
   * Assign device to contact based on order.
   *
   * @param string $orderID
   *   Order Code.
   * @param string $deviceID
   *   Device IMEI number.
   * @param bool $isPrimary
   *   Set Device is primary device to contact.
   *
   * @return array|void
   *   Error details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function assignDeviceToOrder(string $orderID, string $deviceID, bool $isPrimary = TRUE) {
    $this->sales = $this->findEntityById('code', $orderID, 'InventorySales', TRUE);
    $errors = [];
    $lineItems = [];
    $membershipDetail = [];
    if ($this->sales->id) {
      $deviceModelIds = CRM_Inventory_BAO_InventoryProduct::productIds();
      $lineItems = CRM_Inventory_BAO_InventorySales::getLineItemBySaleID($this->sales->id);
      foreach ($lineItems as $lineObject) {
        /** @var CRM_Price_DAO_LineItem $lineObject */
        if ($lineObject->id && in_array($lineObject->product_id, $deviceModelIds)) {
          $membershipID = $lineObject->membership_id;
          if ($lineObject->product_variant_id) {
            $errors['order_id'] = 'A device has already been assigned for item';
          }
          if (empty($membershipID)) {
            $errors['membership_id'] = 'Line item missing membership id';
          }
          else {
            /** @var CRM_Member_DAO_Membership $membershipDetail */
            $membershipDetail = CRM_Inventory_BAO_Membership::findById($membershipID, TRUE);
            if (empty($membershipDetail->id)) {
              $errors['order_id'] = "No such membership ID";
            }
          }
        }
      }
    }
    else {
      if (!$this->sales->id) {
        $errors['order_id'] = "No such order ID";
      }
    }

    $this->productVariant = $this->findEntityById('product_variant_unique_id', $deviceID, 'InventoryProductVariant', TRUE);
    if (!$this->productVariant->id) {
      $errors['device_id'] = "No such device ID";
    }
    elseif ($this->productVariant->contact_id || $this->productVariant->membership_id) {
      $errors['device_id'] = "Device already assigned";
    }

    // sale/order is present, product is matched, membership details present,
    // line item matched and no error.
    if ($this->sales->id && $this->productVariant->id && !empty($membershipDetail) && !empty($lineItems) && empty($errors)) {
      $expectedProductModelID = $this->getDeviceModelForSale($this->sales->id);
      if ($this->productVariant->product_id != $expectedProductModelID) {
        $errors['device_id'] = "That device does not match that order";
      }

      if (empty($errors)) {
        $this->assignDeviceToContactOrder($membershipDetail, $lineItems, $isPrimary);
      }
    }
    return $errors;
  }

  /**
   * Assign Device To Contact Order.
   *
   * @param CRM_Member_DAO_Membership $membershipDetail
   *   Membership Object.
   * @param array $lineItems
   *   Line item object.
   * @param bool $isPrimary
   *   Set as Primary device.
   *
   * @return void
   *   Nothing.
   */
  public function assignDeviceToContactOrder(CRM_Member_DAO_Membership $membershipDetail, array $lineItems, $isPrimary = TRUE): void {
    // Assign contact ID in variant table.
    // Add variant id on the line item table.
    $this->productVariant->shipped_on = $this->productVariant->shipped_on ?? date('Y-m-d H:i:s');
    $nonDeviceProductIds = CRM_Inventory_BAO_InventoryProduct::productIds(FALSE);
    foreach ($lineItems as $lineObject) {
      // Match the line item product and update the variant id.
      if ($lineObject->product_id == $this->productVariant->product_id) {
        $lineObject->product_variant_id = $this->productVariant->id;
        $lineObject->save();
      }
      if (in_array($lineObject->product_id, $nonDeviceProductIds)) {
        $nonDeviceVariantId = CRM_Core_DAO::getFieldValue('CRM_Inventory_DAO_InventoryProductVariant', $lineObject->product_id, 'id', 'product_id');
        if (!empty($nonDeviceVariantId)) {
          $lineObject->product_variant_id = $nonDeviceVariantId;
          $lineObject->save();
        }
      }
    }

    // $this->sales->is_shipping_required = 1;
    $this->sales->needs_assignment = 1;
    $this->sales->has_assignment = 1;
    $this->sales->save();

    $this->productVariant->contact_id = $this->sales->contact_id;
    $this->productVariant->sales_id = $this->sales->id;
    $this->productVariant->membership_id = $membershipDetail->id;
    if ($this->productVariant->isTerminated()) {
      try {
        $this->productVariant->changeStatus($this->productVariant->id, 'REACTIVATE', "Reactivating because assigned to [order:{$this->sales->code}]");
      }
      catch (UnauthorizedException|CRM_Core_Exception $e) {

      }
    }
    $this->productVariant->expire_on = NULL;
    $this->productVariant->status = 'assigned_to_member';
    $this->productVariant->save();
    if ($isPrimary) {
      $this->productVariant->setPrimary(TRUE, TRUE);
    }
  }

  /**
   * Get Product or model id for sale.
   *
   * @param int $saleID
   *   Sale Id.
   *
   * @return string|null
   *   Product Model ID.
   *
   * @throws CRM_Core_Exception
   */
  public function getDeviceModelForSale(int $saleID): ?string {
    $sql = "select mem_prod.product_id as product_id
      FROM civicrm_inventory_sales sales
      INNER JOIN civicrm_line_item li
        ON (li.sale_id = sales.id and li.entity_table = 'civicrm_membership')
      INNER JOIN civicrm_membership membership
        ON (membership.id = li.entity_id = li.entity_table = 'civicrm_membership')
      INNER JOIN civicrm_inventory_product_membership mem_prod
        ON (mem_prod.membership_type_id = membership.membership_type_id and mem_prod.is_product_serialize and mem_prod.is_active)
      INNER JOIN civicrm_inventory_product product
        ON (product.id = mem_prod.product_id and mem_prod.is_product_serialize and mem_prod.is_active)
      where sales.id = {$saleID}";
    return CRM_Core_DAO::singleValueQuery($sql);
  }

  /**
   * Move sale order to another shipment.
   *
   * @param int $newShipmentID
   *   New Shipment ID.
   * @param array $saleIDs
   *   Sale id list.
   *
   * @return bool
   *   Moved.
   */
  public static function moveSalesOrderToOtherShipment(int $newShipmentID, array $saleIDs): bool {
    $moved = FALSE;
    if (!empty($saleIDs)) {
      try {
        $saleIDs = array_unique($saleIDs);
        $saleIdString = implode(',', $saleIDs);
        $sql = "UPDATE civicrm_inventory_sales SET shipment_id = $newShipmentID WHERE id IN ($saleIdString)";
        CRM_Core_DAO::executeQuery($sql);
        $moved = TRUE;
      }
      catch (Exception $e) {

      }
    }
    return $moved;
  }

}
