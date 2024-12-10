<?php

/**
 * @file
 */

use Civi\Api4\InventorySales;

/**
 * Job.Ordersweeper API specification (optional).
 *
 * This is used for documentation and validation.
 *
 * @param array $spec
 *   Description of fields supported by this API call.
 *
 * @see https://docs
 * .org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_job_Ordersweeper_spec(&$spec) {
}

/**
 * Job.Ordersweeper API.
 *
 * @param array $params
 *   Input params.
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws CRM_Core_Exception
 */
function civicrm_api3_job_Ordersweeper($params) {
  OrderSweeper::sweep();
  $returnValues = 'Updated Order Sweeper';
  return civicrm_api3_create_success($returnValues, $params, 'Job', 'Ordersweeper');
}

/**
 * Order sweeper class.
 */
class OrderSweeper {

  /**
   * Sweep.
   *
   * @return void
   *   Nothing.
   */
  public static function sweep() {
    self::cancelUnpaid();
    self::destroyCanceled();
    self::send_tracking_notices();
    self::destroy_old_enrollments();
  }

  /**
   * Cancel Unpaid order.
   *
   * @return void
   *   Noting.
   */
  private static function cancelUnpaid(): void {
    $inventorySales = InventorySales::get()
      ->addSelect('contribution.contribution_status_id', 'contribution.contribution_status_id:name', 'id', 'sale_date', 'status_id', 'contact_id')
      ->addJoin('Contribution AS contribution', 'LEFT')
      ->addWhere('is_active', '=', TRUE)
      ->addWhere('is_paid', '=', FALSE)
      ->addWhere('is_fulfilled', '=', FALSE)
      ->addWhere('sale_date', '<', date('Y-m-d H:i:s', strtotime('-2 months')))
      ->setLimit(25)
      ->execute();
    foreach ($inventorySales as $inventorySale) {
      if (empty($inventorySale['contribution_id']) || (
          !empty($inventorySale['contribution.contribution_status_id']) && $inventorySale['contribution.contribution_status_id'] != 1)) {
        $inventorySalesObject = new CRM_Inventory_BAO_InventorySales();
        $inventorySalesObject->id = $inventorySale['id'];
        $inventorySalesObject->is_active = FALSE;
        $inventorySalesObject->save();
      }
    }
  }

  /**
   * Destroy cancelled orders.
   *
   * @return void
   *   Nothing.
   */
  private static function destroyCanceled() {
    $inventorySales = InventorySales::get()
      ->addSelect('contribution.contribution_status_id', 'contribution.contribution_status_id:name', 'id', 'sale_date', 'status_id', 'contact_id')
      ->addJoin('Contribution AS contribution', 'LEFT')
      ->addWhere('is_active', '=', FALSE)
      ->addWhere('is_paid', '=', FALSE)
      ->addWhere('is_fulfilled', '=', FALSE)
      ->setLimit(25)
      ->execute();
    foreach ($inventorySales as $inventorySale) {
      if (empty($inventorySale['contribution_id'])) {
        $inventorySalesObject = new CRM_Inventory_BAO_InventorySales();
        $inventorySalesObject->id = $inventorySale['id'];
        $inventorySalesObject->find();
        $inventorySalesObject->delete();
      }
    }
  }

  /**
   * Sending tracking message.
   *
   * @return void
   *   Nothing.
   */
  private static function send_tracking_notices() {
    $inventorySaleses = \Civi\Api4\InventorySales::get()
      ->addSelect('id', 'code', 'contact_id', 'inventory_shipment.shipped_date', 'inventory_shipment_labels.tracking_id', 'inventory_shipment_labels.tracking_url')
      ->addJoin('InventoryShipment AS inventory_shipment', 'INNER', ['shipment_id', '=', 'inventory_shipment.id'])
      ->addJoin('InventoryShipmentLabels AS inventory_shipment_labels', 'INNER', ['id', '=', 'inventory_shipment_labels.sales_id'])
      ->addWhere('is_shipping_required', '=', TRUE)
      ->addWhere('is_active', '=', TRUE)
      ->addWhere('is_paid', '=', TRUE)
      ->addWhere('is_fulfilled', '=', TRUE)
      ->addWhere('is_tracking_sent', '=', FALSE)
      ->addWhere('inventory_shipment_labels.is_paid', '=', TRUE)
      ->addWhere('inventory_shipment_labels.tracking_url', 'IS NOT EMPTY')
      ->addWhere('inventory_shipment.is_shipped', '=', TRUE)
      ->addWhere('inventory_shipment.is_finished', '=', TRUE)
      ->setLimit(50)
      ->execute();
    $sendTemplateParams = [
      'groupName' => 'msg_tpl_workflow_manifest',
      'valueName' => 'shipping_manifest',
      'isTest' => 0,
      'tplParams' => [],
    ];
    [$receipt_from_name, $receipt_from_email] = CRM_Core_BAO_Domain::getNameAndEmail();
    $sendTemplateParams['from'] = ($receipt_from_name ?? '') . ' <' . $receipt_from_email . '>';
    foreach ($inventorySaleses as $inventorySales) {
      $contactID = $inventorySales['contact_id'];
      [$displayName, $email] = CRM_Contact_BAO_Contact_Location::getEmailDetails($contactID);
      $sendTemplateParams['contactId'] = $contactID;
      $sendTemplateParams['tplParams']['order_code'] = $inventorySales['code'];
      $sendTemplateParams['tplParams']['tracking_id'] = $inventorySales['inventory_shipment_labels.tracking_id'];
      $sendTemplateParams['tplParams']['tracking_url'] = $inventorySales['inventory_shipment_labels.tracking_url'];
      $sendTemplateParams['tplParams']['shipped_date'] = $inventorySales['inventory_shipment.shipped_date'];
      $sendTemplateParams['toName'] = $displayName;
      $sendTemplateParams['toEmail'] = $email;
      [$sent, $subject, $message, $html] = CRM_Core_BAO_MessageTemplate::sendTemplate($sendTemplateParams);
      if ($sent) {
        $inventorySalesObject = new CRM_Inventory_BAO_InventorySales();
        $inventorySalesObject->id = $inventorySales['id'];
        $inventorySalesObject->is_tracking_sent = TRUE;
        $inventorySalesObject->save();
      }
    }
  }

  /**
   * Destroy old enrollment.
   *
   * @return void
   */
  private static function destroy_old_enrollments() {
    //Enrollment::where('order_id', NULL)->where('updated_at', '<', now()->subYear())->delete();
  }

}
