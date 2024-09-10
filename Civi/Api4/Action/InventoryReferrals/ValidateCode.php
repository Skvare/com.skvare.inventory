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
 * Generate a Validate referral code request for membership.
 *
 * @method $this setCode(string $code) Set Referral code (required).
 */
class ValidateCode extends AbstractAction {

  /**
   * Value of referral code.
   *
   * @var string
   * @required
   */
  protected string $code = '';

  /**
   * Function to change product status.
   *
   * @param \Civi\Api4\Generic\Result $result
   *   Result input params.
   *
   * @throws \CRM_Core_Exception
   */
  public function _run(Result $result) {
    // Check Referral code exist on active membership record.
    $isMembershipExist = \CRM_Inventory_BAO_Membership::findByReferralCode($this->code);
    $result[] = [
      'valid' => !empty($isMembershipExist),
    ];
  }

}
