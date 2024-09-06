<?php

use CRM_Inventory_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Inventory_Form_Setting extends CRM_Core_Form {

  /**
   * @throws \CRM_Core_Exception
   */
  public function buildQuickForm(): void {
    $civicrmFields = CRM_Inventory_Utils::getCiviCRMFields();
    $this->add('select', "inventory_referral_code", "Referral Code Field",
      $civicrmFields, FALSE, ['class' => 'crm-select2', 'placeholder' => ts('- any -')]);
    $this->add('select', "inventory_referral_consumed_code", "Referral Code Consumed Field",
      $civicrmFields, FALSE, ['class' => 'crm-select2', 'placeholder' => ts('- any -')]);
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ],
    ]);

    // Export form elements.
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function setDefaultValues() {
    $defaults = [];
    $domainID = CRM_Core_Config::domainID();
    $settings = Civi::settings($domainID);
    $defaults['inventory_referral_code'] = $settings->get('inventory_referral_code');
    $defaults['inventory_referral_consumed_code'] = $settings->get('inventory_referral_consumed_code');
    return $defaults;
  }


  public function postProcess(): void {
    // Store the submitted values in an array.
    $params = $this->controller->exportValues($this->_name);
    // Save the API Key & Save the Security Key
    $setting = ['inventory_referral_code', 'inventory_referral_consumed_code'];
    $domainID = CRM_Core_Config::domainID();
    $settings = Civi::settings($domainID);
    foreach ($setting as $key) {
      if (CRM_Utils_Array::value($key, $params)) {
        $settings->set($key, $params[$key]);
      }
    }

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
