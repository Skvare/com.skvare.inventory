<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/InventoryReferrals.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:bdc3ce18c1a5dda9bacf0e42633abbb3)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the InventoryReferrals entity.
 */
class CRM_Inventory_DAO_InventoryReferrals extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory_referrals';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique InventoryReferrals ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * FK to Membership
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $creator_id;

  /**
   * FK to Membership
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $consumer_id;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_date;

  /**
   * Membership End Date Before referral
   *
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $before_end_date;

  /**
   * Membership End Date after referral
   *
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $after_end_date;

  /**
   * @var string
   *   (SQL type: varchar(100))
   *   Note that values will be retrieved from the database as a string.
   */
  public $referral_code;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_inventory_referrals';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventory Referralses') : E::ts('Inventory Referrals');
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
          'description' => E::ts('Unique InventoryReferrals ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_referrals.id',
          'table_name' => 'civicrm_inventory_referrals',
          'entity' => 'InventoryReferrals',
          'bao' => 'CRM_Inventory_DAO_InventoryReferrals',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'inventory_referral_created_id' => [
          'name' => 'creator_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Creator ID'),
          'description' => E::ts('FK to Membership'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_referrals.creator_id',
          'table_name' => 'civicrm_inventory_referrals',
          'entity' => 'InventoryReferrals',
          'bao' => 'CRM_Inventory_DAO_InventoryReferrals',
          'localizable' => 0,
          'FKClassName' => 'CRM_Member_DAO_Membership',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Created Membership"),
          ],
          'add' => NULL,
        ],
        'inventory_referral_consumer_id' => [
          'name' => 'consumer_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Consumer ID'),
          'description' => E::ts('FK to Membership'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_referrals.consumer_id',
          'table_name' => 'civicrm_inventory_referrals',
          'entity' => 'InventoryReferrals',
          'bao' => 'CRM_Inventory_DAO_InventoryReferrals',
          'localizable' => 0,
          'FKClassName' => 'CRM_Member_DAO_Membership',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Consumer Membership"),
          ],
          'add' => NULL,
        ],
        'inventory_referral_created_date' => [
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
          'where' => 'civicrm_inventory_referrals.created_date',
          'headerPattern' => '/Created(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_inventory_referrals',
          'entity' => 'InventoryReferrals',
          'bao' => 'CRM_Inventory_DAO_InventoryReferrals',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_referral_before_end_date' => [
          'name' => 'before_end_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Before End Date'),
          'description' => E::ts('Membership End Date Before referral'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_referrals.before_end_date',
          'headerPattern' => '/before_end(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_referrals',
          'entity' => 'InventoryReferrals',
          'bao' => 'CRM_Inventory_DAO_InventoryReferrals',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_referral_after_end_date' => [
          'name' => 'after_end_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('After End Date'),
          'description' => E::ts('Membership End Date after referral'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_referrals.after_end_date',
          'headerPattern' => '/after_end(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_referrals',
          'entity' => 'InventoryReferrals',
          'bao' => 'CRM_Inventory_DAO_InventoryReferrals',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_referral_code' => [
          'name' => 'referral_code',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Referral Code'),
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
          'where' => 'civicrm_inventory_referrals.referral_code',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_referrals',
          'entity' => 'InventoryReferrals',
          'bao' => 'CRM_Inventory_DAO_InventoryReferrals',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory_referrals', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory_referrals', $prefix, []);
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
