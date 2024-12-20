<?php

/**
 *
 */

use Civi\Api4\InventorySales;
use Civi\Api4\LineItem;

/**
 *
 */
class CRM_Inventory_Page_Lineitem extends CRM_Core_Page {

  /**
   *
   */
  public function run() {
    CRM_Utils_System::setTitle(\CRM_Inventory_ExtensionUtil::ts('Line Item'));
    $orderID = CRM_Utils_Request::retrieve('code', 'String', NULL, TRUE);
    $action = CRM_Utils_Request::retrieve('operation', 'String', NULL);
    $saleID = CRM_Core_DAO::getFieldValue('CRM_Inventory_DAO_InventorySales', $orderID, 'id', 'code');
    $this->assign('orderID', $orderID);
    $msg = '';
    if ($saleID && !empty($action)) {
      if ($action == 'getrate') {
        try {
          $shipmentLabel = new CRM_Inventory_BAO_InventoryShipmentLabels();
          $shipmentLabel->listRate($saleID);
        }
        catch (Exception $e) {
          $msg = $e->getMessage();
        }
      }
      elseif ($action == 'pay') {
        try {
          $shipmentLabel = new CRM_Inventory_BAO_InventoryShipmentLabels();
          $labelID = CRM_Core_DAO::getFieldValue('CRM_Inventory_DAO_InventoryShipmentLabels', $saleID, 'id', 'sales_id');
          $shipmentLabel->load('id', $labelID);
          $shipmentLabel->asyncGetRatesAndPay();
        }
        catch (Exception $e) {
          $msg = $e->getMessage();
        }
      }
      elseif ($action == 'update_rate_id') {
        try {
          $newRateID = CRM_Utils_Request::retrieve('new_rate_id', 'String', NULL, TRUE);
          $labelID = CRM_Core_DAO::getFieldValue('CRM_Inventory_DAO_InventoryShipmentLabels', $saleID, 'id', 'sales_id');
          $shipmentLabel = new CRM_Inventory_BAO_InventoryShipmentLabels();
          $shipmentLabelObject = $shipmentLabel->findEntityById('id', $labelID, 'InventoryShipmentLabels', TRUE);
          if ($shipmentLabelObject->id && $shipmentLabelObject->rate_id != $newRateID) {
            $shipmentLabelObject->rate_id = $newRateID;
            $shipmentLabelObject->save();
          }
        }
        catch (Exception $e) {
          $msg = $e->getMessage();
        }
      }
      elseif ($action == 'refund') {
        $shipmentLabel = new CRM_Inventory_BAO_InventoryShipmentLabels();
        try {
          $labelID = CRM_Core_DAO::getFieldValue('CRM_Inventory_DAO_InventoryShipmentLabels', $saleID, 'id', 'sales_id');
          $shipmentLabel->load('id', $labelID);
          $shipmentLabel->refund();
        }
        catch (Exception $e) {
          $msg = $e->getMessage();
        }

      }
      elseif ($action == 'destroy') {
        try {
          $labelID = CRM_Core_DAO::getFieldValue('CRM_Inventory_DAO_InventoryShipmentLabels', $saleID, 'id', 'sales_id');
          $shipmentLabel = new CRM_Inventory_BAO_InventoryShipmentLabels();
          $shipmentLabelObject = $shipmentLabel->findEntityById('id', $labelID, 'InventoryShipmentLabels', TRUE);
          if ($shipmentLabelObject->id && !$shipmentLabelObject->is_paid) {
            $shipmentLabelObject->delete();
            $msg = 'Deleted Shipment Label';
          }
          else {
            $msg = 'Unable to delete Label';
          }
        }
        catch (Exception $e) {
          $msg = $e->getMessage();
        }
      }
      $viewLineItem = CRM_Utils_System::url('civicrm/inventory/sale-lineitems',
        "code={$orderID}"
      );
      CRM_Core_Error::statusBounce($msg ?? 'Processed', $viewLineItem, 'Updated');
    }
    $saleID = $saleID ?? 0;
    $inventorySaleses = InventorySales::get(TRUE)
      ->addSelect('*', 'inventory_shipment.shipped_date', 'inventory_shipment.is_shipped', 'inventory_shipment.is_finished', 'product_id.label', 'inventory_shipment_labels.*', 'contact.display_name', 'contact.id')
      ->addJoin('InventoryShipment AS inventory_shipment', 'LEFT')
      ->addJoin('InventoryShipmentLabels AS inventory_shipment_labels', 'LEFT')
      ->addJoin('Contact AS contact', 'LEFT', ['contact_id', '=', 'contact.id'])
      ->addWhere('id', '=', $saleID)
      ->setLimit(25)
      ->execute()->getArrayCopy()[0];
    $salesDetails = [];
    foreach ($inventorySaleses as $key => $inventorySales) {
      // Do something.
      [$pre, $newKey] = explode('.', $key, 2);
      $newKey = str_replace(':', '_', $newKey);
      $key = str_replace(':', '_', $key);
      if ($pre == 'inventory_shipment_labels' && !empty($newKey)) {
        if (($newKey == 'shipment' || $newKey == 'purchase') && !empty($inventorySales)) {
          $inventorySales = json_decode($inventorySales, TRUE);
        }
        if (($newKey == 'shipment' || $newKey == 'purchase') && empty($inventorySales)) {
          $inventorySales = [];
        }
        $salesDetails['inventory_shipment_labels'][$newKey] = $inventorySales;
      }
      elseif ($pre == 'product_id' && !empty($newKey)) {
        $salesDetails['product'][$newKey] = $inventorySales;
      }
      elseif ($pre == 'inventory_shipment' && !empty($newKey)) {
        $salesDetails['inventory_shipment'][$newKey] = $inventorySales;
      }
      elseif ($pre == 'contact' && !empty($newKey)) {
        $salesDetails['contact'][$newKey] = $inventorySales;
      }
      else {
        $salesDetails[$key] = $inventorySales;
      }
    }
    if (!empty($salesDetails['inventory_shipment_labels']['rate_id'])) {
      if (!empty($salesDetails['inventory_shipment_labels']['shipment']['rates'])) {
        foreach ($salesDetails['inventory_shipment_labels']['shipment']['rates'] as $rate) {
          if ($rate['object_id'] == $salesDetails['inventory_shipment_labels']['rate_id']) {
            $salesDetails['inventory_shipment_labels']['rate_used'] = $rate;
          }
        }
      }
    }
    if (!empty($salesDetails['inventory_shipment_labels']['label_url'])) {
      $path = CRM_Core_Config::singleton()->customFileUploadDir . '/' . $salesDetails['inventory_shipment_labels']['label_url'];
      if (file_exists($path)) {
        $salesDetails['inventory_shipment_labels']['label_url_exist'] = TRUE;
      }
      else {
        $salesDetails['inventory_shipment_labels']['label_url_exist'] = FALSE;
      }
    }

    //echo '<pre>'; print_r($salesDetails); echo '</pre>';exit;

    $this->assign('salesDetails', $salesDetails);

    $lineItems = LineItem::get(TRUE)
      ->addSelect('label', 'subtitle', 'entity_id', 'entity_table', 'line_total',
        'product_id', 'product_variant_id', 'additional_details', 'membership_type.fair_value',
        'membership_id', 'inventory_product.label', 'sale_id', 'contribution.total_amount',
        'contribution.fee_amount', 'inventory_sales.value_amount', 'contribution.net_amount',
        'non_deductible_amount', 'qty', 'inventory_product_variant.*')
      ->addJoin('InventoryProduct AS inventory_product',
        'LEFT', ['product_id', '=', 'inventory_product.id'])
      ->addJoin('InventoryProductVariant AS inventory_product_variant',
        'LEFT', ['product_variant_id', '=', 'inventory_product_variant.id'], ['inventory_product.id', '=', 'inventory_product_variant.product_id'])
      ->addJoin('Membership AS membership',
        'LEFT', ['membership_id', '=', 'membership.id'])
      ->addJoin('MembershipType AS membership_type',
        'LEFT', ['membership_type.id', '=', 'membership.membership_type_id'])
      ->addJoin('Contribution AS contribution',
        'LEFT', ['contribution_id', '=', 'contribution.id'])
      ->addJoin('InventorySales AS inventory_sales',
        'LEFT', ['sale_id', '=', 'inventory_sales.id'])
      ->addWhere('sale_id', '=', $saleID)
      ->setLimit(25)
      ->execute()->getArrayCopy();
    $lineItemArray = [];
    foreach ($lineItems as $key => $lineItem) {
      if (!empty($lineItem['additional_details'])) {
        $lineItem['additional_details'] = json_decode($lineItem['additional_details'], TRUE);
        $lineItem['additional_details'] = http_build_query($lineItem['additional_details'], '', ',');
      }
      $lineItemArray[] = [
        'id' => $lineItem['id'],
        'label' => $lineItem['label'],
        'subtitle' => $lineItem['subtitle'],
        'qty' => $lineItem['qty'],
        'entity_id' => $lineItem['entity_id'],
        'entity_table' => $lineItem['entity_table'],
        'membership_id' => $lineItem['membership_id'],
        'line_total' => $lineItem['line_total'],
        'non_deductible_amount' => $lineItem['non_deductible_amount'],
        'product_label' => $lineItem['inventory_product.label'],
        'product_variant_id' => $lineItem['product_variant_id'],
        'product_id' => $lineItem['product_id'],
        'additional_details' => $lineItem['additional_details'],

        'contribution_total_amount' => $lineItem['contribution.total_amount'],
        'contribution_fee_amount' => $lineItem['contribution.fee_amount'],
        'contribution_net_amount' => $lineItem['contribution.net_amount'],
        'sale_value_amount' => $lineItem['inventory_sales.value_amount'],
      ];
    }
    $paidAmount = $fairAmount = $feeAmount = FALSE;
    if (!empty($lineItemArray)) {
      $paidAmount = $lineItemArray[0]['contribution_total_amount'];
      $feeAmount = $lineItemArray[0]['contribution_fee_amount'];
      $fairAmount = $lineItemArray[0]['sale_value_amount'];
    }
    $this->assign('lineItemArray', $lineItemArray);
    $this->assign('paidAmount', $paidAmount);
    $this->assign('feeAmount', $feeAmount);
    $this->assign('fairAmount', $fairAmount);
    parent::run();
  }

}
