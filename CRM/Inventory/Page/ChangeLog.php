<?php
use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_Page_ChangeLog extends CRM_Core_Page {

  public function run() {
    CRM_Utils_System::setTitle(E::ts('Change Log'));
    $id = CRM_Utils_Request::retrieve('batch_id', 'Integer', $this, TRUE);
    $action = CRM_Utils_Request::retrieve('action', 'String', NULL, FALSE, 'browse');
    if ($id && $action & CRM_Core_Action::EXPORT) {
      CRM_Inventory_BAO_InventoryProductChangelog::exportForBatch($id, TRUE);
    }
    $this->assign('batch_id', $id);
    \Civi::service('angularjs.loader')->addModules('afsearchBatchChangeRequest');
    \Civi::service('angularjs.loader')->addModules('afsearchBatchList1');
    parent::run();
  }

}
