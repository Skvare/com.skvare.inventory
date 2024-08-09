<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/Inventory.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:a9446b22c698c3e7ccd48d4fecf9df4f)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the Inventory entity.
 */
class CRM_Inventory_DAO_Inventory extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Inventory ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_variant_sku_code;

  /**
   * FK to Warehouse
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $warehouse_id;

  /**
   * The quantity on hand.
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $quantity_available;

  /**
   * The minimum number of units required to ensure no shortages occur at this warehouse.
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $minimum_quantity_stock_level;

  /**
   * The maximum number of units desired in stock, i.e. to avoid overstocking.
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $maximum_quantity_stock_level;

  /**
   * The minimum number of units required to ensure no shortages occur at this warehouse.
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $reorder_point;

  /**
   * Use to locate the item in warehouse
   *
   * @var string
   *   (SQL type: varchar(256))
   *   Note that values will be retrieved from the database as a string.
   */
  public $row;

  /**
   * Use to locate the item in warehouse
   *
   * @var string
   *   (SQL type: varchar(256))
   *   Note that values will be retrieved from the database as a string.
   */
  public $shelf;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_inventory';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventories') : E::ts('Inventory');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'inventory_id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ID'),
          'description' => E::ts('Inventory ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory.id',
          'table_name' => 'civicrm_inventory',
          'entity' => 'Inventory',
          'bao' => 'CRM_Inventory_DAO_Inventory',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'inventory_product_variant_sku_code' => [
          'name' => 'product_variant_sku_code',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Product Variant Code'),
          'required' => FALSE,
          'maxlength' => 100,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory.product_variant_sku_code',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory',
          'entity' => 'Inventory',
          'bao' => 'CRM_Inventory_DAO_Inventory',
          'localizable' => 0,
          'add' => '5.63',
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
          'where' => 'civicrm_inventory.warehouse_id',
          'table_name' => 'civicrm_inventory',
          'entity' => 'Inventory',
          'bao' => 'CRM_Inventory_DAO_Inventory',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryWarehouse',
          'add' => NULL,
        ],
        'inventory_quantity_available' => [
          'name' => 'quantity_available',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Quantity Available'),
          'description' => E::ts('The quantity on hand.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory.quantity_available',
          'table_name' => 'civicrm_inventory',
          'entity' => 'Inventory',
          'bao' => 'CRM_Inventory_DAO_Inventory',
          'localizable' => 0,
          'add' => NULL,
        ],
        'inventory_minimum_quantity_stock_level' => [
          'name' => 'minimum_quantity_stock_level',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Minimum Stock Level'),
          'description' => E::ts('The minimum number of units required to ensure no shortages occur at this warehouse.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory.minimum_quantity_stock_level',
          'table_name' => 'civicrm_inventory',
          'entity' => 'Inventory',
          'bao' => 'CRM_Inventory_DAO_Inventory',
          'localizable' => 0,
          'add' => NULL,
        ],
        'inventory_maximum_quantity_stock_level' => [
          'name' => 'maximum_quantity_stock_level',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Maximum Stock Level'),
          'description' => E::ts('The maximum number of units desired in stock, i.e. to avoid overstocking.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory.maximum_quantity_stock_level',
          'table_name' => 'civicrm_inventory',
          'entity' => 'Inventory',
          'bao' => 'CRM_Inventory_DAO_Inventory',
          'localizable' => 0,
          'add' => NULL,
        ],
        'inventory_reorder_point' => [
          'name' => 'reorder_point',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ReOrder Point'),
          'description' => E::ts('The minimum number of units required to ensure no shortages occur at this warehouse.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory.reorder_point',
          'table_name' => 'civicrm_inventory',
          'entity' => 'Inventory',
          'bao' => 'CRM_Inventory_DAO_Inventory',
          'localizable' => 0,
          'add' => NULL,
        ],
        'inventory_warehouse_row' => [
          'name' => 'row',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Row in Warehouse'),
          'description' => E::ts('Use to locate the item in warehouse'),
          'required' => FALSE,
          'maxlength' => 256,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory.row',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory',
          'entity' => 'Inventory',
          'bao' => 'CRM_Inventory_DAO_Inventory',
          'localizable' => 0,
          'add' => '5.63',
        ],
        'inventory_warehouse_shelf' => [
          'name' => 'shelf',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('shelf Warehouse'),
          'description' => E::ts('Use to locate the item in warehouse'),
          'required' => FALSE,
          'maxlength' => 256,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory.shelf',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory',
          'entity' => 'Inventory',
          'bao' => 'CRM_Inventory_DAO_Inventory',
          'localizable' => 0,
          'add' => '5.63',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory', $prefix, []);
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
