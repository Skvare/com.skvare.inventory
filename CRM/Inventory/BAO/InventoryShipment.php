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
    $results = InventoryShipment::create(TRUE)
      ->addValue('contact_id', $contactID)
      ->addValue('created_date', date('YmdHis'))
      ->addValue('is_shipped', FALSE)
      ->addValue('is_finished', FALSE)
      ->execute();
    return $results->first();
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
    /** @var CRM_Inventory_BAO_InventoryShipment $shipment */
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
    $sql = "SELECT shipment.*
      FROM civicrm_inventory_shipment shipment
      INNER JOIN (civicrm_inventory_sales inventory_sales) ON inventory_sales.shipment_id = shipment.id
      WHERE (shipment.is_shipped = '0')
      AND (shipment.is_finished = '0')
      GROUP BY shipment.id
      having COUNT(inventory_sales.id) < {$maxCount}
      ORDER BY shipment.created_date DESC
      LIMIT 1";
    $shipmentObject = CRM_Core_DAO::executeQuery($sql);
    $shipmentObject->find(TRUE);
    if (!$shipmentObject->find(TRUE)) {
      // Create new shipment and get the id.
      $shipmentID = self::createOpenShipment()['id'];
    }
    else {
      // If found get the id.
      $shipmentID = $shipmentObject->id;
    }
    // Get the data with shipment object.
    return self::findById($shipmentID, TRUE);
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
   * @param $shipmentID
   *
   * @return void
   *   Noting.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function payForLabels($shipmentID): void {
    foreach ($this->ordersWithUnpaidShippingLabels($shipmentID) as $sale) {
      if (!$sale['shipment_id']) {
        CRM_Inventory_BAO_InventorySales::createShippingLabel($sale);
      }
      CRM_Inventory_BAO_InventorySales::asyncGetRatesAndPay($sale);
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

}
