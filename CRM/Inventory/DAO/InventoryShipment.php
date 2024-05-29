<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.skvare.inventory/xml/schema/CRM/Inventory/InventoryShipment.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:9304e9cb7e6cc76a962bbb126aa898b9)
 */
use CRM_Inventory_ExtensionUtil as E;

/**
 * Database access object for the InventoryShipment entity.
 */
class CRM_Inventory_DAO_InventoryShipment extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_inventory_shipment';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique InventoryShipment ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * FK to Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $contact_id;

  /**
   * When was the shipment was created.
   *
   * @var string|null
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_date;

  /**
   * FK to Contact ID of person under whose credentials this data modification was made.
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $modified_id;

  /**
   * @var string|null
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $updated_date;

  /**
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $shipped_at;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_shipped;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_finished;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_inventory_shipment';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Inventory Shipments') : E::ts('Inventory Shipment');
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
          'description' => E::ts('Unique InventoryShipment ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_shipment.id',
          'table_name' => 'civicrm_inventory_shipment',
          'entity' => 'InventoryShipment',
          'bao' => 'CRM_Inventory_DAO_InventoryShipment',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'contact_id' => [
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Created By'),
          'description' => E::ts('FK to Contact'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_shipment.contact_id',
          'table_name' => 'civicrm_inventory_shipment',
          'entity' => 'InventoryShipment',
          'bao' => 'CRM_Inventory_DAO_InventoryShipment',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'label' => E::ts("Created By"),
          ],
          'add' => NULL,
        ],
        'created_date' => [
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Created Date'),
          'description' => E::ts('When was the shipment was created.'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_shipment.created_date',
          'headerPattern' => '/created(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_shipment',
          'entity' => 'InventoryShipment',
          'bao' => 'CRM_Inventory_DAO_InventoryShipment',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
            'label' => E::ts("Created Date"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'modified_id' => [
          'name' => 'modified_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Modified By Contact ID'),
          'description' => E::ts('FK to Contact ID of person under whose credentials this data modification was made.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_shipment.modified_id',
          'table_name' => 'civicrm_inventory_shipment',
          'entity' => 'InventoryShipment',
          'bao' => 'CRM_Inventory_DAO_InventoryShipment',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'label' => E::ts("Modified By"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'updated_date' => [
          'name' => 'updated_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Updated date'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_shipment.updated_date',
          'headerPattern' => '/updated(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_inventory_shipment',
          'entity' => 'InventoryShipment',
          'bao' => 'CRM_Inventory_DAO_InventoryShipment',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
            'label' => E::ts("Modified Date"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'shipped_at' => [
          'name' => 'shipped_at',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Shipped At'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_inventory_shipment.shipped_at',
          'headerPattern' => '/shipped(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_inventory_shipment',
          'entity' => 'InventoryShipment',
          'bao' => 'CRM_Inventory_DAO_InventoryShipment',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'inventory_shipment_is_shipped' => [
          'name' => 'is_shipped',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is shipped?'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_shipment.is_shipped',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_inventory_shipment',
          'entity' => 'InventoryShipment',
          'bao' => 'CRM_Inventory_DAO_InventoryShipment',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'inventory_shipment_is_finished' => [
          'name' => 'is_finished',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Finished'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => TRUE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_inventory_shipment.is_finished',
          'export' => TRUE,
          'default' => '0',
          'table_name' => 'civicrm_inventory_shipment',
          'entity' => 'InventoryShipment',
          'bao' => 'CRM_Inventory_DAO_InventoryShipment',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'inventory_shipment', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'inventory_shipment', $prefix, []);
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
      'index_created_date' => [
        'name' => 'index_created_date',
        'field' => [
          0 => 'created_date',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_inventory_shipment::0::created_date',
      ],
      'index_updated_date' => [
        'name' => 'index_updated_date',
        'field' => [
          0 => 'updated_date',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_inventory_shipment::0::updated_date',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
