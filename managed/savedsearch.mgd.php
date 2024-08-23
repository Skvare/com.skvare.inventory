<?php

use CRM_Inventory_ExtensionUtil as E;

return [
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
	        "warranty_type_id:label"
	      ],
          'orderBy' => [
            'weight DESC',
          ],
	      "where" => [
	        [
	          "is_serialize",
	          "=",
	          true
	        ],
	        [
	          "is_active",
	          "=",
	          true
	        ]
	      ],
	      "groupBy" => [],
	      "join"=> [],
	      "having"=> []
        ],
      ],
      'match' => [
        'name',
      ],
    ],
  ]
];
