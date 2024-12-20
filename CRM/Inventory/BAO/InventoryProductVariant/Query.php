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



/**
 * Class CRM_Inventory_BAO_InventoryProductVariant_Query.
 */
class CRM_Inventory_BAO_InventoryProductVariant_Query extends CRM_Contact_BAO_Query_Interface {

  const
    MODE_PRODUCT_VARIANT = 32768;

  /**
   * Get available fields.
   *
   * Important for exports & relative date filters.
   *
   * @return array
   */
  public function &getFields() {
    return CRM_Inventory_BAO_InventoryProductVariant::exportableFields();
  }

  /**
   * Get the fields that are available in the 'contact context'.
   *
   * For example exporting contacts should not include fields for grants etc.
   *
   * @return array
   */
  public function getContactFields(): array {
    return [];
  }

  /**
   * Build select for InventoryProductVariant.
   *
   * @param $query
   */
  public static function select(&$query) {
  }

  /**
   * Given a list of conditions in params generate the required.
   * where clause.
   *
   * @param $query
   */
  public static function where(&$query) {
    foreach ($query->_params as $id => $values) {
      if (!is_array($values) || count($values) != 5) {
        continue;
      }

      if (substr($values[0], 0, 10) == 'inventory_') {
        self::whereClauseSingle($values, $query);
      }
    }
  }

  /**
   * @param $values
   * @param \CRM_Contact_BAO_Query $query
   */
  public static function whereClauseSingle(&$values, &$query) {
    [$name, $op, $value, $grouping, $wildcard] = $values;
    CRM_Core_Error::debug_var('$values', $values);
    $fieldName = self::getFieldName($values);
    switch ($name) {
      case 'inventory_product_variant_shipped_on_low':
      case 'inventory_product_variant_shipped_on_high':
      case 'inventory_product_variant_warranty_start_date_low':
      case 'inventory_product_variant_warranty_start_date_high':
      case 'inventory_product_variant_warranty_end_date_low':
      case 'inventory_product_variant_warranty_end_date_high':
      case 'inventory_product_variant_expire_on_low':
      case 'inventory_product_variant_expire_on_high':
      case 'inventory_product_variant_created_at_low':
      case 'inventory_product_variant_created_at_high':
      case 'inventory_product_variant_updated_at_low':
      case 'inventory_product_variant_updated_at_high':
      case 'inventory_product_variant_replaced_date_low':
      case 'inventory_product_variant_replaced_date_high':
        $fields = CRM_Inventory_DAO_InventoryProductVariant::fields();
        $fieldName = str_replace(['_high', '_low'], '', $name);
        $dbFieldName = $fields[$fieldName]['name'];
        $label = $fields[$fieldName]['title'];
        $query->dateQueryBuilder($values, 'civicrm_inventory_product_variant',
          $fieldName, $dbFieldName,
          $label
        );

        return;

      case 'inventory_product_variant_shipped_relative':
      case 'inventory_product_variant_warranty_start_date_relative':
      case 'inventory_product_variant_warranty_end_date_relative':
      case 'inventory_product_variant_replaced_date_relative':
      case 'inventory_product_variant_expire_on_relative':
      case 'inventory_product_variant_created_at_relative':
      case 'inventory_product_variant_updated_at_relative':
      case 'inventory_product_variant_shipped_on_relative':
        $value = $values[2] ?? NULL;
        if (empty($value)) {
          return;
        }

        [$from, $to] = CRM_Utils_Date::getFromTo($value, NULL, NULL);
        if (strlen($to ?? '') === 10) {
          // If we just have the date we assume the end of that day.
          $to .= ' 23:59:59';
        }
        $fields = CRM_Inventory_DAO_InventoryProductVariant::fields();
        $fieldName = str_replace(['_relative'], '', $name);
        $dbFieldName = $fields[$fieldName]['name'];
        $query->_where[$grouping][] = "civicrm_inventory_product_variant.$dbFieldName" . " BETWEEN '{$from}' AND '{$to}'";
        return;

      default:
        if (substr($name, 0, 10) === 'inventory_') {
          $fields = CRM_Inventory_DAO_InventoryProductVariant::fields();
          $fieldName = $fields[$name]['name'];
          $label = $fields[$name]['title'];
          $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("civicrm_inventory_product_variant.$fieldName", $op, $value, "String");
          [$qillop, $qillVal] = CRM_Contact_BAO_Query::buildQillForFieldValue('CRM_Inventory_DAO_InventoryProductVariant', $name, $value, $op);
          $query->_qill[$grouping][] = \CRM_Inventory_ExtensionUtil::ts("%1 %2 %3", [1 => $label, 2 => $qillop, 3 => $qillVal]);
          $query->_tables['civicrm_inventory_product_variant'] = $query->_whereTables['civicrm_inventory_product_variant'] = 1;
        }
    }
  }

