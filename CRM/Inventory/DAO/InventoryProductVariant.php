<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/InventoryProductVariant.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:4fda45367863498b927792b3a5350b15)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the InventoryProductVariant entity.
 */
class CRM_Inventory_DAO_InventoryProductVariant extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory_product_variant';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique Inventory Product Variant ID
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
   * FK to Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $contact_id;

  /**
   * Phone number linked with device.
   *
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_variant_phone_number;

  /**
   * e.g IMEI (International Mobile Equipment Identity) number .
   *
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_variant_unique_id;

  /**
   * Product Variant details.
   *
   * @var string|null
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $product_variant_details;

  /**
   * A private note only visible to admins.
   *
   * @var string|null
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $note;

  /**
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $status;

  /**
   * Optional Product.
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $replaced_product_id;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_replaced;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $replaced_date;

  /**
   * Membership ID Associated with product.
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $membership_id;

  /**
   * Added into system on specific order number.
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $order_number;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $shipped_on;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $warranty_start_date;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $warranty_end_date;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $expire_on;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_at;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $updated_at;

  /**
   * Sales ID Associated with sales tables.
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $sales_id;

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
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_primary;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_discontinued;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_active;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_suspended;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_problem;

  /**
   * Memo for device.
   *
   * @var string
   *   (SQL type: varchar(256))
   *   Note that values will be retrieved from the database as a string.
   */
  public $memo;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_inventory_product_variant';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventory Product Variants') : E::ts('Inventory Product Variant');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'inventory_product_variant_id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ID'),
          'description' => E::ts('Unique Inventory Product Variant ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_variant.id',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
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
          'where' => 'civicrm_inventory_product_variant.product_id',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryProduct',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Product Model"),
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_contact_id' => [
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
          'where' => 'civicrm_inventory_product_variant.contact_id',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Contact"),
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_phone_number' => [
          'name' => 'product_variant_phone_number',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Phone Number'),
          'description' => E::ts('Phone number linked with device.'),
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
          'where' => 'civicrm_inventory_product_variant.product_variant_phone_number',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.63',
        ],
        'inventory_product_variant_unique_id' => [
          'name' => 'product_variant_unique_id',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Identifier (IMEI/MEID)'),
          'description' => E::ts('e.g IMEI (International Mobile Equipment Identity) number .'),
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
          'where' => 'civicrm_inventory_product_variant.product_variant_unique_id',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.63',
        ],
        'inventory_product_variant_details' => [
          'name' => 'product_variant_details',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Product Variant Details'),
          'description' => E::ts('Product Variant details.'),
          'rows' => 4,
          'cols' => 60,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.product_variant_details',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'TextArea',
          ],
          'add' => '5.63',
        ],
        'inventory_product_variable_note' => [
          'name' => 'note',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Note'),
          'description' => E::ts('A private note only visible to admins.'),
          'rows' => 4,
          'cols' => 60,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.note',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'TextArea',
          ],
          'add' => '5.63',
        ],
        'inventory_product_variant_status' => [
          'name' => 'status',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Product Status'),
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
          'where' => 'civicrm_inventory_product_variant.status',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Inventory_Utils::productVariantStatus',
          ],
          'add' => '5.63',
        ],
        'replaced_product_id' => [
          'name' => 'replaced_product_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Replaced Product ID'),
          'description' => E::ts('Optional Product.'),
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_variant.replaced_product_id',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Replaced Product"),
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_is_replaced' => [
          'name' => 'is_replaced',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Replaced'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_variant.is_replaced',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_replaced_date' => [
          'name' => 'replaced_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Replacement Date'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.replaced_date',
          'headerPattern' => '/replace(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_membership_id' => [
          'name' => 'membership_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Membership ID'),
          'description' => E::ts('Membership ID Associated with product.'),
          'required' => FALSE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.membership_id',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'FKClassName' => 'CRM_Member_DAO_Membership',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Membership ID"),
          ],
          'add' => '5.63',
        ],
        'inventory_product_variant_order_number' => [
          'name' => 'order_number',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Order ID'),
          'description' => E::ts('Added into system on specific order number.'),
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_variant.order_number',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_shipped_on' => [
          'name' => 'shipped_on',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Shipped on'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.shipped_on',
          'headerPattern' => '/shipped on(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_warranty_start_date' => [
          'name' => 'warranty_start_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Warranty start on'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.warranty_start_date',
          'headerPattern' => '/warranty(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_warranty_end_date' => [
          'name' => 'warranty_end_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Warranty end on'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.warranty_end_date',
          'headerPattern' => '/warranty(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_expire_on' => [
          'name' => 'expire_on',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Expire On'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.expire_on',
          'headerPattern' => '/warranty(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_created_at' => [
          'name' => 'created_at',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Created Date'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.created_at',
          'headerPattern' => '/created(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_updated_at' => [
          'name' => 'updated_at',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Updated Date'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.updated_at',
          'headerPattern' => '/created(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_product_sales_id' => [
          'name' => 'sales_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Sales ID'),
          'description' => E::ts('Sales ID Associated with sales tables.'),
          'required' => FALSE,
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_product_variant.sales_id',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'FKClassName' => 'CRM_Inventory_DAO_InventorySales',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Sales ID"),
          ],
          'add' => '5.63',
        ],
        'inventory_product_variant_warehouse_row' => [
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
          'where' => 'civicrm_inventory_product_variant.row',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Row in Warehouse"),
          ],
          'add' => '5.63',
        ],
        'inventory_product_variant_warehouse_shelf' => [
          'name' => 'shelf',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Warehouse shelf'),
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
          'where' => 'civicrm_inventory_product_variant.shelf',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
            'label' => E::ts("Warehouse shelf"),
          ],
          'pseudoconstant' => [
            'optionGroupName' => 'warehouse_shelf',
            'optionEditPath' => 'civicrm/admin/options/warehouse_shelf',
          ],
          'add' => '5.63',
        ],
        'inventory_product_variant_is_primary' => [
          'name' => 'is_primary',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Primary Device'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_variant.is_primary',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_is_discontinued' => [
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
          'where' => 'civicrm_inventory_product_variant.is_discontinued',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Active'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_variant.is_active',
          'export' => TRUE,
          'default' => '1',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_is_suspended' => [
          'name' => 'is_suspended',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Suspended'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_variant.is_suspended',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'inventory_product_variant_is_problem' => [
          'name' => 'is_problem',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Problem'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_product_variant.is_problem',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'inventory_product_memo' => [
          'name' => 'memo',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Memo'),
          'description' => E::ts('Memo for device.'),
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
          'where' => 'civicrm_inventory_product_variant.memo',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_product_variant',
          'entity' => 'InventoryProductVariant',
          'bao' => 'CRM_Inventory_DAO_InventoryProductVariant',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory_product_variant', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory_product_variant', $prefix, []);
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
    $indices = [
      'UI_product_variant_unique_id' => [
        'name' => 'UI_product_variant_unique_id',
        'field' => [
          0 => 'product_variant_unique_id',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_inventory_product_variant::1::product_variant_unique_id',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
