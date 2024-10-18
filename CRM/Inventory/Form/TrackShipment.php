<?php

/**
 * Form controller class.
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Inventory_Form_TrackShipment extends CRM_Core_Form {

  /**
   * Tracking Number.
   *
   * @var string
   */
  protected ?string $_tracking_id = NULL;

  /**
   * @throws CRM_Core_Exception
   */
  public function preProcess() {
    $this->_tracking_id = CRM_Utils_Request::retrieve('tracking_id', 'String');
  }

  /**
   * @throws \CRM_Core_Exception
   */
  public function buildQuickForm(): void {

    // Add form element.
    $this->add('text', 'tracking_id', 'Tracking Number', [], TRUE);
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => \CRM_Inventory_ExtensionUtil::ts('Submit'),
        'isDefault' => TRUE,
      ],
    ]);

    if ($this->_tracking_id) {
      $defaultValues['tracking_id'] = $this->_tracking_id;
      $this->setDefaults($defaultValues);
      $this->postProcess();
    }

    // Export form elements.
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  /**
   * @throws \CRM_Inventory_ExtensionUtilxception
   */
  public function postProcess(): void {
    $values = $this->exportValues();
    if (isset($values['tracking_id'])) {
      $detail = CRM_Inventory_BAO_InventoryShipmentLabels::getTrackShipment($values['tracking_id']);
      if ($detail['valid']) {
        $params = ['tracking_id' => $detail['tracking_id'], 'provider' => $detail['provider']];
        $result = CRM_Inventory_BAO_InventoryShipmentLabels::trackShipment($params);
        if (empty($result)) {
          CRM_Core_Session::setStatus('Not found', 'Record');
        }
        else {
          $this->assign('trackingDetails', $result);
          CRM_Core_Session::setStatus('Found', 'Record');
        }
      }
      else {
        CRM_Core_Session::setStatus('Not found', 'Record');
      }
    }
    parent::postProcess();
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
