<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/InventoryProductMeta.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:a02a479ee6bde69cc16bff6d7d7f3c67)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the InventoryProductMeta entity.
 */
class CRM_Inventory_DAO_InventoryProductMeta extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory_product_meta';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique InventoryProductMeta ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * FK to Product
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_id;

  /**
   * @var string
   *   (SQL type: varchar(50))
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_meta_key;

  /**
   * Product Meta Content.
   *
   * @var string|null
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_meta_content;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_inventory_product_meta';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventory Product Metas') : E::ts('Inventory Product Meta');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'inventory_product_meta_id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ID'),
          'description' => E::ts('Unique InventoryProductMeta ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_meta.id',
          'table_name' => 'civicrm_inventory_product_meta',
          'entity' => 'InventoryProductMeta',
          'bao' => 'CRM_Inventory_DAO_InventoryProductMeta',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'product_id' => [
          'name' => 'product_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Product ID'),
          'description' => E::ts('FK to Product'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_meta.product_id',
          'table_name' => 'civicrm_inventory_product_meta',
          'entity' => 'InventoryProductMeta',
          'bao' => 'CRM_Inventory_DAO_InventoryProductMeta',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryProduct',
          'add' => NULL,
        ],
        'inventory_product_meta_key' => [
          'name' => 'product_meta_key',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Product Key'),
          'required' => TRUE,
          'maxlength' => 50,
          'size' => CRM_Utils_Type::BIG,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_meta.product_meta_key',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_meta',
          'entity' => 'InventoryProductMeta',
          'bao' => 'CRM_Inventory_DAO_InventoryProductMeta',
          'localizable' => 0,
          'add' => '5.63',
        ],
        'inventory_product_meta_content' => [
          'name' => 'product_meta_content',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Product Content'),
          'description' => E::ts('Product Meta Content.'),
          'rows' => 4,
          'cols' => 60,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_meta.product_meta_content',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_meta',
          'entity' => 'InventoryProductMeta',
          'bao' => 'CRM_Inventory_DAO_InventoryProductMeta',
          'localizable' => 0,
          'html' => [
            'type' => 'TextArea',
          ],
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory_product_meta', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory_product_meta', $prefix, []);
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
