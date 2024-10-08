<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/InventoryCategory.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:95edec63903a69f3152395e5ba64ec19)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the InventoryCategory entity.
 */
class CRM_Inventory_DAO_InventoryCategory extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory_category';

  /**
   * Field to show when displaying a record.
   *
   * @var string
   */
  public static $_labelField = 'title';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique InventoryCategory ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * FK to Parent Category
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $parent_id;

  /**
   * @var string
   *   (SQL type: varchar(256))
   *   Note that values will be retrieved from the database as a string.
   */
  public $title;

  /**
   * @var string
   *   (SQL type: varchar(256))
   *   Note that values will be retrieved from the database as a string.
   */
  public $meta_title;

  /**
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $slug;

  /**
   * Category Content.
   *
   * @var string|null
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $content;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_inventory_category';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventory Categories') : E::ts('Inventory Category');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'inventory_category_id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ID'),
          'description' => E::ts('Unique InventoryCategory ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_category.id',
          'table_name' => 'civicrm_inventory_category',
          'entity' => 'InventoryCategory',
          'bao' => 'CRM_Inventory_DAO_InventoryCategory',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'inventory_category_parent_id' => [
          'name' => 'parent_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Parent ID'),
          'description' => E::ts('FK to Parent Category'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_category.parent_id',
          'table_name' => 'civicrm_inventory_category',
          'entity' => 'InventoryCategory',
          'bao' => 'CRM_Inventory_DAO_InventoryCategory',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryCategory',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Parent ID"),
          ],
          'add' => NULL,
        ],
        'inventory_category_title' => [
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Title'),
          'required' => TRUE,
          'maxlength' => 256,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_category.title',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_category',
          'entity' => 'InventoryCategory',
          'bao' => 'CRM_Inventory_DAO_InventoryCategory',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.63',
        ],
        'inventory_category_meta_title' => [
          'name' => 'meta_title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Meta Title'),
          'required' => TRUE,
          'maxlength' => 256,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_category.meta_title',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_category',
          'entity' => 'InventoryCategory',
          'bao' => 'CRM_Inventory_DAO_InventoryCategory',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.63',
        ],
        'inventory_category_slug' => [
          'name' => 'slug',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Slug'),
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
          'where' => 'civicrm_inventory_category.slug',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_category',
          'entity' => 'InventoryCategory',
          'bao' => 'CRM_Inventory_DAO_InventoryCategory',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.63',
        ],
        'inventory_category_content' => [
          'name' => 'content',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('content'),
          'description' => E::ts('Category Content.'),
          'rows' => 4,
          'cols' => 60,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_category.content',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_category',
          'entity' => 'InventoryCategory',
          'bao' => 'CRM_Inventory_DAO_InventoryCategory',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory_category', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory_category', $prefix, []);
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
