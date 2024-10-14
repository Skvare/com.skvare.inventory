ALTER TABLE `civicrm_membership_type` ADD `shippable_to` VARCHAR(255) NOT NULL AFTER `is_active`;
ALTER TABLE `civicrm_membership_type` ADD `may_renew` tinyint NULL DEFAULT 0;
ALTER TABLE `civicrm_membership_type` ADD `fair_value` decimal(20,2) NULL COMMENT 'The fair market value of this item.'

ALTER TABLE `civicrm_line_item` ADD `sale_id` int unsigned DEFAULT NULL COMMENT 'sale id from inventory sale';
ALTER TABLE `civicrm_line_item` ADD `product_id` INT UNSIGNED NULL COMMENT 'product model id';
ALTER TABLE `civicrm_line_item` ADD `product_variant_id` int unsigned DEFAULT NULL COMMENT 'product variant id from inventory product variant table';
ALTER TABLE `civicrm_line_item` ADD `membership_id` int unsigned NULL COMMENT 'Membership ID Associated with product.';
ALTER TABLE `civicrm_line_item` ADD `subtitle` VARCHAR(255) NULL COMMENT 'Sub label';
ALTER TABLE `civicrm_line_item` ADD `additional_details` text COMMENT 'Additional product details';
