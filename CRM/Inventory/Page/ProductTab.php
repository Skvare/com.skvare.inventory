<?php

/**
 *
 */
class CRM_Inventory_Page_ProductTab extends CRM_Core_Page {

  /**
   * Run Page.
   *
   * @return void
   *   Nothing.
   */
  public function run(): void {
    CRM_Utils_System::setTitle(\CRM_Inventory_ExtensionUtil::ts('Product Tab'));
    $this->build();
    parent::run();
  }

  /**
   * Build tab.
   *
   * @return array[]|null
   *   Tabs.
   */
  public function build(): ?array {
    $tabs = $this->getVar('tabHeader');
    if (!$tabs || empty($_GET['reset'])) {
      $tabs = $this->process();
      $this->assign('tabHeader', $tabs);
      $this->setVar('tabHeader', $tabs);
    }
    $this->assign('tabHeader', $tabs);
    CRM_Core_Resources::singleton()
      ->addScriptFile('civicrm', 'templates/CRM/common/TabHeader.js', 1, 'html-header')
      ->addSetting([
        'tabSettings' => [
          'active' => self::getCurrentTab($tabs),
        ],
      ]);
    return $tabs;
  }

  /**
   * Get Current tab.
   *
   * @param array $tabs
   *   Tab details.
   *
   * @return false|int|string
   *   Current tab.
   */
  public static function getCurrentTab(array $tabs): false|int|string {
    static $current = FALSE;

    if ($current) {
      return $current;
    }

    if (is_array($tabs)) {
      foreach ($tabs as $subPage => $pageVal) {
        if ($pageVal['current'] === TRUE) {
          $current = $subPage;
          break;
        }
      }
    }

    $current = $current ?: 'settings';
    return $current;
  }

  /**
   * Process.
   *
   * @return array|array[]
   *   Tabs.
   */
  public function process(): array {
    $default = [
      'link' => NULL,
      'valid' => FALSE,
      'active' => FALSE,
      'current' => FALSE,
      'class' => FALSE,
      'extra' => FALSE,
      'template' => FALSE,
      'count' => FALSE,
      'icon' => FALSE,
    ];

    $tabs = [
      'all' => [
        'title' => ts('All'),
      ] + $default,
      'new_inventory' => [
        'title' => ts('unassigned'),
      ] + $default,
      'inactive' => [
        'title' => ts('Out of service'),
      ] + $default,
      'problems' => [
        'title' => ts('Problems'),
      ] + $default,
      'expiring' => [
        'title' => ts('Expiring'),
      ] + $default,
      'loaners' => [
        'title' => ts('Loaners'),
      ] + $default,
      'nomodel' => [
        'title' => ts('No Model'),
      ] + $default,
    ];

    $reset = !empty($_GET['reset']) ? 'reset=1&' : '';
    $selectedChild = CRM_Utils_Request::retrieve('selectedChild', 'Alphanumeric', $this, FALSE);
    if ($selectedChild) {
      $tabs[$selectedChild]['current'] = TRUE;
    }
    foreach ($tabs as $key => $value) {
      if (!isset($tabs[$key]['qfKey'])) {
        $tabs[$key]['qfKey'] = NULL;
      }

      $tabs[$key]['link'] = CRM_Utils_System::url(
        "civicrm/inventory/device-details",
        "{$reset}&key={$key}", FALSE,
        "status=$key"
      );
      $tabs[$key]['active'] = $tabs[$key]['valid'] = TRUE;
    }

    return $tabs;
  }

}
