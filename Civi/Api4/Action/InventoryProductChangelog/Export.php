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

namespace Civi\Api4\Action\InventoryProductChangelog;

use Civi\Api4\Generic\AbstractAction;
use Civi\Api4\Generic\Result;

/**
 * Extend batch process.
 *
 * @method $this setBatchId(string $id) Set Batch ID
 * (required).
 */
class Export extends AbstractAction {

  /**
   * Batch ID.
   *
   * @var int
   * @required
   */
  protected int $batchId;

  /**
   * Export the Change log for batch.
   *
   * @param \Civi\Api4\Generic\Result $result
   *   Result input params.
   *
   * @throws \CRM_Core_Exception
   */
  public function _run(Result $result) {
    \CRM_Inventory_BAO_InventoryProductChangelog::exportForBatch
    ($this->batchId, TRUE);
  }

}
