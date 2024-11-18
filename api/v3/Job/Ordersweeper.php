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
    $inventorySales = InventorySales::get(TRUE)
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
    $inventorySales = InventorySales::get(TRUE)
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
    $orders = Order::withLabel()->where([
      'is_active' => TRUE,
      'is_paid' => TRUE,
      'is_fulfilled' => TRUE,
      'has_sent_tracking' => FALSE,
    ])->get();

    foreach ($orders as $order) {
      MemberMailer::with(['order' => $order])->trackingNotice()->deliverLater(['queue' => 'tracking']);
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
