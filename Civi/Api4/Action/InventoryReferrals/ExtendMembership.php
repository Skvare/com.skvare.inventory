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
 * Extend membership by set interval using referral.
 *
 * @method $this setMembershipId(string $membership_id) Set Membership ID
 * (required).
 */
class ExtendMembership extends AbstractAction {

  /**
   * Membership ID of consume membership.
   *
   * @var int
   * @required
   */
  protected string $membershipId = '';

  /**
   * Extend the membership for referral.
   *
   * @param \Civi\Api4\Generic\Result $result
   *   Result input params.
   *
   * @throws \CRM_Core_Exception
   */
  public function _run(Result $result) {
    // Extend the membership of referral and referee record after validation.
    $isExtended =
      \CRM_Inventory_BAO_InventoryReferrals::extendMembershipForReferral($this->membershipId);

    $result[] = [
      'extended' => $isExtended,
    ];
  }
}
