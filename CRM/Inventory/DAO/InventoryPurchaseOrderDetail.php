<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/InventoryPurchaseOrderDetail.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:cdb4685565ee5c51ad7082e14015371b)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the InventoryPurchaseOrderDetail entity.
 */
class CRM_Inventory_DAO_InventoryPurchaseOrderDetail extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory_purchase_order_detail';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique InventoryPurchaseOrderDetail ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * FK to Order
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $order_id;

  /**
   * FK to Product Variant
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_variant_id;

  /**
   * FK to Supplier
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $supplier_id;

  /**
   * FK to Warehouse
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $warehouse_id;

  /**
   * Order quantiy to supplier
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $order_quantity;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $expected_date;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $actual_date;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_inventory_purchase_order_detail';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventory Purchase Order Details') : E::ts('Inventory Purchase Order Detail');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ID'),
          'description' => E::ts('Unique InventoryPurchaseOrderDetail ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_purchase_order_detail.id',
          'table_name' => 'civicrm_inventory_purchase_order_detail',
          'entity' => 'InventoryPurchaseOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryPurchaseOrderDetail',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'order_id' => [
          'name' => 'order_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Order ID'),
          'description' => E::ts('FK to Order'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_purchase_order_detail.order_id',
          'table_name' => 'civicrm_inventory_purchase_order_detail',
          'entity' => 'InventoryPurchaseOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryPurchaseOrderDetail',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryPurchaseOrder',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Order ID"),
          ],
          'add' => NULL,
        ],
        'product_variant_id' => [
          'name' => 'product_variant_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Product Variant ID'),
          'description' => E::ts('FK to Product Variant'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_purchase_order_detail.product_variant_id',
          'table_name' => 'civicrm_inventory_purchase_order_detail',
          'entity' => 'InventoryPurchaseOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryPurchaseOrderDetail',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Product Variant"),
          ],
          'add' => NULL,
        ],
        'supplier_id' => [
          'name' => 'supplier_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Supplier ID'),
          'description' => E::ts('FK to Supplier'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_purchase_order_detail.supplier_id',
          'table_name' => 'civicrm_inventory_purchase_order_detail',
          'entity' => 'InventoryPurchaseOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryPurchaseOrderDetail',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventorySupplier',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Supplier ID"),
          ],
          'add' => NULL,
        ],
        'warehouse_id' => [
          'name' => 'warehouse_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Warehouse ID'),
          'description' => E::ts('FK to Warehouse'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_purchase_order_detail.warehouse_id',
          'table_name' => 'civicrm_inventory_purchase_order_detail',
          'entity' => 'InventoryPurchaseOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryPurchaseOrderDetail',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryWarehouse',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Warehouse ID"),
          ],
          'add' => NULL,
        ],
        'inventory_orderdetail_order_quantity' => [
          'name' => 'order_quantity',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Order Quantity'),
          'description' => E::ts('Order quantiy to supplier'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_purchase_order_detail.order_quantity',
          'table_name' => 'civicrm_inventory_purchase_order_detail',
          'entity' => 'InventoryPurchaseOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryPurchaseOrderDetail',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'add' => NULL,
        ],
        'expected_date' => [
          'name' => 'expected_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Expected Date'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_purchase_order_detail.expected_date',
          'headerPattern' => '/expected(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_purchase_order_detail',
          'entity' => 'InventoryPurchaseOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryPurchaseOrderDetail',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'actual_date' => [
          'name' => 'actual_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Actual Date'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_purchase_order_detail.actual_date',
          'headerPattern' => '/atual(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_purchase_order_detail',
          'entity' => 'InventoryPurchaseOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryPurchaseOrderDetail',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory_purchase_order_detail', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory_purchase_order_detail', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
