<?php

/**
 * Interface API_Wrapper
 */
interface API4_Wrapper {

  /**
   * Interface for interpreting api input.
   *
   * @param array|Civi\Api4\Generic\AbstractAction $apiRequest
   *
   * @return array|Civi\Api4\Generic\AbstractAction $apiRequest
   *   modified $apiRequest
   */
  public function fromApiInput(\Civi\Api4\Generic\AbstractAction $apiRequest);

  /**
   * Interface for interpreting api output.
   *
   * @param array|Civi\Api4\Generic\AbstractAction $apiRequest
   * @param array $result
   *
   * @return array
   *   modified $result
   */
  public function toApiOutput(Civi\Api4\Generic\AbstractAction $apiRequest, $result);

}

/**
 * Implements an API Wrapper to Player search search kit.
 */
class CRM_Inventory_APIWrapper implements API4_Wrapper {

  /**
   * Conditionally changes custom filter for the API request.
   */
  public function fromApiInput(\Civi\Api4\Generic\AbstractAction $apiRequest) {
    return $apiRequest;
  }

  /**
   * Munges the result before returning it to the caller.
   * @throws CiviCRM_API3_Exception
   */
  public function toApiOutput($apiRequest, $result) {
    if (is_object($apiRequest) &&
      is_a($apiRequest, 'Civi\Api4\Generic\AutocompleteAction') &&
      $apiRequest->getFormName() === 'crmMenubar' &&
      $apiRequest->getFieldName() === 'crm-qsearch-input'
    ) {
      if ($apiRequest->getEntityName() == 'Contact' &&
        $apiRequest->getActionName() == 'autocomplete') {
        $filters = $apiRequest->getFilters();
        if (array_key_exists('inventoryproductvariant.product_variant_unique_id', $filters) && !empty($filters['inventoryproductvariant.product_variant_unique_id'])) {
          $result = $this->showContactUsingProductVariant($filters['inventoryproductvariant.product_variant_unique_id']);
        }
      }
    }
    return $result;
  }

  /**
   * @param $apiRequest
   *
   * @throws \CiviCRM_API3_Exception
   */
  private function showContactUsingProductVariant($imeiNumber) {
    $operator = '=';
    if (strrpos($imeiNumber, "%")) {
      $operator = 'LIKE';
    }
    $inventoryProductVariants = \Civi\Api4\InventoryProductVariant::get(TRUE)
      ->addSelect('contact_id', 'contact.sort_name')
      ->addJoin('Contact AS contact', 'INNER')
      ->addWhere('product_variant_unique_id', $operator, $imeiNumber)
      ->setLimit(25)
      ->execute();
    foreach ($inventoryProductVariants as &$inventoryProductVariant) {
      $inventoryProductVariant['id'] = $inventoryProductVariant['contact_id'];
      $inventoryProductVariant['label'] = $inventoryProductVariant['contact.sort_name'];
      $inventoryProductVariant['description'] = [];
    }
    return $inventoryProductVariants;
  }
}

