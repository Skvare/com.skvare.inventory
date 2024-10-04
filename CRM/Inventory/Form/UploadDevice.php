<?php

/**
 * Form controller class.
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Inventory_Form_UploadDevice extends CRM_Core_Form {

  /**
   * Error Message.
   *
   * @var array
   */
  public array $message = [];


  /**
   * @throws \CRM_Core_Exception
   */
  public function buildQuickForm(): void {
    $list = [
      'CRM_Inventory_UploaderHandlers_UploadDevice' => 'New Device',
      'CRM_Inventory_UploaderHandlers_DeviceReplacement' => 'Replace Device',
      'CRM_Inventory_UploaderHandlers_MobileCitizen' => 'Mobile Citizen',
    ];
    // Add form elements.
    $this->add('select', 'file_handler', 'File Type', $list, TRUE);
    $config = CRM_Core_Config::singleton();
    $uploadFileSize = CRM_Utils_Number::formatUnitSize($config->maxFileSize . 'm', TRUE);

    // Fetch uploadFileSize from php_ini when $config->maxFileSize is set to
    // "no limit".
    if (empty($uploadFileSize)) {
      $uploadFileSize = CRM_Utils_Number::formatUnitSize(ini_get('upload_max_filesize'), TRUE);
    }
    $uploadSize = round(($uploadFileSize / (1024 * 1024)), 2);

    $this->assign('uploadSize', $uploadSize);
    $this->add('File', 'uploadFile', ts('Data File'), 'size=30 maxlength=255', TRUE);
    $this->setMaxFileSize($uploadFileSize);
    $this->addRule('uploadFile', ts('File size should be less than %1 MBytes (%2 bytes)', [
      1 => $uploadSize,
      2 => $uploadFileSize,
    ]), 'maxfilesize', $uploadFileSize);
    $this->addRule('uploadFile', ts('A valid file must be uploaded.'), 'uploadedfile');

    $this->addButtons([
        [
          'type' => 'upload',
          'name' => ts('Upload'),
          'spacing' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          'isDefault' => TRUE,
        ],
        [
          'type' => 'cancel',
          'name' => ts('Cancel'),
        ],
    ]
    );

    // Export form elements.
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  /**
   * Process the data.
   *
   * @return void
   *   Nothing.
   */
  public function postProcess(): void {
    $values = $this->exportValues();
    $params = $this->controller->exportValues($this->_name);
    $filePath = $params['uploadFile']['name'];
    $fileName = $_FILES['uploadFile']['name'];
    $handlers = CRM_Inventory_Uploader::create($filePath, $params['file_handler'], $fileName);
    foreach ($handlers as $handler) {
      /** @var CRM_Inventory_Uploader $handler */
      $handler->process();
      $this->messages = array_merge($this->messages, $handler->messages);
    }
    CRM_Core_Session::setStatus(\CRM_Inventory_ExtensionUtil::ts('You picked color "%1"', [
      1 => $options[$values['favorite_color']],
    ]));
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
