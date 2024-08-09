<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/InventoryProductMembership.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:6dc2c35b339485c6c8e17eef68b82db7)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the InventoryProductMembership entity.
 */
class CRM_Inventory_DAO_InventoryProductMembership extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory_product_membership';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique Inventory Product Membership ID
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
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_name;

  /**
   * Membership Type Associated with product.
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $membership_type_id;

  /**
   * FK to Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $contact_id;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_inventory_product_membership';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventory Product Memberships') : E::ts('Inventory Product Membership');
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
          'description' => E::ts('Unique Inventory Product Membership ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_membership.id',
          'table_name' => 'civicrm_inventory_product_membership',
          'entity' => 'InventoryProductMembership',
          'bao' => 'CRM_Inventory_DAO_InventoryProductMembership',
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
          'title' => E::ts('Product Variant SKU Code'),
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
          'where' => 'civicrm_inventory_product_membership.product_variant_sku_code',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_membership',
          'entity' => 'InventoryProductMembership',
          'bao' => 'CRM_Inventory_DAO_InventoryProductMembership',
          'localizable' => 0,
          'add' => '5.63',
        ],
        'inventory_product_name' => [
          'name' => 'product_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Product Name'),
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
          'where' => 'civicrm_inventory_product_membership.product_name',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_membership',
          'entity' => 'InventoryProductMembership',
          'bao' => 'CRM_Inventory_DAO_InventoryProductMembership',
          'localizable' => 0,
          'add' => '5.63',
        ],
        'inventory_membership_type_id' => [
          'name' => 'membership_type_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Membership ID'),
          'description' => E::ts('Membership Type Associated with product.'),
          'required' => TRUE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_membership.membership_type_id',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_membership',
          'entity' => 'InventoryProductMembership',
          'bao' => 'CRM_Inventory_DAO_InventoryProductMembership',
          'localizable' => 0,
          'FKClassName' => 'CRM_Member_DAO_MembershipType',
          'html' => [
            'label' => E::ts("Membership Type"),
          ],
          'add' => '5.63',
        ],
        'contact_id' => [
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Contact ID'),
          'description' => E::ts('FK to Contact'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_membership.contact_id',
          'table_name' => 'civicrm_inventory_product_membership',
          'entity' => 'InventoryProductMembership',
          'bao' => 'CRM_Inventory_DAO_InventoryProductMembership',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory_product_membership', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory_product_membership', $prefix, []);
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
