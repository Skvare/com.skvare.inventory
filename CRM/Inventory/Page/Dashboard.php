<?php
use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_Page_Dashboard extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Dashboard'));

    // Example: Assign a variable for use in a template
    $this->assign('currentTime', date('Y-m-d H:i:s'));
    $id = 4;
    $code = '12345';
    $out =  CRM_Inventory_BAO_InventoryReferrals::addReferralDetails($id);
    //$out = CRM_Inventory_BAO_Membership::findById($id);
    //$out = CRM_Inventory_BAO_Membership::findByReferralCode($code);
    echo '<pre>'; print_r($out);exit;
    parent::run();
  }

}
