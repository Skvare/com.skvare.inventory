<?php

use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_BAO_InventoryProductVariant extends CRM_Inventory_DAO_InventoryProductVariant {

  /**
   * Callback for hook_civicrm_pre().
   * @param \Civi\Core\Event\PreEvent $event
   */
  public static function self_hook_civicrm_pre(\Civi\Core\Event\PreEvent $event): void {
    if ($event->action === 'update' || $event->action === 'edit') {
      // CRM_Inventory_BAO_InventoryProductChangelog::logStatusChange($event->id, $event->params);
    }
  }

}
