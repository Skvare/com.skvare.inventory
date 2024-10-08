<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/InventoryWarehouseTransfer.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:6500e1e0756dca456124a0e6cfab4f9b)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the InventoryWarehouseTransfer entity.
 */
class CRM_Inventory_DAO_InventoryWarehouseTransfer extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory_warehouse_transfer';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique Inventory Warehouse Transfer ID
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
  public $lot_id;

  /**
   * FK to Product Variant
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_variant_id;

  /**
   * FK to Warehouse
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $from_warehouse_id;

  /**
   * FK to Warehouse
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $to_warehouse_id;

  /**
   * FK to Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $from_contact_id;

  /**
   * FK to Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $to_contact_id;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_date;

  /**
   * IN = into location, OUT = OUT of location
   *
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $status_id;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $status_date;

  /**
   * The quantity sent.
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $from_stock_quantity;

  /**
   * The quantity Received.
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $received_stock_quantity;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_inventory_warehouse_transfer';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventory Warehouse Transfers') : E::ts('Inventory Warehouse Transfer');
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
          'description' => E::ts('Unique Inventory Warehouse Transfer ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_warehouse_transfer.id',
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'inventory_warehouse_transfer_lot_id' => [
          'name' => 'lot_id',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Product Lot ID'),
          'required' => TRUE,
          'maxlength' => 100,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_warehouse_transfer.lot_id',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.63',
        ],
        'inventory_warehouse_transfer_product_variant_id' => [
          'name' => 'product_variant_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Product Variant'),
          'description' => E::ts('FK to Product Variant'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_warehouse_transfer.product_variant_id',
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Product Variant"),
          ],
          'add' => NULL,
        ],
        'inventory_warehouse_transfer_from_warehouse_id' => [
          'name' => 'from_warehouse_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('From Warehouse ID'),
          'description' => E::ts('FK to Warehouse'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_warehouse_transfer.from_warehouse_id',
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryWarehouse',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("From Warehouse"),
          ],
          'add' => NULL,
        ],
        'inventory_warehouse_transfer_to_warehouse_id' => [
          'name' => 'to_warehouse_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('To Warehouse ID'),
          'description' => E::ts('FK to Warehouse'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_warehouse_transfer.to_warehouse_id',
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryWarehouse',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("To Warehouse"),
          ],
          'add' => NULL,
        ],
        'inventory_warehouse_transfer_from_contact_id' => [
          'name' => 'from_contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Operation performed By'),
          'description' => E::ts('FK to Contact'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_warehouse_transfer.from_contact_id',
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Operation performed By"),
          ],
          'add' => NULL,
        ],
        'inventory_warehouse_transfer_to_contact_id' => [
          'name' => 'to_contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Received By'),
          'description' => E::ts('FK to Contact'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_warehouse_transfer.to_contact_id',
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Received By"),
          ],
          'add' => NULL,
        ],
        'inventory_warehouse_transfer_created_date' => [
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Created Date'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_warehouse_transfer.created_date',
          'headerPattern' => '/action(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_warehouse_transfer_status_id' => [
          'name' => 'status_id',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Transaction Status'),
          'description' => E::ts('IN = into location, OUT = OUT of location'),
          'required' => TRUE,
          'maxlength' => 100,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_warehouse_transfer.status_id',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.63',
        ],
        'inventory_warehouse_transfer_status_date' => [
          'name' => 'status_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Status Date'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_warehouse_transfer.status_date',
          'headerPattern' => '/action(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'from_stock_quantity' => [
          'name' => 'from_stock_quantity',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Product Stock Quantity Sent'),
          'description' => E::ts('The quantity sent.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_warehouse_transfer.from_stock_quantity',
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'add' => NULL,
        ],
        'received_stock_quantity' => [
          'name' => 'received_stock_quantity',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Product Stock Quantity Received'),
          'description' => E::ts('The quantity Received.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_warehouse_transfer.received_stock_quantity',
          'table_name' => 'civicrm_inventory_warehouse_transfer',
          'entity' => 'InventoryWarehouseTransfer',
          'bao' => 'CRM_Inventory_DAO_InventoryWarehouseTransfer',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory_warehouse_transfer', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory_warehouse_transfer', $prefix, []);
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
