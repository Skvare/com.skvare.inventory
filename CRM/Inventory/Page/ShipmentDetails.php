<?php
use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_Page_ShipmentDetails extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Shipment Details'));
    // Example: Assign a variable for use in a template
    $this->assign('currentTime', date('Y-m-d H:i:s'));
    $shipmentID = CRM_Utils_Request::retrieve('id', 'Integer');
    $action = CRM_Utils_Request::retrieve('action', 'String', NULL);
    $operation = CRM_Utils_Request::retrieve('operation', 'String', NULL);
    if (!empty($action) && $action & CRM_Core_Action::EXPORT && $operation === 'export') {
      CRM_Inventory_BAO_InventoryShipment::exportForBatch($shipmentID, TRUE);
    }
    elseif (!empty($action) && $action & CRM_Core_Action::EXPORT && $operation === 'manifests') {
      CRM_Inventory_BAO_InventoryShipment::manifestForBatch($shipmentID, TRUE);
    }
    elseif (!empty($action) && $action & CRM_Core_Action::RENEW && $operation == 'assigndevice' && !empty($shipmentID)) {
      $orderCode = CRM_Utils_Request::retrieve('order_id', 'String', NULL);
      $deviceID = CRM_Utils_Request::retrieve('device_id', 'String', NULL);
      if ($orderCode && $deviceID) {
        $inventoryShipment = new CRM_Inventory_BAO_InventoryShipment();
        $output = $inventoryShipment->assignDeviceToOrder($orderCode, $deviceID);
        if (!empty($output)) {
          $viewShipment = CRM_Utils_System::url('civicrm/inventory/shipment-details',
            "action=browser&reset=1&id={$shipmentID}"
          );
          $error = implode('<br />', $output);
          CRM_Core_Error::statusBounce($error, $viewShipment);
        }
        else {
          CRM_Core_Session::setStatus(E::ts('Device assigned successfully.'));
        }
      }
    }
    elseif (!empty($action) && $action & CRM_Core_Action::RENEW && $operation == 'moveshipment' && !empty($shipmentID)) {
      $newShipmentId = CRM_Utils_Request::retrieve('new_shipment_id', 'Integer', NULL);
      $saleIds = $_POST['sale_id'];
      if (!empty($newShipmentId) && !empty($saleIds)) {
        $moved = CRM_Inventory_BAO_InventoryShipment::moveSalesOrderToOtherShipment($newShipmentId, $saleIds);
        $viewShipment = CRM_Utils_System::url('civicrm/inventory/shipment-details',
          "action=browser&reset=1&id={$shipmentID}"
        );
        if ($moved) {
          $msg = 'Moved Order successfully.';
        }
        else {
          $msg = 'Moved Order failed.';
        }
        CRM_Core_Error::statusBounce($msg, $viewShipment);
      }
    }
    elseif (!empty($action) && $action & CRM_Core_Action::RENEW && $operation == 'newshipment' && !empty($shipmentID)) {
      CRM_Inventory_BAO_InventoryShipment::createOpenShipment();
      $viewShipment = CRM_Utils_System::url('civicrm/inventory/shipment-details',
        "action=browser&reset=1&id={$shipmentID}"
      );
      CRM_Core_Error::statusBounce('New shipment created', $viewShipment);
    }
    $shipmentDetails = CRM_Inventory_BAO_InventoryShipment::shipmentSalesListing($shipmentID);
    $shipmentInfo = CRM_Inventory_DAO_InventoryShipment::findById($shipmentID);
    $openShipmentList = CRM_Inventory_BAO_InventoryShipment
      ::findOpenShipmentList();
    $this->assign('openShipmentList', $openShipmentList);
    $shipmentInfo = $shipmentInfo->toArray();
    $this->assign('shipmentInfo', $shipmentInfo);
    $this->assign('shipmentDetails', $shipmentDetails);
    $links = [
      'manifests' => ['id' => 'manifests', 'fa' => 'print', 'label' => 'Print Manifests', 'class' => 'btn btn-success'],
      'export' => ['id' => 'export', 'fa' => 'table', 'label' => 'Batch Shipping Export', 'class' => 'btn btn-danger'],
      'purchase' => ['id' => 'purchase', 'fa' => 'dollar-sign', 'label' => ' Purchase Labels', 'class' => 'btn btn-danger'],
      'print_2' => ['id' => 'print_2', 'icon'=> '⊟','label' => 'Print 2-Up', 'class' =>
    'btn btn-primary'],
      'print_4' => ['id' => 'print_4', 'icon'=> '⊞','label' => 'Print 4-Up', 'class' => 'btn btn-primary'],
    ];

    if ($shipmentInfo['is_shipped']) {
      $links['purchase']['is_disable'] = TRUE;
    }
    $this->assign('links', $links);
    $this->assign('shipmentID', $shipmentID);

    parent::run();
  }

}
