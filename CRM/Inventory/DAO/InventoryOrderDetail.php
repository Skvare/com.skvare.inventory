<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/InventoryOrderDetail.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:a77cde4564b5b892559c6e5c0e57e202)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the InventoryOrderDetail entity.
 */
class CRM_Inventory_DAO_InventoryOrderDetail extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory_order_detail';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique InventoryOrderDetail ID
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
   * FK to Provider
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $provider_id;

  /**
   * FK to Warehouse
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $warehouse_id;

  /**
   * Order quantiy to provider
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
    $this->__table = 'civicrm_inventory_order_detail';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventory Order Details') : E::ts('Inventory Order Detail');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'order_id', 'civicrm_inventory_order', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'product_variant_id', 'civicrm_inventory_product_variant', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'provider_id', 'civicrm_inventory_provider', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'warehouse_id', 'civicrm_inventory_warehouse', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
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
          'description' => E::ts('Unique InventoryOrderDetail ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_order_detail.id',
          'table_name' => 'civicrm_inventory_order_detail',
          'entity' => 'InventoryOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryOrderDetail',
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
          'where' => 'civicrm_inventory_order_detail.order_id',
          'table_name' => 'civicrm_inventory_order_detail',
          'entity' => 'InventoryOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryOrderDetail',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryOrder',
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
          'where' => 'civicrm_inventory_order_detail.product_variant_id',
          'table_name' => 'civicrm_inventory_order_detail',
          'entity' => 'InventoryOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryOrderDetail',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'add' => NULL,
        ],
        'provider_id' => [
          'name' => 'provider_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Provider ID'),
          'description' => E::ts('FK to Provider'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_order_detail.provider_id',
          'table_name' => 'civicrm_inventory_order_detail',
          'entity' => 'InventoryOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryOrderDetail',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryProvider',
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
          'where' => 'civicrm_inventory_order_detail.warehouse_id',
          'table_name' => 'civicrm_inventory_order_detail',
          'entity' => 'InventoryOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryOrderDetail',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryWarehouse',
          'add' => NULL,
        ],
        'inventory_orderdetail_order_quantity' => [
          'name' => 'order_quantity',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Order Quantity'),
          'description' => E::ts('Order quantiy to provider'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_order_detail.order_quantity',
          'table_name' => 'civicrm_inventory_order_detail',
          'entity' => 'InventoryOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryOrderDetail',
          'localizable' => 0,
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
          'where' => 'civicrm_inventory_order_detail.expected_date',
          'headerPattern' => '/expected(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_order_detail',
          'entity' => 'InventoryOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryOrderDetail',
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
          'where' => 'civicrm_inventory_order_detail.actual_date',
          'headerPattern' => '/atual(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_order_detail',
          'entity' => 'InventoryOrderDetail',
          'bao' => 'CRM_Inventory_DAO_InventoryOrderDetail',
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
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory_order_detail', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory_order_detail', $prefix, []);
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