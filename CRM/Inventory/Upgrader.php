<?php

// phpcs:disable
use Civi\Api4\OptionValue;
use Civi\Api4\OptionGroup;
use CRM_Inventory_ExtensionUtil as E;
// phpcs:enable

/**
 * Collection of upgrade steps.
 */
class CRM_Inventory_Upgrader extends CRM_Extension_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Example: Run an external SQL script when the module is installed.
   *
   * Note that if a file is present sql\auto_install that will run regardless of this hook.
   */
  public function install() {
    // Ensure option group exists (in case OptionGroup_grant_status.mgd.php hasn't loaded yet)
    OptionGroup::save(FALSE)
      ->addRecord([
        'name' => 'product_brand',
        'title' => E::ts('Product Brand'),
      ])
      ->setMatch(['name'])
      ->execute();

    // Create unmanaged option values. They will not be updated by the system ever,
    // but they will be deleted on uninstall because the option group is a managed entity.
    OptionValue::save(FALSE)
      ->setDefaults([
        'option_group_id.name' => 'product_brand',
      ])
      ->setRecords([
        ['value' => 1, 'name' => 'JEXtream', 'label' => E::ts('JEXtream'), 'is_default' => TRUE],
        ['value' => 2, 'name' => 'Inseego', 'label' => E::ts('Inseego')],
        ['value' => 3, 'name' => 'Quanta', 'label' => E::ts('Quanta')],
        ['value' => 4, 'name' => 'Franklin', 'label' => E::ts('Franklin')],
        ['value' => 5, 'name' => 'Pixel', 'label' => E::ts('Pixel')],
        ['value' => 6, 'name' => 'Alcatel', 'label' => E::ts('Alcatel')],
        ['value' => 7, 'name' => 'NETGEAR', 'label' => E::ts('NETGEAR')],
        ['value' => 8, 'name' => 'ZTE', 'label' => E::ts('ZTE')],
        ['value' => 9, 'name' => 'Alcatel', 'label' => E::ts('Alcatel')],
      ])
      ->setMatch(['option_group_id', 'name'])
      ->execute();

    OptionGroup::save(FALSE)
      ->addRecord([
        'name' => 'warehouse_shelf',
        'title' => E::ts('Warehouse Shelf'),
      ])
      ->setMatch(['name'])
      ->execute();

    // Create unmanaged option values. They will not be updated by the system ever,
    // but they will be deleted on uninstall because the option group is a managed entity.
    OptionValue::save(FALSE)
      ->setDefaults([
        'option_group_id.name' => 'warehouse_shelf',
      ])
      ->setRecords([
        ['value' => 1, 'name' => 'Upper', 'label' => E::ts('Upper'), 'is_default' => TRUE],
        ['value' => 2, 'name' => 'Lower', 'label' => E::ts('Lower')],
        ['value' => 3, 'name' => 'Middle', 'label' => E::ts('Middle')],
      ])
      ->setMatch(['option_group_id', 'name'])
      ->execute();

    OptionGroup::save(FALSE)
      ->addRecord([
        'name' => 'warranty_type',
        'title' => E::ts('Warranty Type'),
      ])
      ->setMatch(['name'])
      ->execute();

    // Create unmanaged option values. They will not be updated by the system ever,
    // but they will be deleted on uninstall because the option group is a managed entity.
    OptionValue::save(FALSE)
      ->setDefaults([
        'option_group_id.name' => 'warranty_type',
      ])
      ->setRecords([
        ['value' => 1, 'name' => 'mobile_citizen', 'label' => E::ts('Mobile Citizen'), 'is_default' => TRUE],
        ['value' => 2, 'name' => 'google_pixel', 'label' => E::ts('Google Pixel')],
        ['value' => 3, 'name' => 'quanta_warranty', 'label' => E::ts('Quanta Warranty')],
      ])
      ->setMatch(['option_group_id', 'name'])
      ->execute();
  }

  /**
   * Example: Work with entities usually not available during the install step.
   *
   * This method can be used for any post-install tasks. For example, if a step
   * of your installation depends on accessing an entity that is itself
   * created during the installation (e.g., a setting or a managed entity), do
   * so here to avoid order of operation problems.
   */
  public function postInstall(): void {
    $this->executeSqlFile('sql/category.sql');
  }

  /**
   * Example: Run an external SQL script when the module is uninstalled.
   *
   * Note that if a file is present sql\auto_uninstall that will run regardless of this hook.
   */
  public function uninstall(): void {
    $optionGroups = ['warehouse_shelf', 'product_brand', 'warranty_type'];
    foreach ($optionGroups as $optionGroup) {
      // Delete all option fields.
      civicrm_api4('OptionValue', 'delete', [
        'where' => [
          ['option_group_id:name', '=', $optionGroup],
        ],
        'checkPermissions' => TRUE,
      ]);
      // Delete option Group.
      civicrm_api4('OptionGroup', 'delete', [
        'where' => [
          ['name', '=', $optionGroup],
        ],
        'checkPermissions' => TRUE,
      ]);
    }
  }

  /**
   * Example: Run a simple query when a module is enabled.
   */
  // Public function enable(): void {
  //  CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
  // }.

  /**
   * Example: Run a simple query when a module is disabled.
   */
  // Public function disable(): void {
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  // }.

  /**
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   *
   * @throws CRM_Core_Exception
   */
  // Public function upgrade_4200(): bool {
  //   $this->ctx->log->info('Applying update 4200');
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
  //   CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
  //   return TRUE;.

  /**
   * }.
   */
  public function upgrade_1100() {
    $this->ctx->log->info('Applying update 1100 - Installing shipment manifest message workflow templates');
    $this->installManifestMsgWorkflowTpls();
    return TRUE;
  }

  /**
   *
   */
  public function upgrade_1101() {
    $this->ctx->log->info('Applying update 1101 - Installing shipment notification message workflow templates');
    $this->installShipmentMsgWorkflowTpls();
    return TRUE;
  }

  /**
   * Function to install message template.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   */
  public function installManifestMsgWorkflowTpls(): void {
    try {
      $optionGroup = civicrm_api3('OptionGroup', 'create', [
        'name' => 'msg_tpl_workflow_manifest',
        'title' => ts("Message Template Workflow for Shipping Manifest", ['domain' => 'com.skvare.inventory']),
        'description' => ts("Message Template Workflow for Shipping Manifest", ['domain' => 'com.skvare.inventory']),
        'is_reserved' => 1,
        'is_active' => 1,
      ]);
      $optionGroupId = $optionGroup['id'];
    }
    catch (Exception $e) {
      // If an exception is thrown, most likely the option group already exists,
      // in which case we'll just use that one.
      $optionGroupId = civicrm_api3('OptionGroup', 'getvalue', [
        'name' => 'msg_tpl_workflow_manifest',
        'return' => 'id',
      ]);
    }

    $msgTpls = [
      [
        'description' => ts('Shipping - Manifest', ['domain' => 'com.skvare.inventory']),
        'label' => ts('Shipping -Manifest', ['domain' => 'com.skvare.inventory']),
        'name' => 'shipping_manifest',
        'subject' => ts("Shipping : Manifest", ['domain' => 'com.skvare.inventory']),
      ],
    ];

    $this->createMsgTpl($msgTpls, $optionGroupId);
  }

  /**
   * Function to install message template.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   */
  public function installShipmentMsgWorkflowTpls(): void {
    $optionGroupId = civicrm_api3('OptionGroup', 'getvalue', [
      'name' => 'msg_tpl_workflow_manifest',
      'return' => 'id',
    ]);

    $msgTpls = [
      '2' =>
      [
        'description' => ts('Shipping - Notification', ['domain' => 'com.skvare.inventory']),
        'label' => ts('Shipping -Notification', ['domain' => 'com.skvare.inventory']),
        'name' => 'shipping_notification',
        'subject' => ts("Tracking your order", ['domain' => 'com.skvare.inventory']),
      ],
    ];

    $this->createMsgTpl($msgTpls, $optionGroupId);
  }

  /**
   * Create template.
   *
   * @param array $msgTpls
   *   Msg template details.
   * @param int $optionGroupId
   *   Option Group id.
   *
   * @return void
   *   Nothing.
   *
   * @throws CRM_Core_Exception
   */
  public function createMsgTpl(array $msgTpls, int $optionGroupId): void {
    $msgTplDefaults = [
      'is_active' => 1,
      'is_default' => 1,
      'is_reserved' => 0,
    ];

    $baseDir = CRM_Extension_System::singleton()->getMapper()->keyToBasePath('com.skvare.inventory') . '/';
    foreach ($msgTpls as $i => $msgTpl) {
      $optionValue = civicrm_api3('OptionValue', 'create', [
        'description' => $msgTpl['description'],
        'is_active' => 1,
        'is_reserved' => 1,
        'label' => $msgTpl['label'],
        'name' => $msgTpl['name'],
        'option_group_id' => $optionGroupId,
        'value' => ++$i,
        'weight' => $i,
      ]);
      $html = file_get_contents($baseDir . 'msg_tpl/' . $msgTpl['name'] . '.html');

      $params = array_merge($msgTplDefaults, [
        'msg_title' => $msgTpl['label'],
        'msg_subject' => $msgTpl['subject'],
        'msg_html' => $html,
        'workflow_id' => $optionValue['id'],
      ]);
      civicrm_api3('MessageTemplate', 'create', $params);
    }

  }

}
