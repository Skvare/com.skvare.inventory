<?php

$jayParsedAry = [
  "name" => "Inventory_Product_List_Table_1",
  "label" => "Inventory Product List Table",
  "saved_search_id" => 11,
  "type" => "table",
  "settings" => [
    "description" => null,
    "sort" => [
    ],
    "limit" => 50,
    "pager" => [
    ],
    "placeholder" => 5,
    "columns" => [
      [
        "type" => "field",
        "key" => "label",
        "dataType" => "String",
        "label" => "Product Name",
        "sortable" => true
      ],
      [
        "type" => "field",
        "key" => "is_active",
        "dataType" => "Boolean",
        "label" => "Is Active",
        "sortable" => true
      ],
      [
        "type" => "field",
        "key" => "product_code",
        "dataType" => "String",
        "label" => "Product Code",
        "sortable" => true
      ],
      [
        "type" => "field",
        "key" => "product_category_id:label",
        "dataType" => "Integer",
        "label" => "Product Category",
        "sortable" => true
      ],
      [
        "type" => "field",
        "key" => "product_brand:label",
        "dataType" => "String",
        "label" => "Product Brand",
        "sortable" => true
      ],
      [
        "type" => "field",
        "key" => "warranty_type_id:label",
        "dataType" => "Integer",
        "label" => "Warranty Type",
        "sortable" => true
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
                "edit inventory product"
              ]
            ],
            "task" => "",
            "entity" => "",
            "action" => "",
            "join" => "",
            "target" => "crm-popup"
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
                "delete inventory product"
              ]
            ]
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
                "edit inventory product"
              ]
            ]
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
                "edit inventory product"
              ]
            ]
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
                "edit inventory product"
              ]
            ]
          ]
        ],
        "type" => "menu",
        "alignment" => "text-right",
        "label" => "Action"
      ]
    ],
    "actions" => true,
    "classes" => [
      "table",
      "table-striped",
      "table-bordered"
    ]
  ],
  "acl_bypass" => false
];

