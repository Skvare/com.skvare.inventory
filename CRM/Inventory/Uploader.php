<?php

/**
 * @file
 */

use Civi\Api4\InventoryProductChangelog;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/*
Here lies all the code for processing spreadsheets from Mobile Citizen.
There are three types spreadsheets we get:

1. Device manifests, that come with a new shipment of devices.

2. Device replacements, a list of devices that members have replaced because
they were under warranty.

3. Device status, a list of all the current devices known to Mobile Citizen.

 */
/**
 * CRM_Inventory_Uploader.
 *
 * Uploader functions.
 */
class CRM_Inventory_Uploader {
  use CRM_Inventory;

  // Even after we send them device updates, they often don't show.
  const WINDOW = 7;

  /**
   * Error Message.
   *
   * @var array
   */
  public array $errors = [];
  /**
   * Warning Message.
   *
   * @var array
   */
  public array $warnings = [];

  /**
   * General Message.
   *
   * @var array
   */
  public array $messages = [];

  /**
   * Boolean type.
   *
   * @var false|mixed
   */
  public mixed $dryRun;

  public $workbook;

  /**
   * Sheet Name.
   *
   * @var string
   */
  public string $sheetName;

  /**
   * Label.
   *
   * @var mixed|null
   */
  public mixed $label;

  /**
   * Change date.
   *
   * @var DateTime|false|null
   */
  public bool|null|DateTime $changeDate;

  /**
   * @var array
   */
  public array $modelIds;

  /**
   * To be overridden.
   *
   * @throws Exception
   */
  public function headers() {
    throw new \Exception('Not implemented');
  }

  /**
   * To be overridden.
   */
  public function headerSearch(): array {
    return [];
  }

  /**
   * To be overridden.
   */
  public function optionalHeaders(): array {
    return [];
  }

  /**
   * Create function.
   *
   * Creates a handler for each sheet in the workbook.
   * Which handler it picks for each sheet depends on the headers
   * in the first row.
   *
   * @param string $filePath
   *   File Path.
   * @param string $handler
   *   Class Handler.
   * @param string|null $label
   *   File name.
   *
   * @return array
   *   Handler class object.
   *
   * @throws Exception
   */
  public static function create(string $filePath, string $handler = '', string $label = NULL): array {
    $handlers = [];
    $workbook = self::loadWorkbook($filePath);
    foreach ($workbook->getSheetNames() as $sheetName) {
      $sheet = $workbook->getSheetByName($sheetName);
      if (class_exists($handler)) {
        $handlers[] = new $handler($workbook, $sheetName, $label);
      }
    }
    return $handlers;
  }

  /**
   * Load library.
   *
   * @param string $filePath
   *   File with path.
   *
   * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
   *   Spreadsheet object.
   */
  public static function loadWorkbook(string $filePath): Spreadsheet {
    return IOFactory::load($filePath);
  }

  /**
   * Constructor.
   *
   * @throws Exception
   */
  public function __construct($workbook, $sheetName, $label = NULL) {
    $this->workbook = $workbook;
    $this->sheetName = $sheetName;
    $this->dryRun = FALSE;
    $this->label = $label;
    $this->changeDate = $this->parseDateFromLabel($label);
    $this->modelIds = $this->getModelIdName();
  }

  /**
   * Get Model name and id.
   *
   * @return array
   *   List.
   *
   * @throws \Civi\Core\Exception\DBQueryException
   */
  public function getModelIdName() {
    $sql = "SELECT id, product_code FROM `civicrm_inventory_product`";
    $productObject = CRM_Core_DAO::executeQuery($sql);
    $list = [];
    while ($productObject->fetch()) {
      $list[$productObject->product_code] = $productObject->id;
    }
    return $list;
  }

  /**
   * Get Sheet.
   *
   * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet|null
   *   Sheet.
   */
  public function sheet(): ?Worksheet {
    return $this->workbook->getSheetByName($this->sheetName);
  }

  /**
   * Process the sheet.
   *
   * @return void
   *   Nothing.
   *
   * @throws Exception
   */
  public function process(): void {
    $search = $this->headerSearch() ?: array_merge(
      $this->headers(),
      array_map(function ($h) {
        return "/{$h}|/";
      }, $this->optionalHeaders())
    );
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet */
    $sheet = $this->sheet();
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

    $row = 1;
    $rowHeader = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
    for ($row = 2; $row <= $highestRow; $row++) {
      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
      $rowData = array_combine($rowHeader, $rowData);
      $this->processRow($rowData);
    }
  }

  /**
   * Message.
   *
   * @param string $str
   *   String.
   * @param CRM_Inventory_BAO_InventoryProductVariant|null $device
   *   Device.
   * @param bool|string $log
   *   Log.
   * @param string|null $problem
   *   Is problem.
   *
   * @return void
   *   Nothing.
   */
  public function message(string $str, CRM_Inventory_BAO_InventoryProductVariant $device = NULL, bool|string $log = TRUE, string $problem = NULL): void {

    $this->messages[] = new MessageString($str, $device);
    if (strpos($str, 'ERROR') !== FALSE) {
      $this->errors[] = new MessageString($str, $device);
    }
    if (strpos($str, 'WARNING') !== FALSE) {
      $this->warnings[] = new MessageString($str, $device);
    }

    if ($device->id && !$this->dryRun && $log !== FALSE) {
      if ($log === TRUE || ($log === 'problem' && $device->is_problem)) {
        if ($this->label) {
          $str .= " ({$this->label})";
        }
        $device->memo = $str;
      }
    }
  }

