<?php

/**
 *
 */

use Civi\Api4\InventorySales;
use Civi\Api4\LineItem;

/**
 *
 */
class CRM_Inventory_BAO_InventorySales extends CRM_Inventory_DAO_InventorySales {

  /**
   * Create a new InventorySales based on array-data.
   *
   * @param array $params
   *   Key-value pairs.
   *
   * @return CRM_Inventory_DAO_InventorySales|null
   *   Object of sales.
   */
  public static function create($params) {
    $className = 'CRM_Inventory_DAO_InventorySales';
    $entityName = 'InventorySales';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Function to generate random code for sale order.
   *
   * @param int $length
   *   Length of code.
   *
   * @return string
   *   Randomly generated code.
   *
   * @throws \Random\RandomException
   */
  public static function generateCode($length = 10):string {
    return strtoupper(bin2hex(random_bytes($length / 2)));
  }

  /**
   * Function to get New order code.
   *
   * @return string
   *   Randomly generated code.
   *
   * @throws \Random\RandomException
   */
  public static function getNewCode():string {
    do {
      $code = self::generateCode();
    } while (!CRM_Core_DAO::objectExists($code, 'CRM_Inventory_DAO_InventorySales', 'code'));

    return $code;
  }

  /**
   * Fetch the object and store the values in the values array.
   *
   * @param array $params
   *   Input parameters to find object.
   * @param array $values
   *   Output values of the object.
   *
   * @return array|null
   *   The found object or null
   */
  public static function getValues(array $params, array &$values): ?array {
    if (empty($params)) {
      return NULL;
    }
    $inventorySales = new CRM_Inventory_BAO_InventorySales();
    $inventorySales->copyValues($params);
    $inventorySales->find();
    $inventorySalesArray = [];
    while ($inventorySales->fetch()) {
      CRM_Core_DAO::storeValues($inventorySales, $values[$inventorySales->id]);
      $inventorySalesArray[$inventorySales->id] = $inventorySales;
    }
    return $inventorySalesArray;
  }

  /**
   * Get product details from sale.
   *
   * @param int $saleID
   *   Sale ID.
   *
   * @return array
   *   Product details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function getProductDetailOfLineItem(int $saleID): array {
    $lineItems = LineItem::get(TRUE)
      ->addSelect('inventory_product.label', 'inventory_product.product_code',
        'label', 'inventory_category.title', 'id', 'entity_id',
        'contribution_id', 'line_total', 'unit_price', 'qty', 'product_variant_id',
        'entity_table', 'inventory_sales.is_shipping_required', 'inventory_product.packed_width',
        'inventory_product.packed_weight', 'inventory_product.packed_height',
        'inventory_product.packed_depth', 'inventory_product.uom')
      ->addJoin('InventoryProductVariant AS inventory_product_variant',
        'INNER', ['product_variant_id', '=', 'inventory_product_variant.id'])
      ->addJoin('InventoryProduct AS inventory_product', 'INNER')
      ->addJoin('InventoryCategory AS inventory_category', 'INNER')
      ->addJoin('InventorySales AS inventory_sales', 'INNER')
      ->addWhere('sale_id', '=', $saleID)
      ->setLimit(25)
      ->execute();
    $productDetails = [];
    foreach ($lineItems as $key => $lineItem) {
      $productDetails[$key]['product_label'] = $lineItem['inventory_product.label'];
      $productDetails[$key]['product_name'] = $lineItem['inventory_product.product_code'];
      $productDetails[$key]['product_class'] = $lineItem['inventory_category.title'];
      $productDetails[$key]['item'] = $lineItem['label'];
      $productDetails[$key]['entity_id'] = $lineItem['entity_id'];
      $productDetails[$key]['contribution_id'] = $lineItem['contribution_id'];
      $productDetails[$key]['line_total'] = $lineItem['line_total'];
      $productDetails[$key]['unit_price'] = $lineItem['unit_price'];
      $productDetails[$key]['qty'] = $lineItem['qty'];
      $productDetails[$key]['product_variant_id'] = $lineItem['product_variant_id'];
      $productDetails[$key]['entity_table'] = $lineItem['entity_table'];
      $productDetails[$key]['is_shipping_required'] = $lineItem['inventory_sales.is_shipping_required'];
      $productDetails[$key]['packed_width'] = $lineItem['inventory_product.packed_width'] ?? 1;
      $productDetails[$key]['packed_length'] = $lineItem['inventory_product.packed_depth'] ?? 1;
      $productDetails[$key]['packed_height'] = $lineItem['inventory_product.packed_height'] ?? 1;
      $productDetails[$key]['packed_weight'] = $lineItem['inventory_product.packed_weight'] ?? 1;
      $productDetails[$key]['mass_unit'] = $lineItem['inventory_product.uom'] ?? 'lb';
      $productDetails[$key]['distance_unit'] = 'in';
    }
    return $productDetails;
  }

  /**
   * Get Parcel details.
   *
   * @param int $saleID
   *   Sale ID.
   *
   * @return array|null
   *   Parcel related details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function parcel(int $saleID): ?array {
    $lineItems = CRM_Inventory_BAO_InventorySales::getProductDetailOfLineItem($saleID);
    $itemMatch = array_filter(array_map(function ($oi) {
      return $oi['product_name'] ? [$oi['product_class'], $oi['product_name']] :
        ["Item", $oi['item']];
    }, $lineItems));

    $filePath = __DIR__ . '/../../../parcels.json';
    $parcelData = json_decode(file_get_contents($filePath), TRUE);
    $possible = array_filter($parcelData, function ($parcel) use ($itemMatch) {
      $matchFound = TRUE;
      foreach ($parcel['items'] as $label => $item) {
        $matchFound = array_reduce($itemMatch, function ($carry, $itemMatch) use ($item) {
          return $carry && in_array($itemMatch[1], $item["names"]) && ($item["class"] == $itemMatch[0] || $itemMatch[0] == "Item");
        }, TRUE);
      }
      return $matchFound;
    });
    $possible = array_values($possible);
    return empty($possible) ? NULL : array_merge($possible[0], ['items' => NULL]);
  }

  /**
   * Function to detach the sale from shipment.
   *
   * @param int $shipmentID
   *   Shipment ID.
   * @param int $saleID
   *   Sale ID.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function detachFromShipment(int $shipmentID, int $saleID):void {
    $results = InventorySales::update(TRUE)
      ->addValue('shipment_id', NULL)
      ->addValue('is_fulfilled', FALSE)
      ->addWhere('id', '=', $saleID)
      ->addWhere('shipment_id', '=', $shipmentID)
      ->execute();
  }

  /**
   * Function to create shipping labels.
   *
   * @param array $params
   *   Sale params.
   *
   * @return void
   *   Nothing.
   */
  public static function createShippingLabel(array $params):void {

  }

  /**
   * Function to pay for shipping labels.
   *
   * @param array $params
   *   Sale params.
   *
   * @return void
   *   Nothing.
   */
  public static function asyncGetRatesAndPay(array $params):void {

  }

}
