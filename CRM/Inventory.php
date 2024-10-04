<?php

/**
 * CRM_Inventory.
 *
 * Utility functions.
 */
trait CRM_Inventory {

  /**
   * Object.
   *
   * @var CRM_Inventory_BAO_InventorySales
   */
  public $sales = NULL;

  /**
   * Object.
   *
   * @var CRM_Inventory_BAO_InventoryShipmentLabels
   */
  private $shipmentLabel = NULL;

  /**
   * Object.
   *
   * @var CRM_Inventory_BAO_InventoryProduct
   */
  private $product = NULL;

  /**
   * Object.
   *
   * @var CRM_Inventory_BAO_InventoryProductVariant
   */
  private $productVariant = NULL;

  /**
   * Object.
   *
   * @var array
   */
  private $lineItem = [];

  /**
   * Object.
   *
   * @var array
   */
  private $address = NULL;

  /*
  function __construct($searchColumn = 'id', int|string $searchValue = '', string $entity, bool $returnObject = TRUE) {
  $this->$entity = self::findById($searchColumn, $searchValue, $entity, $returnObject);
  }
   */

  /**
   * Get Entity record.
   *
   * @param string $searchColumn
   * @param int|string $searchValue
   * @param string $entity
   * @param bool $returnObject
   * @return mixed|null
   */
  public function findEntityById($searchColumn, int|string $searchValue, string $entity, bool $returnObject = TRUE): mixed {
    $entityClass = [
      'InventoryShipment' => 'CRM_Inventory_BAO_InventoryShipment',
      'InventoryShipmentLabels' => 'CRM_Inventory_BAO_InventoryShipmentLabels',
      'InventoryBatch' => 'CRM_Inventory_BAO_InventoryBatch',
      'InventoryProduct' => 'CRM_Inventory_BAO_InventoryProduct',
      'InventoryProductChangelog' => 'CRM_Inventory_BAO_InventoryProductChangelog',
      'InventoryProductMembership' => 'CRM_Inventory_BAO_InventoryProductMembership',
      'InventoryProductVariant' => 'CRM_Inventory_BAO_InventoryProductVariant',
      'InventoryProductVariantReplacement' => 'CRM_Inventory_BAO_InventoryProductVariantReplacement',
      'InventoryReferrals' => 'CRM_Inventory_BAO_InventoryReferrals',
      'InventorySales' => 'CRM_Inventory_BAO_InventorySales',
      'Membership' => 'CRM_Member_BAO_Membership',
      'Contact' => 'CRM_Contact_BAO_Contact',
      'Contribution' => 'CRM_Contribute_BAO_Contribution',
    ];
    if (!array_key_exists($entity, $entityClass)) {
      return NULL;
    }
    if ($returnObject) {
      $class = $entityClass[$entity];
      $shipmentObj = new $class();
      $shipmentObj->$searchColumn = $searchValue;
      $shipmentObj->find(TRUE);
      if ($class == 'CRM_Inventory_BAO_InventoryShipmentLabels') {
        if (!empty($shipmentObj->shipment) && !is_array($shipmentObj->shipment)) {
          $shipmentObj->shipment = json_decode($shipmentObj->shipment, TRUE);
        }
        if (!empty($shipmentObj->purchase) && !is_array($shipmentObj->purchase)) {
          $shipmentObj->purchase = json_decode($shipmentObj->purchase, TRUE);
        }
      }
      return $shipmentObj;
    }
    else {
      $cname = "Civi\\Api4\\" . $entity;
      $shipment = $cname::get(TRUE)
        ->addSelect('*')
        ->addWhere($searchColumn, '=', $searchValue)
        ->setLimit(1)
        ->execute();
      return $shipment->first();
    }
  }

  /**
   * Get Shipment details.
   *
   * @param object $classObject
   *   Shipment address.
   *
   * @return array
   *   Address details.
   *
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function getShipmentAddress(&$classObject): array {
    $address = [];
    if ($classObject->N && !empty($classObject->contact_id)) {
      $address = CRM_Inventory_Utils::getAddress($classObject->contact_id);
    }
    return $address;
  }

  /**
   * Get Product details.
   *
   * @param object $classObject
   *   Mixed object.
   *
   * @return CRM_Inventory_BAO_InventoryProduct
   *   Product details.
   */
  public function getProduct(&$classObject):
  CRM_Inventory_BAO_InventoryProduct {
    $column = '';
    $value = '';
    if ($classObject instanceof CRM_Inventory_BAO_InventoryProductVariant) {
      $value = $classObject->product_id;
      $column = 'id';
    }
    if ($classObject->N && !empty($value)) {
      $product = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventoryProduct', [$column => $value], TRUE);
    }
    else {
      $product = new CRM_Inventory_BAO_InventoryProduct();
    }
    return $product;
  }

