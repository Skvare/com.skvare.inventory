<?php

use CRM_Inventory_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Inventory_Form_Devicereplacement extends CRM_Core_Form {

  /**
   * @throws \CRM_Core_Exception
   */
  public function buildQuickForm(): void {
    $contactID = CRM_Core_Session::getLoggedInContactID();
    $productList = CRM_Inventory_BAO_InventoryProductVariantReplacement::getContactProductList($contactID);
    $this->assign('productList', $productList);
    // add form elements
    $this->add(
      'select', // field type
      'old_product_id', // field name
      'Product you want to replace', // field label
      $productList, // list of options
      TRUE // is required
    );

    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ],
    ]);

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }


  public function postProcess(): void {
    $values = $this->exportValues();
    $contactID = CRM_Core_Session::getLoggedInContactID();
    $results = \Civi\Api4\InventoryProductVariantReplacement::create(TRUE)
      ->addValue('contact_id', $contactID)
      ->addValue('old_product_id', $values['old_product_id'])
      ->addValue('source', 'User request')
      ->execute();
  }


  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames(): array {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
