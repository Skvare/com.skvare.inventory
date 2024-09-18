<?php

/*
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC. All rights reserved.                        |
|                                                                    |
| This work is published under the GNU AGPLv3 license with some      |
| permitted exceptions and without any warranty. For full license    |
| and copyright information, see https://civicrm.org/licensing       |
+--------------------------------------------------------------------+
 */

namespace Civi\Api4\Action\InventoryReferrals;

use Civi\Api4\Generic\AbstractAction;
use Civi\Api4\Generic\Result;

/**
 * Generate a Code referral code on request.
 */
class GenerateCode extends AbstractAction {

  /**
   * Function to change product status.
   *
   * @param \Civi\Api4\Generic\Result $result
   *   Result input params.
   *
   * @throws \CRM_Core_Exception
   */
  public function _run(Result $result) {
    // Generate New code.
    $newCode = \CRM_Inventory_BAO_InventoryReferrals::getNewCode();
    $result['code'] = $newCode;
  }

}
