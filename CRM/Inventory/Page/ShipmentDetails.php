<?php

/**
 *
 */

use Civi\API\Exception\UnauthorizedException;

/**
 *
 */
class CRM_Inventory_Page_ShipmentDetails extends CRM_Core_Page {

  /**
   *
   */
  public function run() {
    CRM_Utils_System::setTitle(\CRM_Inventory_ExtensionUtil::ts('Shipment Details'));
    // Example: Assign a variable for use in a template.
    $this->assign('currentTime', date('Y-m-d H:i:s'));
    $shipmentID = CRM_Utils_Request::retrieve('id', 'Integer');
    $action = CRM_Utils_Request::retrieve('action', 'String', NULL);
    $operation = CRM_Utils_Request::retrieve('operation', 'String', NULL);

    if (!empty($action) && $action & CRM_Core_Action::EXPORT && $operation === 'export') {
      // Export CSV.
      CRM_Inventory_BAO_InventoryShipment::exportForBatch($shipmentID, TRUE);
    }
    elseif (!empty($action) && $action & CRM_Core_Action::EXPORT && $operation
      === 'purchase') {
      // Purchase Label.
      $shipment = new CRM_Inventory_BAO_InventoryShipment();
      try {
        $shipment->payForLabels($shipmentID);
      }
      catch (UnauthorizedException | CRM_Core_Exception $e) {

      }
    }
    elseif (!empty($action) && $action & CRM_Core_Action::EXPORT && in_array($operation, ['print_2', 'print_4'])) {

      try {
        // Print Label.
        CRM_Inventory_BAO_InventoryShipmentLabels::printLabels($shipmentID, $operation);
      }
      catch (UnauthorizedException | CRM_Core_Exception $e) {

      }
    }
    elseif (!empty($action) && $action & CRM_Core_Action::EXPORT && $operation === 'manifests') {
      // Export Manifests.
      $manifestForBatch = CRM_Inventory_BAO_InventoryShipment::manifestForBatch($shipmentID);
      CRM_Inventory_BAO_InventoryShipment::printManifestForBatch($shipmentID, $manifestForBatch);
    }
    elseif (!empty($action) && $action & CRM_Core_Action::RENEW && $operation == 'assigndevice' && !empty($shipmentID)) {
      // Assign Device to Order.
      $orderCode = CRM_Utils_Request::retrieve('order_id', 'String', NULL);
      $deviceID = CRM_Utils_Request::retrieve('device_id', 'String', NULL);
      if ($orderCode && $deviceID) {
        $inventoryShipment = new CRM_Inventory_BAO_InventoryShipment();
        $output = $inventoryShipment->assignDeviceToOrder($orderCode, $deviceID);
        $viewShipment = CRM_Utils_System::url('civicrm/inventory/shipment-details',
          "action=browser&reset=1&id={$shipmentID}&selectedChild=assign_device"
        );
        if (!empty($output)) {
          $error = implode('<br />', $output);
          CRM_Core_Error::statusBounce($error, $viewShipment, 'Error');
        }
        else {
          CRM_Core_Error::statusBounce('Device assigned successfully.',
            $viewShipment, 'Success');
        }
      }
    }
    elseif (!empty($action) && $action & CRM_Core_Action::RENEW && $operation == 'moveshipment' && !empty($shipmentID)) {
      // Mover Order to another shipment.
      $newShipmentId = CRM_Utils_Request::retrieve('new_shipment_id', 'Integer', NULL);
      $saleIds = $_POST['sale_id'];
      if (!empty($newShipmentId) && !empty($saleIds)) {
        $moved = CRM_Inventory_BAO_InventoryShipment::moveSalesOrderToOtherShipment($newShipmentId, $saleIds);
        $viewShipment = CRM_Utils_System::url('civicrm/inventory/shipment-details',
          "action=browser&reset=1&id={$shipmentID}&selectedChild=move_orders"
        );
        if ($moved) {
          $msg = 'Moved Order successfully.';
          $title = ts('Success');
        }
        else {
          $msg = 'Failed to move Order.';
          $title = 'Error';
        }
        CRM_Core_Error::statusBounce($msg, $viewShipment, $title);
      }
    }
    elseif (!empty($action) && $action & CRM_Core_Action::RENEW && $operation == 'newshipment' && !empty($shipmentID)) {
      // Create New shipment.
      CRM_Inventory_BAO_InventoryShipment::createOpenShipment();
      $viewShipment = CRM_Utils_System::url('civicrm/inventory/shipment-details',
        "action=browser&reset=1&id={$shipmentID}&selectedChild=move_orders"
      );
      CRM_Core_Error::statusBounce('New shipment created', $viewShipment, 'Success');
    }
    $shipmentDetails = CRM_Inventory_BAO_InventoryShipment::shipmentSalesListing($shipmentID);
    $shipmentInfo = CRM_Inventory_DAO_InventoryShipment::findById($shipmentID);
    $openShipmentList = CRM_Inventory_BAO_InventoryShipment::findOpenShipmentList();
    $this->assign('openShipmentList', $openShipmentList);
    $shipmentInfo = $shipmentInfo->toArray();
    $this->assign('shipmentInfo', $shipmentInfo);
    $this->assign('shipmentDetails', $shipmentDetails);
    $links = [
      'manifests' => ['id' => 'manifests', 'fa' => 'print', 'label' => 'Print Manifests', 'class' => 'btn btn-success'],
      'export' => ['id' => 'export', 'fa' => 'table', 'label' => 'Batch Shipping Export', 'class' => 'btn btn-danger'],
      'purchase' => ['id' => 'purchase', 'fa' => 'dollar-sign', 'label' => ' Purchase Labels', 'class' => 'btn btn-danger'],
      'print_2' => ['id' => 'print_2', 'icon' => '⊟', 'label' => 'Print 2-Up', 'class' => 'btn btn-primary',],
      'print_4' => ['id' => 'print_4', 'icon' => '⊞', 'label' => 'Print 4-Up', 'class' => 'btn btn-primary'],
    ];

    if ($shipmentInfo['is_shipped']) {
      //$links['purchase']['valid'] = FALSE;
      //$links['purchase']['is_disable'] = TRUE;
    }
    $this->assign('links', $links);
    $this->assign('shipmentID', $shipmentID);
    if (!array_key_exists('snippet', $_GET)) {
      $this->build($shipmentID);
    }
    parent::run();
  }

