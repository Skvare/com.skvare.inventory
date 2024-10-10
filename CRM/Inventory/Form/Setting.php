<?php

use CRM_Inventory_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Inventory_Form_Setting extends CRM_Core_Form {

  /**
   * @var array
   */
  var array $fieldList = [];

  var array $fieldListTextField = [];

  public function preProcess() {
    $this->fieldList = [
      'inventory_referral_code' => 'Referral Code Field',
      'inventory_referral_consumed_code' => 'Referral Code Consumed Field',
      'inventory_membership_renewal_date' => 'Membership Renewal Date',
    ];

    $this->fieldListTextField = [
      'inventory_lead_time' => 'Inventory Lead Time (in days)',
    ];

  }

  /**
   * Build form.
   *
   * @throws \CRM_Core_Exception
   */
  public function buildQuickForm(): void {
    $civicrmFields = CRM_Inventory_Utils::getCiviCRMFields();
    foreach ($this->fieldList as $name => $label) {
      $this->add('select', $name, $label,
        $civicrmFields, FALSE, ['class' => 'crm-select2', 'placeholder' => ts('- any -')]);
    }
    foreach ($this->fieldListTextField as $name => $label) {
      $this->add('text', $name, $label, [], FALSE);
    }
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
    foreach ($this->fieldList as $name => $label) {
      $defaults[$name] = $settings->get($name);
    }
    foreach ($this->fieldListTextField as $name => $label) {
      $defaults[$name] = $settings->get($name);
    }
    return $defaults;
  }

  public function postProcess(): void {
    // Store the submitted values in an array.
    $params = $this->controller->exportValues($this->_name);
    // Save the API Key & Save the Security Key.
    $domainID = CRM_Core_Config::domainID();
    $settings = Civi::settings($domainID);
    foreach ($this->fieldList as $name => $label) {
      if (CRM_Utils_Array::value($name, $params)) {
        $settings->set($name, $params[$name]);
      }
    }
    foreach ($this->fieldListTextField as $name => $label) {
      if (CRM_Utils_Array::value($name, $params)) {
        $settings->set($name, $params[$name]);
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