  /**
   * Get product variant details.
   *
   * @param object $classObject
   *   Mixed object.
   *
   * @return CRM_Inventory_BAO_InventoryProductVariant
   *   Product variant details.
   */
  public function getProductVariant(&$classObject):
  CRM_Inventory_BAO_InventoryProductVariant {
    $column = '';
    $value = '';
    if ($classObject instanceof CRM_Inventory_BAO_InventorySales) {
      $value = $classObject->id;
      $column = 'sale_id';
    }
    elseif ($classObject instanceof CRM_Inventory_BAO_InventoryProduct) {
      $value = $classObject->id;
      $column = 'product_id';
    }
    elseif ($classObject instanceof CRM_Member_BAO_Membership) {
      $value = $classObject->id;
      $column = 'membership_id';
    }
    elseif ($classObject instanceof CRM_Contact_BAO_Contact) {
      $value = $classObject->id;
      $column = 'contact_id';
    }
    if ($classObject->N && !empty($value)) {
      $productVariant = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventoryProductVariant', [$column => $value], TRUE);
    }
    else {
      $productVariant = new CRM_Inventory_BAO_InventoryProductVariant();
    }
    return $productVariant;
  }

  /**
   * Get Product details.
   *
   * @param object $classObject
   *   Mixed object.
   *
   * @return CRM_Inventory_BAO_InventoryProductChangelog
   *   Product details.
   */
  public function getSaleProductVariantChangeLog(&$classObject): CRM_Inventory_BAO_InventoryProductChangelog {
    $column = '';
    $value = '';
    if ($classObject instanceof CRM_Inventory_BAO_InventoryProductVariant) {
      $value = $classObject->id;
      $column = 'product_variant_id';
    }
    elseif ($classObject instanceof CRM_Inventory_BAO_InventoryBatch) {
      $value = $classObject->id;
      $column = 'batch_id';
    }
    elseif ($classObject instanceof CRM_Contact_BAO_Contact) {
      $value = $classObject->id;
      $column = 'contact_id';
    }
    if ($classObject->N && !empty($value)) {
      $productChangelog = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventoryProductChangelog', [$column => $value], TRUE);
    }
    else {
      $productChangelog = new CRM_Inventory_BAO_InventoryProductChangelog();
    }
    return $productChangelog;
  }

  /**
   * Get product variant details.
   *
   * @param object $classObject
   *   Class object.
   *
   * @return CRM_Inventory_BAO_InventorySales
   *   Object or null.
   */
  public function getSales(&$classObject): CRM_Inventory_BAO_InventorySales {
    $column = '';
    $value = '';
    if ($classObject instanceof CRM_Inventory_BAO_InventoryShipment) {
      $value = $classObject->id;
      $column = 'shipment_id';
    }
    if ($classObject instanceof CRM_Inventory_BAO_InventoryShipmentLabels) {
      $value = $classObject->sales_id;
      $column = 'id';
    }
    elseif ($classObject instanceof CRM_Contribute_BAO_Contribution) {
      $value = $classObject->id;
      $column = 'contribution_id';
    }
    elseif ($classObject instanceof CRM_Contact_BAO_Contact) {
      $value = $classObject->id;
      $column = 'contact_id';
    }
    if ($classObject->N && !empty($value)) {
      $sale = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventorySales', [$column => $value], TRUE);
    }
    else {
      $sale = new CRM_Inventory_BAO_InventorySales();
    }
    return $sale;
  }

  /**
   * Get product variant details.
   *
   * @param object $classObject
   *   Class object.
   *
   * @return CRM_Inventory_BAO_InventoryShipmentLabels
   *   Object or null.
   */
  public function getShipmentLabels(&$classObject):
  CRM_Inventory_BAO_InventoryShipmentLabels {
    $column = '';
    $value = '';
    if ($classObject instanceof CRM_Inventory_BAO_InventorySales) {
      $value = $classObject->id;
      $column = 'sale_id';
    }
    elseif ($classObject instanceof CRM_Contact_BAO_Contact) {
      $value = $classObject->id;
      $column = 'contact_id';
    }
    if ($classObject->N && !empty($value)) {
      $shipmentLabel = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventoryShipmentLabels', [$column => $value], TRUE);
    }
    else {
      $shipmentLabel = new CRM_Inventory_BAO_InventoryShipmentLabels();
    }
    return $shipmentLabel;
  }

  /**
   * Get sale line item details.
   *
   * @param object $classObject
   *   Class object.
   *
   * @return CRM_Price_BAO_LineItem[]
   *   Array of line item.
   */
  public function getSalesLineItems(&$classObject): array {
    $column = '';
    $value = '';
    if ($classObject instanceof CRM_Inventory_BAO_InventoryProductVariant) {
      $value = $classObject->id;
      $column = 'product_variant_id';
    }
    elseif ($classObject instanceof CRM_Contribute_BAO_Contribution) {
      $value = $classObject->id;
      $column = 'contribution_id';
    }
    if ($classObject instanceof CRM_Inventory_BAO_InventorySales) {
      $value = $classObject->id;
      $column = 'sale_id';
    }

    if ($classObject->N && !empty($value)) {
      $lineItem = CRM_Inventory_Utils::commonRetrieveAll('CRM_Price_BAO_LineItem', [$column => $value], FALSE);
    }
    else {
      $lineItem = [];
    }
    return $lineItem;
  }

}
