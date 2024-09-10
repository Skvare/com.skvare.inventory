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

namespace Civi\Api4\Action\InventoryProductVariant;

use Civi\Api4\DedupeRuleGroup;
use Civi\Api4\Generic\AbstractAction;
use Civi\Api4\Generic\Result;

/**
 * Generate a Status change request for device.
 *
 * @method $this setId(int $cid) Set contact ID (required).
 * @method $this setChangeAction(string $changeAction) Set Change Status (required).
 * @method $this setMsg(string $msg) Set Message.
 */
class ChangeStatus extends AbstractAction {

  /**
   * ID of product variant.
   *
   * @var int
   * @required
   */
  protected int $id;

  /**
   * Value of Change Action.
   *
   * @var string
   * @optionsCallback getChangeAction
   * @required
   */
  protected string $changeAction;

  /**
   * Value of msg.
   *
   * @var string
   */
  protected string $msg = '';

  /**
   * Options callback for changeAction param.
   *
   * @return string[]
   */
  protected function getChangeAction() {
    return['REACTIVATE', 'TERMINATE',
      'SUSPEND', 'UPDATE', 'LOST', 'EXPIRE', 'PROBLEM'];
  }
  /**
   * Function to change product status.
   *
   * @param \Civi\Api4\Generic\Result $result
   *   Result input params.
   *
   * @throws \CRM_Core_Exception
   */
  public function _run(Result $result) {
    if (in_array(strtoupper($this->changeAction), ['REACTIVATE', 'TERMINATE',
      'SUSPEND', 'UPDATE', 'LOST', 'EXPIRE', 'PROBLEM',
    ])) {
      $a = new \CRM_Inventory_BAO_InventoryProductVariant();
      $result[] = call_user_func_array([$a, 'changeStatus'],
        [$this->id, strtoupper($this->changeAction), $this->msg]);
    }
    else {
      throw new \CRM_Core_Exception('Invalid Action ' . $this->changeAction);
    }
  }

}
