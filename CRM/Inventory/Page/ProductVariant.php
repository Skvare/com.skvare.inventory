<?php
use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_Page_ProductVariant extends CRM_Core_Page {

  public function run() {
    CRM_Utils_System::setTitle(E::ts('Product Variant'));
    $id = CRM_Utils_Request::retrieve('id', 'Integer', $this, TRUE);
    $contactID = CRM_Utils_Request::retrieve('cid', 'Integer', NULL);
    $action = CRM_Utils_Request::retrieve('action', 'String', NULL);
    $change = CRM_Utils_Request::retrieve('change', 'String', NULL);
    if (!empty($action) && $action & CRM_Core_Action::UPDATE) {
      if (!empty($change)) {
        civicrm_api4('InventoryProductVariant', 'changeStatus', [
          'id' => $id,
          'changeAction' => $change,
          'checkPermissions' => TRUE,
        ]);
      }
        $viewProduct = CRM_Utils_System::url('civicrm/contact/view/inventory-productvariant',
          "action=view&reset=1&cid={$contactID}&id={$id}"
        );
        CRM_Core_Error::statusBounce(ts('Product details updated.'), $viewProduct,
          'Updated');
    }

    $productDetails = CRM_Inventory_BAO_InventoryProductVariant
      ::getProductVariant($id, TRUE);
    // echo '<pre>'; print_r($productDetails); echo '</pre>';exit;
    $this->assign('productDetails', $productDetails);

    $tags = CRM_Inventory_BAO_InventoryProductVariant::statusTagClass();
    $this->assign('tags', $tags);

    $currentTags =
      CRM_Inventory_BAO_InventoryProductVariant::getTagsForVariant($id);

    // Example: Assign a variable for use in a template
    $this->assign('currentTags', $currentTags);
    \Civi::service('angularjs.loader')->addModules('afsearchBatchChangeRequest');

    parent::run();
  }

}