  /**
   * Build Shipment tab.
   *
   * @param int $shipmentID
   *   Shipment id.
   *
   * @return array[]|null
   *   Tabs.
   */
  public function build(int $shipmentID): ?array {
    $tabs = $this->getVar('tabHeader');
    if (!$tabs || empty($_GET['reset'])) {
      $tabs = $this->process($shipmentID);
      $this->assign('tabHeader', $tabs);
      $this->setVar('tabHeader', $tabs);
    }
    $this->assign('tabHeader', $tabs);
    CRM_Core_Resources::singleton()
      ->addScriptFile('civicrm', 'templates/CRM/common/TabHeader.js', 1, 'html-header')
      ->addSetting([
        'tabSettings' => [
          'active' => self::getCurrentTab($tabs),
        ],
      ]);
    return $tabs;
  }

  /**
   * Get Current tab.
   *
   * @param array $tabs
   *   Tab details.
   *
   * @return false|int|string
   *   Current tab.
   */
  public static function getCurrentTab(array $tabs): false|int|string {
    static $current = FALSE;
    if ($current) {
      return $current;
    }

    if (is_array($tabs)) {
      foreach ($tabs as $subPage => $pageVal) {
        if ($pageVal['current'] === TRUE) {
          $current = $subPage;
          break;
        }
      }
    }

    $current = $current ?: 'settings';
    return $current;
  }

  /**
   * Process.
   *
   * @return array|array[]
   *   Tabs.
   */
  public function process($shipmentID): array {
    $default = [
      'link' => NULL,
      'valid' => FALSE,
      'active' => FALSE,
      'current' => FALSE,
      'class' => FALSE,
      'extra' => FALSE,
      'count' => FALSE,
      'icon' => FALSE,
    ];

    $tabs = [
      'edit' => [
        'title' => ts('Edit'),
        'template' => 'CRM/Inventory/Page/ShipmentDetails_edit.tpl',
      ] + $default,
      'shipping' => [
        'title' => ts('Shipping'),
        'template' => 'CRM/Inventory/Page/ShipmentDetails_shipping.tpl',
      ] + $default,
      'assign_device' => [
        'title' => ts('Assign Devices'),
        'template' => 'CRM/Inventory/Page/ShipmentDetails_assign_device.tpl',
      ] + $default,
      'move_orders' => [
        'title' => ts('Move Orders'),
        'template' => 'CRM/Inventory/Page/ShipmentDetails_move_orders.tpl',
      ] + $default,
    ];

    $reset = !empty($_GET['reset']) ? 'reset=1&' : '';
    $selectedChild = CRM_Utils_Request::retrieve('selectedChild', 'Alphanumeric', $this, FALSE);
    if ($selectedChild) {
      $tabs[$selectedChild]['current'] = TRUE;
    }

    foreach ($tabs as $key => $value) {
      if (!isset($tabs[$key]['qfKey'])) {
        $tabs[$key]['qfKey'] = NULL;
      }

      $tabs[$key]['link'] = CRM_Utils_System::url(
        "civicrm/inventory/shipment-details",
        "id={$shipmentID}&{$reset}&key={$key}", FALSE,
        "status=$key"
      );
      $tabs[$key]['active'] = $tabs[$key]['valid'] = TRUE;
    }

    return $tabs;
  }

}