  /**
   * Get date from the cell.
   *
   * @param string $value
   *   Date value.
   *
   * @return DateTime|null
   *   Date.
   */
  public function date(string $value): ?DateTime {
    if (is_string($value) && !empty($value) && substr_count($value, '/') == 2) {
      [$month, $day, $year] = explode('/', $value);
      return new \DateTime("$year-$month-$day");
    }
    elseif ($value instanceof \DateTime) {
      return $value;
    }
    else {
      return NULL;
    }
  }

  /**
   * Clean the string.
   *
   * @param string|null $value
   *   Value.
   *
   * @return mixed|string
   *   Trimmed string.
   */
  public function clean(string|null $value): mixed {
    return is_string($value) ? trim($value) : $value;
  }

  /**
   * Clear Number string.
   *
   * @param string|null $value
   *   Value.
   *
   * @return array|string|string[]|null
   *   Trimmed string.
   */
  public function cleanNumstr(string|null $value): array|string|null {
    return preg_replace('/[^0-9]/', '', trim((string) $value));
  }

  /**
   * Get recent changes for device.
   *
   * @param CRM_Inventory_BAO_InventoryProductVariant $device
   *   Device.
   *
   * @return bool
   *   Is recent changes present.
   */
  public function recentChange(CRM_Inventory_BAO_InventoryProductVariant $device): bool {
    if ($this->changeDate) {
      if ($device->updated_at && strtotime('+' . self::WINDOW . ' days', strtotime($device->updated_at)) < strtotime($this->changeDate)) {
        return FALSE;
      }
      else {
        $last_change = InventoryProductChangelog::get(TRUE)
          ->addWhere('product_variant_id', '=', $device->id)
          ->addOrderBy('created_date', 'DESC')
          ->setLimit(1)
          ->execute()->first();

        if (!empty($last_change) && strtotime('+' . self::WINDOW . ' days', strtotime($last_change['created_date'])) >= strtotime($this->changeDate)) {
          return TRUE;
        }
        else {
          return FALSE;
        }
      }
    }
    else {
      return TRUE;
    }
  }

  /**
   * Create Device.
   *
   * @param array $params
   *   Device Parameter.
   *
   * @return CRM_Inventory_BAO_InventoryProductVariant
   */
  public function createDevice(array $params): CRM_Inventory_BAO_InventoryProductVariant {
    /** @var CRM_Inventory_BAO_InventoryProductVariant $device */
    $device = CRM_Inventory_BAO_InventoryProductVariant::create($params);
    if (!$device->product_id) {
      $this->message("ERROR: Could not determine device model for device [device:{$device->product_variant_unique_id}]");
    }
    if ($this->dryRun) {
      $this->message("INFO: Created device [device:{$device->product_variant_unique_id}].", $device);
    }
    else {
      $this->message("INFO: Created device [device:{$device->product_variant_unique_id}].", $device);
      try {
        $device->save();
      }
      catch (Exception $e) {
        $this->message("ERROR: Failed to create device [device:{$device->product_variant_unique_id}]: " . $e->getMessage());
      }
    }
    return $device;
  }

  /**
   * Parse date from label.
   *
   * @param string $label
   *   Label.
   *
   * @return DateTime|false|null
   *   Date time.
   *
   * @throws Exception
   */
  private function parseDateFromLabel(string $label): DateTime|bool|null {
    if ($label === NULL) {
      return new \DateTime();
    }
    if (preg_match('/(\d{4}-\d{2}-\d{2})/', $label, $matches)) {
      return new \DateTime($matches[1]);
    }
    elseif (preg_match('/(\d{4}-\d{2})/', $label, $matches)) {
      return (new \DateTime($matches[1] . '-01'))->modify('last day of this month');
    }
    else {
      $this->message("ERROR: Could not figure out the date of the spreadsheet. " .
        "The file name must contain YYYY-MM or YYYY-MM-DD. Switching to dry run mode.");
      $this->dryRun = TRUE;
      return NULL;
    }
  }

}

/**
 * Message string class.
 */
class MessageString extends \ArrayObject {

  /**
   * @param string $str
   * @param CRM_Inventory_BAO_InventoryProductVariant $device
   */
  public function __construct($str, $device = NULL) {
    $this->device_id = $device ? $device->id : NULL;
    $this->product_variant_unique_id = $device ? $device->product_variant_unique_id : NULL;
    parent::__construct([$str]);
  }

  /**
   * Message.
   *
   * @return mixed|null
   */
  public function message() {
    return $this[0];
  }

  /**
   * Item id.
   *
   * @return int|string|null
   */
  public function item_id() {
    return $this->device_id;
  }

  /**
   * Identifier.
   *
   * @return string|null
   */
  public function identifier() {
    return $this->product_variant_unique_id;
  }

}
