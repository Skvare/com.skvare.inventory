<?php
// phpcs:disable
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
    \Civi\Api4\OptionGroup::save(FALSE)
      ->addRecord([
        'name' => 'product_brand',
        'title' => E::ts('Product Brand'),
      ])
      ->setMatch(['name'])
      ->execute();

    // Create unmanaged option values. They will not be updated by the system ever,
    // but they will be deleted on uninstall because the option group is a managed entity.
    \Civi\Api4\OptionValue::save(FALSE)
      ->setDefaults([
        'option_group_id.name' => 'product_brand',
      ])
      ->setRecords([
        ['value' => 1, 'name' => 'Sumsung', 'label' => E::ts('Sumsung'), 'is_default' => TRUE],
        ['value' => 2, 'name' => 'Franklin', 'label' => E::ts('Franklin')],
        ['value' => 3, 'name' => 'Coolpad', 'label' => E::ts('Coolpad')],
        ['value' => 4, 'name' => 'Coolpad', 'label' => E::ts('Coolpad')],
        ['value' => 5, 'name' => 'LINKZONE', 'label' => E::ts('LINKZONE')],
        ['value' => 6, 'name' => 'Fuse', 'label' => E::ts('Fuse')],
        ['value' => 7, 'name' => 'ZTE', 'label' => E::ts('ZTE')],
      ])
      ->setMatch(['option_group_id', 'name'])
      ->execute();


    \Civi\Api4\OptionGroup::save(FALSE)
      ->addRecord([
        'name' => 'warehouse_shelf',
        'title' => E::ts('Warehouse Shelf'),
      ])
      ->setMatch(['name'])
      ->execute();

    // Create unmanaged option values. They will not be updated by the system ever,
    // but they will be deleted on uninstall because the option group is a managed entity.
    \Civi\Api4\OptionValue::save(FALSE)
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


    \Civi\Api4\OptionGroup::save(FALSE)
      ->addRecord([
        'name' => 'warranty_type',
        'title' => E::ts('Warranty Type'),
      ])
      ->setMatch(['name'])
      ->execute();

    // Create unmanaged option values. They will not be updated by the system ever,
    // but they will be deleted on uninstall because the option group is a managed entity.
    \Civi\Api4\OptionValue::save(FALSE)
      ->setDefaults([
        'option_group_id.name' => 'warranty_type',
      ])
      ->setRecords([
        ['value' => 1, 'name' => 'express', 'label' => E::ts('Expres'), 'is_default' => TRUE],
        ['value' => 2, 'name' => 'implied', 'label' => E::ts('Implied')],
        ['value' => 3, 'name' => 'not_available', 'label' => E::ts('Not Available')],
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
  // public function postInstall(): void {
  //  $customFieldId = civicrm_api3('CustomField', 'getvalue', array(
  //    'return' => array("id"),
  //    'name' => "customFieldCreatedViaManagedHook",
  //  ));
  //  civicrm_api3('Setting', 'create', array(
  //    'myWeirdFieldSetting' => array('id' => $customFieldId, 'weirdness' => 1),
  //  ));
  // }

  /**
   * Example: Run an external SQL script when the module is uninstalled.
   *
   * Note that if a file is present sql\auto_uninstall that will run regardless of this hook.
   */
  // public function uninstall(): void {
  //   $this->executeSqlFile('sql/my_uninstall.sql');
  // }

  /**
   * Example: Run a simple query when a module is enabled.
   */
  // public function enable(): void {
  //  CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
  // }

  /**
   * Example: Run a simple query when a module is disabled.
   */
  // public function disable(): void {
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  // }

  /**
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   * @throws CRM_Core_Exception
   */
  // public function upgrade_4200(): bool {
  //   $this->ctx->log->info('Applying update 4200');
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
  //   CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
  //   return TRUE;
  // }

}