  /**
   * From clause.
   *
   * @param string $name
   *   Field name.
   * @param int $mode
   *   Mode.
   * @param string $side
   *   Join side.
   *
   * @return null|string
   */
  public static function from($name, $mode, $side) {
    $from = NULL;
    switch ($name) {
      case 'civicrm_inventory_product_variant':
        $from = " $side JOIN civicrm_inventory_product_variant ON civicrm_inventory_product_variant.contact_id = contact_a.id ";
        break;
    }

    return $from;
  }

  /**
   * Default return properties.
   *
   * @param $mode
   * @param bool $includeCustomFields
   *
   * @return array|null
   */
  public static function defaultReturnProperties(
    $mode,
    $includeCustomFields = TRUE,
  ) {
    $properties = NULL;
    if ($mode & CRM_Inventory_BAO_InventoryProductVariant_Query::MODE_PRODUCT_VARIANT) {
      $properties = [
        'contact_type' => 1,
        'contact_sub_type' => 1,
        'sort_name' => 1,
        'inventory_product_variant_unique_id' => 1,
        'inventory_product_variant_phone_number' => 1,
      ];
    }

    return $properties;
  }

  /**
   * Get the metadata for fields to be included to search form.
   *
   * @throws \CRM_Core_Exception
   */
  public static function getSearchFieldMetadata() {
    $fields = [];
    $metadata = civicrm_api3('InventoryProductVariant', 'getfields', [])['values'];
    $ignoreList = [
      'inventory_product_variant_id',
      'product_id', 'inventory_product_variant_contact_id', 'inventory_product_variant_details', 'inventory_product_variable_note',
      'replaced_product_id', 'inventory_product_variant_membership_id', 'inventory_product_sales_id',
      'inventory_product_variant_warehouse_row', 'inventory_product_variant_warehouse_shelf', 'inventory_product_variant_is_discontinued',
      'inventory_product_memo',
    ];
    foreach ($metadata as $fld => $fldDetails) {
      if (in_array($fld, $ignoreList)) {
        continue;
      }
      if (substr($fld, 0, 10) === 'inventory_') {
        $fields[] = $fld;
      }
    }
    return array_intersect_key($metadata, array_flip($fields));
  }

  /**
   *  Function for specifying which fields the tpl can iterate through.
   */
  public static function getTemplateHandlableSearchFields() {
    return array_diff_key(self::getSearchFieldMetadata(), []);
  }

  /**
   * Add all the elements to advanaced search.
   *
   * @param $form
   * @param $type
   *
   * @return void
   */
  public function buildAdvancedSearchPaneForm(&$form, $type) {
    if ($type !== 'InventoryProductVariant') {
      return;
    }
    $form->addSearchFieldMetadata(['InventoryProductVariant' => self::getSearchFieldMetadata()]);
    self::addFormFieldsFromMetadataVariant($form);
    $templateFields = self::getTemplateHandlableSearchFields();
    $form->assign('inventoryProductVariantFields', $templateFields);
    $form->assign('validInventoryProductVariant', TRUE);
  }

