<?php

/**
 *
 */
class CRM_Inventory_Page_Dashboard extends CRM_Core_Page {

  /**
   *
   */
  public function run() {
    CRM_Utils_System::setTitle(\CRM_Inventory_ExtensionUtil::ts('Dashboard'));

    // Example: Assign a variable for use in a template.
    $this->assign('currentTime', date('Y-m-d H:i:s'));
    if (!array_key_exists('snippet', $_GET)) {
      $this->build();
    }
    $dashboardStats = CRM_Inventory_Utils::deviceModelStats();
    $this->assign('dashboardStats', $dashboardStats);
    $membershipDash = new CRM_Member_Page_DashBoard();
    $membershipDash->preProcess();

    parent::run();
  }

  /**
   *
   */
  public function build() {
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
   *
   */
  public static function getCurrentTab($tabs) {
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
      'count' => FALSE,
      'icon' => FALSE,
    ];

    $tabs = [
      'device' => [
        'title' => ts('Device'),
        'template' => 'CRM/Inventory/Page/DashboardPart.tpl',
      ] + $default,
      'order' => [
        'title' => ts('Order'),
        'template' => 'CRM/Inventory/Page/DashboardPart.tpl',
      ] + $default,
      'shipping' => [
        'title' => ts('Shipping'),
        'template' => 'CRM/Inventory/Page/DashboardPart.tpl',
      ] + $default,
      'setting' => [
          'title' => ts('Setting'),
          'template' => 'CRM/Inventory/Page/DashboardPart.tpl',
        ] + $default,
    ];

    $reset = !empty($_GET['reset']) ? 'reset=1&' : '';

    foreach ($tabs as $key => $value) {
      if (!isset($tabs[$key]['qfKey'])) {
        $tabs[$key]['qfKey'] = NULL;
      }

      $tabs[$key]['link'] = CRM_Utils_System::url(
        "civicrm/inventory",
        "{$reset}&key={$key}", FALSE,
        "status=$key"
      );
      $tabs[$key]['active'] = $tabs[$key]['valid'] = TRUE;
    }

    return $tabs;
  }

}
