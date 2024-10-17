<?php

/**
 *
 */

use Civi\Api4\InventorySales;
use Civi\Api4\LineItem;
use Civi\Core\Event\PostEvent;
use Civi\Core\Event\PreEvent;

/**
 *
 */
class CRM_Inventory_BAO_InventorySales extends CRM_Inventory_DAO_InventorySales {
  use CRM_Inventory;

  /**
   * Function to preload the object.
   *
   * @param string $columName
   *   Field column.
   * @param mixed $value
   *   Field Value.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function load(string $columName = 'id', mixed $value = ''): void {
    $this->sales = $this->findEntityById($columName, $value, 'InventorySales', TRUE);
    $this->shipmentLabel = $this->getShipmentLabels($this->sales);
    $this->address = $this->getShipmentAddress($this->sales);
    $this->lineItem = $this->getSalesLineItems($this->sales);
    $this->productVariant = $this->getProductVariant($this->sales);
    $this->product = $this->getProduct($this->productVariant);
  }

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
   * Callback for hook_civicrm_post().
   *
   * @param \Civi\Core\Event\PostEvent $event
   *   Object event.
   *
   * @throws \CRM_Core_Exception
   */
  public static function self_hook_civicrm_post(PostEvent $event): void {
    if ($event->action === 'update' || $event->action === 'edit') {
      // CRM_Inventory_BAO_InventoryShipment::addShipmentToSale($event->id);.
    }
  }

  /**
   * Callback for hook_civicrm_pre().
   *
   * @param \Civi\Core\Event\PreEvent $event
   *   Object event.
   *
   * @throws \CRM_Core_Exception
   */
  public static function self_hook_civicrm_pre(PreEvent $event): void {
    if ($event->action === 'update' || $event->action === 'edit') {
      // Check the call.
    }
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
      $code = self::generateCode(8);
    } while (self::checkDuplicate($code));

    return $code;
  }

  /**
   * Check order code is not duplicate.
   *
   * @param string $code
   *   Order code.
   *
   * @return bool
   *   Is duplicate.
   *
   * @throws CRM_Core_Exception
   */
  public static function checkDuplicate($code):bool {
    // This field must be indexed, it gets used heavily.
    $sql = "select count(code) from civicrm_inventory_sales  where code = %1";
    $count = CRM_Core_DAO::singleValueQuery($sql, [1 => [$code, 'String']]);
    return (bool) $count;
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
      ->addJoin('InventorySales AS inventory_sales', 'LEFT')
      ->addWhere('sale_id', '=', $saleID)
      ->addWhere('product_id', 'IS NOT NULL')
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
    $itemMatch = array_values($itemMatch);
    $filePath = __DIR__ . '/../../../parcels.json';
    $parcelData = json_decode(file_get_contents($filePath), TRUE);
    $keyList = [];
    foreach ($parcelData as $mainKey => $parcelInfo) {
      $keyList = [];
      foreach ($parcelInfo['items'] as $type => $itemInfo) {
        foreach ($itemMatch as $item) {
          $result = in_array($item['1'], $itemInfo['names']) && ($itemInfo["class"] == $item[0] || $item[0] == "Item");
          if ($result) {
            $keyList[] = $type;
          }
        }
        if (count($itemMatch) == count($keyList)) {
          break 2;
        }
      }
    }
    $parcelDetails = [];
    if (!empty($keyList)) {
      $matchKey = implode('_with_', $keyList);
      if (array_key_exists($matchKey, $parcelData)) {
        $parcelDetails = $parcelData[$matchKey];
      }
      else {
        $keyList = array_reverse($keyList);
        $matchKey = implode('_with_', $keyList);
        if (array_key_exists($matchKey, $parcelData)) {
          $parcelDetails = $parcelData[$matchKey];
        }
        else {
          $parcelDetails = $parcelData['hotspot_with_shirt'];
        }
      }
    }
    else {
      $parcelDetails = $parcelData['hotspot_with_shirt'];
    }

    return array_merge($parcelDetails, ['items' => NULL]);
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
   *
   */
  public function missingDevices() {

    if (!isset($this->missing_devices)) {
      $this->missing_devices = array_filter(array_map(function ($oi) {
        if ($oi->sales->needs_assignment && !$oi->sales->has_assignment) {
          return [$oi->item->product, $oi->membership];
        }
        return NULL;
      }, $this->lineItem));
    }
    return $this->missing_devices;
  }

  /**
   * Find Assignable Sale Line item.
   *
   * Returns the first order line item that is assignable and that match the
   * criteria.
   *
   * @param $membership
   * @param $deviceModel
   * @param $device
   *
   * @return mixed|null
   */
  public function findAssignableOrderItem($membership = 'unset', $deviceModel = 'unset', $device = 'unset'): mixed {
    return array_values(array_filter($this->lineItem, function ($oi) use ($membership, $device, $deviceModel) {
      /** @var CRM_Price_BAO_LineItem $oi */
      return $oi->needs_assignment &&
        ($membership == 'unset' || ($oi->entity_id == $membership->id && $oi->entity_table == 'civicrm_membership')) &&
        ($device == 'unset' || $oi->product_variant_id == $device->id) &&
        ($deviceModel == 'unset' || $oi->item->product == $deviceModel);
    }))[0] ?? NULL;
  }

  /**
   * Update Flags.
   *
   * @return void
   *   Noting.
   */
  public function updateFlags() {
    $this->is_shipping_required = 0;
    $this->needs_assignment = 0;
    $this->has_assignment = 1;
  }

  /**
   * Get line item by field name and value.
   *
   * @param string $fieldValue
   *   Field value.
   * @param string $fieldName
   *   Field name.
   *
   * @return array|null
   * @throws \Civi\Core\Exception\DBQueryException
   */
  public static function getLineItemBySaleID(string $fieldValue, string $fieldName = 'sale_id'): ?array {
    $sql = "select id from civicrm_line_item where $fieldName = $fieldValue";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $lineItems = NULL;
    while ($dao->fetch()) {
      $lineObject = CRM_Price_DAO_LineItem::findById($dao->id);
      $lineItems[$lineObject->id] = $lineObject;
    }
    return $lineItems;
  }

}