  /**
   * Register Search Pane.
   *
   * @param array $panes
   *   Panes array.
   *
   * @return void
   */
  public function registerAdvancedSearchPane(&$panes) {
    $panes[\CRM_Inventory_ExtensionUtil::ts('Product Variant')] = 'InventoryProductVariant';
  }

  /**
   * Get table name.
   *
   * @param array $panes
   *   Panel table mapping.
   *
   * @return void
   *   Nothing.
   */
  public function getPanesMapper(&$panes) {
    $panes[\CRM_Inventory_ExtensionUtil::ts('Product Variant')] = 'civicrm_inventory_product_variant';
  }

  /**
   * Table dependency.
   *
   * @param array $tables
   *   Table list.
   */
  public function setTableDependency(&$tables) {
    // $tables['civicrm_inventory_product_variant'] = 1;
  }

  /**
   * Set message template.
   *
   * @param array $paneTemplatePathArray
   *   Pane template list.
   *
   * @param string $type
   *   Type.
   */
  public function setAdvancedSearchPaneTemplatePath(&$paneTemplatePathArray, $type) {
    $paneTemplatePathArray['InventoryProductVariant'] = \CRM_Inventory_ExtensionUtil::path('templates/CRM/Inventory/Form/Search/AdvancedSearchPane.tpl');
  }

  /**
   * Describe options for available for use in the search-builder.
   *
   * @param array $apiEntities
   *   Api entities list.
   * @param array $fieldOptions
   *   Field options.
   */
  public function alterSearchBuilderOptions(&$apiEntities, &$fieldOptions) {
    $apiEntities[] = 'InventoryProductVariant';
  }

  /**
   * Get the name of the field.
   *
   * @param array $values
   *   Values.
   *
   * @return string
   *   Field name.
   */
  protected static function getFieldName($values) {
    $name = $values[0];

    return str_replace(['_high', '_low'], '', $name);
  }

  /**
   *
   */
  public static function addFormFieldsFromMetadataVariant(&$form) {
    $form->addFormRule(['CRM_Core_Form_Search', 'formRule'], $form);
    $form->_action = CRM_Core_Action::ADVANCED;
    foreach ($form->getSearchFieldMetadata() as $entity => $fields) {
      foreach ($fields as $fieldName => $fieldSpec) {
        $fieldType = $fieldSpec['type'] ?? '';
        if ($fieldType === CRM_Utils_Type::T_DATE || $fieldType === (CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME) || $fieldType === CRM_Utils_Type::T_TIMESTAMP) {
          $title = empty($fieldSpec['unique_title']) ? $fieldSpec['title'] : $fieldSpec['unique_title'];
          $form->addDatePickerRange($fieldName, $title, ($fieldType === (CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME) || $fieldType === CRM_Utils_Type::T_TIMESTAMP));
        }
        else {
          // Not quite sure about moving to a mix of keying by entity vs permitting entity to
          // be passed in. The challenge of the former is that it doesn't permit ordering.
          // Perhaps keying was the wrong starting point & we should do a flat array as all
          // fields eventually need to be unique.
          $props = ['entity' => $fieldSpec['entity'] ?? $entity];
          if (isset($fields[$fieldName]['unique_title'])) {
            $props['label'] = $fields[$fieldName]['unique_title'];
          }
          elseif (isset($fields[$fieldName]['html']['label'])) {
            $props['label'] = $fields[$fieldName]['html']['label'];
          }
          elseif (isset($fields[$fieldName]['title'])) {
            $props['label'] = $fields[$fieldName]['title'];
          }
          if (substr($fieldName, 0, 27) === 'InventoryProductVariant_field_custom_') {
            $fieldId = substr($fieldName, 27);
            CRM_Core_BAO_CustomField::addQuickFormElement($form, $fieldName, $fieldId, FALSE, TRUE);
          }
          else {
            if (empty($fieldSpec['is_pseudofield'])) {
              $form->addField($fieldName, $props);
            }
          }
        }
      }
    }
  }

}
