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
   * @var CRM_Inventory_DAO_InventorySales
   */
  public $sales = NULL;

  /**
   * Object.
   *
   * @var CRM_Inventory_DAO_InventoryShipmentLabels
   */
  private $shipmentLabel = NULL;

  /**
   * Object.
   *
   * @var CRM_Inventory_DAO_InventoryProduct
   */
  private $product = NULL;

  /**
   * Object.
   *
   * @var CRM_Inventory_DAO_InventoryProductVariant
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
   * @param $searchColumn
   * @param int|string $searchValue
   * @param string $entity
   * @param bool $returnObject
   * @return mixed|null
   */
  public function findEntityById($searchColumn = 'id', int|string $searchValue = '', string $entity, bool $returnObject = TRUE) {
    $ENTITY_CLASS = [
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
    if (!array_key_exists($entity, $ENTITY_CLASS)) {
      return NULL;
    }
    if ($returnObject) {
      $class = $ENTITY_CLASS[$entity];
      $shipmentObj = new $class();
      $shipmentObj->$searchColumn = $searchValue;
      $shipmentObj->find(TRUE);
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
    if ($classObject->address === NULL && $classObject->N && !empty($classObject->contact_id)) {
      $classObject->address = CRM_Inventory_Utils::getAddress($classObject->contact_id);
    }
    return $classObject->address;
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
    if ($classObject->product === NULL && $classObject->N && !empty($value)) {
      $classObject->product = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventoryProduct', [$column => $value], TRUE);
    }
    else {
      $classObject->product = new CRM_Inventory_BAO_InventoryProduct();
    }
    return $classObject->product;
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
    if ($classObject->productVariant === NULL && $classObject->N && !empty($value)) {
      $classObject->productVariant = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventoryProductVariant', [$column => $value], TRUE);
    }
    else {
      $classObject->productVariant = new CRM_Inventory_BAO_InventoryProductVariant();
    }
    return $classObject->productVariant;
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
    if ($classObject->productChangelog === NULL && $classObject->N && !empty($value)) {
      $classObject->productChangelog = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventoryProductChangelog', [$column => $value], TRUE);
    }
    else {
      $classObject->productChangelog = new CRM_Inventory_BAO_InventoryProductChangelog();
    }
    return $classObject->productChangelog;
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
    if ($classObject->sale === NULL && $classObject->N && !empty($value)) {
      $classObject->sale = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventorySales', [$column => $value], TRUE);
    }
    else {
      $classObject->sale = new CRM_Inventory_BAO_InventorySales();
    }
    return $classObject->sale;
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
    if ($classObject->shipmentLabel === NULL && $classObject->N && !empty($value)) {
      $classObject->shipmentLabel = CRM_Inventory_Utils::commonRetrieveAll('CRM_Inventory_BAO_InventoryShipmentLabels', [$column => $value], TRUE);
    }
    else {
      $classObject->shipmentLabel = new CRM_Inventory_BAO_InventoryShipmentLabels();
    }
    return $classObject->shipmentLabel;
  }

  /**
   * Get sale line item details.
   *
   * @param object $classObject
   *   Class object.
   *
   * @return array
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
      $classObject->lineItem = CRM_Inventory_Utils::commonRetrieveAll('CRM_Price_BAO_LineItem', [$column => $value], FALSE);
    }
    else {
      $classObject->lineItem = [];
    }
    return $classObject->lineItem;
  }

}
