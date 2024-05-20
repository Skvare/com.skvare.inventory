<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/InventoryProduct.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:7ad47ec1dc228ac201aae642bcaf6800)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the InventoryProduct entity.
 */
class CRM_Inventory_DAO_InventoryProduct extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory_product';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique InventoryProduct ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Product Code SKU.
   *
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_code;

  /**
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $external_code;

  /**
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_name;

  /**
   * @var string
   *   (SQL type: varchar(512))
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_description;

  /**
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_brand;

  /**
   * Product details.
   *
   * @var string|null
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_note;

  /**
   * FK to Category
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_category_id;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_disable;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_discontinued;

  /**
   * File url.
   *
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $image_actual;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_inventory_product';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventory Products') : E::ts('Inventory Product');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'inventory_product_id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ID'),
          'description' => E::ts('Unique InventoryProduct ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product.id',
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'inventory_product_code' => [
          'name' => 'product_code',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Product Code'),
          'description' => E::ts('Product Code SKU.'),
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
          'where' => 'civicrm_inventory_product.product_code',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
          'localizable' => 0,
          'add' => '5.63',
        ],
        'inventory_product_external_code' => [
          'name' => 'external_code',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('External Code'),
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
          'where' => 'civicrm_inventory_product.external_code',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
          'localizable' => 0,
          'add' => '5.63',
        ],
        'inventory_product_name' => [
          'name' => 'product_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Product Name'),
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
          'where' => 'civicrm_inventory_product.product_name',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
          'localizable' => 0,
          'add' => '5.63',
        ],
        'inventory_product_description' => [
          'name' => 'product_description',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Product Description'),
          'required' => FALSE,
          'maxlength' => 512,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product.product_description',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
          'localizable' => 0,
          'add' => '5.63',
        ],
        'inventory_product_brand' => [
          'name' => 'product_brand',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Product Brand'),
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
          'where' => 'civicrm_inventory_product.product_brand',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
          'localizable' => 0,
          'add' => '5.63',
        ],
        'inventory_product_note' => [
          'name' => 'product_note',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Product Note'),
          'description' => E::ts('Product details.'),
          'rows' => 4,
          'cols' => 60,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product.product_note',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
          'localizable' => 0,
          'html' => [
            'type' => 'TextArea',
          ],
          'add' => '5.63',
        ],
        'inventory_product_category_id' => [
          'name' => 'product_category_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Product Category'),
          'description' => E::ts('FK to Category'),
          'required' => TRUE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product.product_category_id',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryCategory',
          'add' => NULL,
        ],
        'inventory_product_is_disable' => [
          'name' => 'is_disable',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Disable'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product.is_disable',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'inventory_product_is_discontinued' => [
          'name' => 'is_discontinued',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Discontinued'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product.is_discontinued',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'inventory_product_image' => [
          'name' => 'image_actual',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Image'),
          'description' => E::ts('File url.'),
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
          'where' => 'civicrm_inventory_product.image_actual',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product',
          'entity' => 'InventoryProduct',
          'bao' => 'CRM_Inventory_DAO_InventoryProduct',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory_product', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory_product', $prefix, []);
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
