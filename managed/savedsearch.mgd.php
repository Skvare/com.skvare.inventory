<?php

use CRM_Inventory_ExtensionUtil as E;

return [
  // Saved Search for Inventory_Product_List.
  [
    'name' => 'SavedSearch_Inventory_Product_List',
    'entity' => 'SavedSearch',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Inventory_Product_List',
        'label' => E::ts('Inventory Product List'),
        'api_entity' => 'InventoryProduct',
        'api_params' => [
          'version' => 4,
          "select" => [
            "id",
            "label",
            "is_active",
            "product_code",
            "product_category_id:label",
            "product_brand:label",
            "warranty_type_id:label",
          ],
          'orderBy' => [
            'weight DESC',
          ],
          "where" => [
            [
              "is_serialize",
              "=",
              TRUE,
            ],
          ],
          "groupBy" => [],
          "join" => [],
          "having" => [],
        ],
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  // Search Display for Inventory_Product_List.
  [
    'name' => 'SearchDisplay_Inventory_Product_List_Table_1',
    'entity' => 'SearchDisplay',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Inventory_Product_List_Table_1',
        'label' => E::ts('Inventory Product List Table'),
        'saved_search_id.name' => 'Inventory_Product_List',
        'type' => 'table',
        "settings" => [
          "description" => NULL,
          "sort" => [],
          "limit" => 50,
          "pager" => [],
          "placeholder" => 5,
          "columns" => [
            [
              "type" => "field",
              "key" => "label",
              "dataType" => "String",
              "label" => "Product Name",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "is_active",
              "dataType" => "Boolean",
              "label" => "Is Active",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "product_code",
              "dataType" => "String",
              "label" => "Product Code",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "product_category_id:label",
              "dataType" => "Integer",
              "label" => "Product Category",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "product_brand:label",
              "dataType" => "String",
              "label" => "Product Brand",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "warranty_type_id:label",
              "dataType" => "Integer",
              "label" => "Warranty Type",
              "sortable" => TRUE,
            ],
            [
              "text" => "",
              "style" => "info-outline",
              "size" => "btn-xs",
              "icon" => "fa-bars",
              "links" => [
                [
                  "path" => "civicrm/inventory/device-model#?InventoryProduct1=[id]",
                  "icon" => "fa-external-link",
                  "text" => "Edit",
                  "style" => "default",
                  "condition" => [
                    "check user permission",
                    "=",
                    [
                      "edit inventory product",
                    ],
                  ],
                  "task" => "",
                  "entity" => "",
                  "action" => "",
                  "join" => "",
                  "target" => "crm-popup",
                ],
                [
                  "task" => "delete",
                  "entity" => "InventoryProduct",
                  "join" => "",
                  "target" => "crm-popup",
                  "icon" => "fa-trash",
                  "text" => "Delete Model",
                  "style" => "danger",
                  "path" => "",
                  "action" => "",
                  "condition" => [
                    "check user permission",
                    "=",
                    [
                      "edit inventory product",
                      "delete inventory product",
                    ],
                  ],
                ],
                [
                  "task" => "disable",
                  "entity" => "InventoryProduct",
                  "join" => "",
                  "target" => "crm-popup",
                  "icon" => "fa-toggle-off",
                  "text" => "Disable Model",
                  "style" => "default",
                  "path" => "",
                  "action" => "",
                  "condition" => [
                    "check user permission",
                    "=",
                    [
                      "edit inventory product",
                    ],
                  ],
                ],
                [
                  "task" => "enable",
                  "entity" => "InventoryProduct",
                  "join" => "",
                  "target" => "crm-popup",
                  "icon" => "fa-toggle-on",
                  "text" => "Enable Model",
                  "style" => "default",
                  "path" => "",
                  "action" => "",
                  "condition" => [
                    "check user permission",
                    "=",
                    [
                      "edit inventory product",
                    ],
                  ],
                ],
                [
                  "task" => "update",
                  "entity" => "InventoryProduct",
                  "join" => "",
                  "target" => "crm-popup",
                  "icon" => "fa-save",
                  "text" => "Update Model",
                  "style" => "default",
                  "path" => "",
                  "action" => "",
                  "condition" => [
                    "check user permission",
                    "=",
                    [
                      "edit inventory product",
                    ],
                  ],
                ],
              ],
              "type" => "menu",
              "alignment" => "text-right",
              "label" => "Action",
            ],
          ],
          "actions" => TRUE,
          "classes" => [
            "table",
            "table-striped",
            "table-bordered",
          ],
        ],
        'acl_bypass' => FALSE,
      ],
    ],
  ],
  // Saved Search for Inventory_Product_Variant.
  [
    'name' => 'SavedSearch_Inventory_Product_Variant',
    'entity' => 'SavedSearch',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Inventory_Product_Variant',
        'label' => E::ts('Inventory Product Variant'),
        'api_entity' => 'InventoryProductVariant',
        'api_params' => [
          'version' => 4,
          "select" => [
            "id",
            "product_id",
            "InventoryProductVariant_InventoryProduct_product_id_01.label",
            "contact_id",
            "InventoryProductVariant_Contact_contact_id_01.sort_name",
            "membership_id",
            "InventoryProductVariant_Membership_membership_id_01.membership_type_id:label",
            "product_variant_phone_number",
            "status:label",
            "product_variant_unique_id",
            "is_active",
            "is_suspended",
            "created_at",
            "updated_at",
          ],
          'orderBy' => [
            'weight DESC',
          ],
          "where" => [
            [
              "is_serialize",
              "=",
              TRUE,
            ],
          ],
          "groupBy" => [],
          "join" => [
            [
              "Contact AS InventoryProductVariant_Contact_contact_id_01",
              "LEFT",
              [
                "contact_id",
                "=",
                "InventoryProductVariant_Contact_contact_id_01.id",
              ],
            ],
            [
              "InventoryProduct AS InventoryProductVariant_InventoryProduct_product_id_01",
              "INNER",
              [
                "product_id",
                "=",
                "InventoryProductVariant_InventoryProduct_product_id_01.id",
              ],
              [
                "InventoryProductVariant_InventoryProduct_product_id_01.is_serialize",
                "=",
                TRUE,
              ],
            ],
            [
              "Membership AS InventoryProductVariant_Membership_membership_id_01",
              "LEFT",
              [
                "membership_id",
                "=",
                "InventoryProductVariant_Membership_membership_id_01.id",
              ],
            ],
            [
              "MembershipType AS InventoryProductVariant_Membership_membership_id_01_Membership_MembershipType_membership_type_id_01",
              "LEFT",
              [
                "InventoryProductVariant_Membership_membership_id_01.membership_type_id",
                "=",
                "InventoryProductVariant_Membership_membership_id_01_Membership_MembershipType_membership_type_id_01.id",
              ],
            ],
          ],
          "having" => [],
        ],
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  // Search Display for Inventory_Product_Variant.
  [
    'name' => 'SearchDisplay_Inventory_Product_Variant_Table_1',
    'entity' => 'SearchDisplay',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Inventory_Product_Variant_Table_1',
        'label' => 'Inventory Product List Table',
        'saved_search_id.name' => 'Inventory_Product_Variant',
        'type' => 'table',
        "settings" => [
          "description" => NULL,
          "sort" => [],
          "limit" => 50,
          "pager" => [],
          "placeholder" => 5,
          "columns" => [
            [
              "type" => "field",
              "key" => "InventoryProductVariant_InventoryProduct_product_id_01.label",
              "dataType" => "String",
              "label" => "Product Name",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "InventoryProductVariant_Contact_contact_id_01.sort_name",
              "dataType" => "String",
              "label" => "Contact Name",
              "sortable" => TRUE,
              "link" => [
                "path" => "",
                "entity" => "Contact",
                "action" => "view",
                "join" => "InventoryProductVariant_Contact_contact_id_01",
                "target" => "",
              ],
              "title" => "View Inventory Product Variant Contact",
            ],
            [
              "type" => "field",
              "key" => "InventoryProductVariant_Membership_membership_id_01.membership_type_id:label",
              "dataType" => "Integer",
              "label" => "Membership Type",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "product_variant_phone_number",
              "dataType" => "String",
              "label" => "Phone Number",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "status:label",
              "dataType" => "String",
              "label" => "Product Status",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "product_variant_unique_id",
              "dataType" => "String",
              "label" => "IMEI",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "is_active",
              "dataType" => "Boolean",
              "label" => "Is Active",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "is_suspended",
              "dataType" => "Boolean",
              "label" => "Is Suspended",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "created_at",
              "dataType" => "Timestamp",
              "label" => "Created Date",
              "sortable" => TRUE,
            ],
            [
              "type" => "field",
              "key" => "updated_at",
              "dataType" => "Timestamp",
              "label" => "Updated Date",
              "sortable" => TRUE,
            ],
            [
              "text" => "",
              "style" => "default",
              "size" => "btn-xs",
              "icon" => "fa-bars",
              "links" => [
                [
                  "path" => "/civicrm/inventory/device-from#?InventoryProductVariant1=[id]",
                  "icon" => "fa-external-link",
                  "text" => "Edit",
                  "style" => "default",
                  "condition" => [
                    "check user permission",
                    "=",
                    [
                      "edit inventory product",
                    ],
                  ],
                  "task" => "",
                  "entity" => "",
                  "action" => "",
                  "join" => "",
                  "target" => "crm-popup",
                ],
                [
                  "entity" => "Contact",
                  "action" => "view",
                  "join" => "InventoryProductVariant_Contact_contact_id_01",
                  "target" => "",
                  "icon" => "fa-external-link",
                  "text" => "View Contact",
                  "style" => "default",
                  "path" => "",
                  "task" => "",
                  "condition" => [],
                ],
                [
                  "entity" => "Contact",
                  "action" => "update",
                  "join" => "InventoryProductVariant_Contact_contact_id_01",
                  "target" => "",
                  "icon" => "fa-pencil",
                  "text" => "Update Contact",
                  "style" => "default",
                  "path" => "",
                  "task" => "",
                  "condition" => [],
                ],
                [
                  "entity" => "Membership",
                  "action" => "view",
                  "join" => "InventoryProductVariant_Membership_membership_id_01",
                  "target" => "crm-popup",
                  "icon" => "fa-external-link",
                  "text" => "View Membership",
                  "style" => "default",
                  "path" => "",
                  "task" => "",
                  "condition" => [],
                ],
                [
                  "entity" => "Membership",
                  "action" => "update",
                  "join" => "InventoryProductVariant_Membership_membership_id_01",
                  "target" => "crm-popup",
                  "icon" => "fa-pencil",
                  "text" => "Update Membership",
                  "style" => "default",
                  "path" => "",
                  "task" => "",
                  "condition" => [],
                ],
                [
                  "entity" => "Membership",
                  "action" => "renew",
                  "join" => "InventoryProductVariant_Membership_membership_id_01",
                  "target" => "crm-popup",
                  "icon" => "fa-external-link",
                  "text" => "Renew Membership",
                  "style" => "default",
                  "path" => "",
                  "task" => "",
                  "condition" => [],
                ],
                [
                  "task" => "disable",
                  "entity" => "InventoryProductVariant",
                  "join" => "",
                  "target" => "crm-popup",
                  "icon" => "fa-toggle-off",
                  "text" => "Disable Product",
                  "style" => "default",
                  "path" => "",
                  "action" => "",
                  "condition" => [
                    "check user permission",
                    "=",
                    [
                      "edit inventory product",
                    ],
                  ],
                ],
                [
                  "task" => "enable",
                  "entity" => "InventoryProductVariant",
                  "join" => "",
                  "target" => "crm-popup",
                  "icon" => "fa-toggle-on",
                  "text" => "Enable Product",
                  "style" => "default",
                  "path" => "",
                  "action" => "",
                  "condition" => [
                    "check user permission",
                    "=",
                    [
                      "edit inventory product",
                    ],
                  ],
                ],
                [
                  "task" => "update",
                  "entity" => "InventoryProductVariant",
                  "join" => "",
                  "target" => "crm-popup",
                  "icon" => "fa-save",
                  "text" => "Update Product",
                  "style" => "default",
                  "path" => "",
                  "action" => "",
                  "condition" => [
                    "check user permission",
                    "=",
                    [
                      "edit inventory product",
                    ],
                  ],
                ],
              ],
              "type" => "menu",
              "alignment" => "text-right",
            ],
          ],
          "actions" => TRUE,
          "classes" => [
            "table",
            "table-striped",
            "table-bordered",
          ],
        ],
        'acl_bypass' => FALSE,
      ],
    ],
  ],
];
