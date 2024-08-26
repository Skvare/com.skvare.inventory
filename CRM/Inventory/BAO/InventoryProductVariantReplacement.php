<?php

use CRM_Inventory_ExtensionUtil as E;

class CRM_Inventory_BAO_InventoryProductVariantReplacement extends CRM_Inventory_DAO_InventoryProductVariantReplacement {

  /**
   * Get list of product assigned to contact ID.
   * @param $contactID
   * @return array
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function  getContactProductList($contactID) {
    $productList = [];
    if (CRM_Utils_Type::validate($contactID, 'Integer') !== NULL) {
      $inventoryProductVariants = \Civi\Api4\InventoryProductVariant::get(TRUE)
        ->addSelect('id', 'product_id', 'inventory_product.label', 'product_variant_unique_id', 'is_suspended', 'is_discontinued', 'is_discontinued')
        ->addJoin('InventoryProduct AS inventory_product', 'INNER')
        ->addWhere('contact_id', '=', $contactID)
        ->addWhere('is_active', '=', TRUE)
        ->setLimit(25)
        ->execute();
      foreach ($inventoryProductVariants as $inventoryProductVariant) {
        $productList[$inventoryProductVariant['id']] = $inventoryProductVariant['inventory_product.label'] . ' (' . $inventoryProductVariant['product_variant_unique_id'] . ')';
      }
    }
    return $productList;
  }
}

