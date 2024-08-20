ALTER TABLE `civicrm_membership_type` ADD `shippable_to` VARCHAR(255) NOT NULL AFTER `is_active`;
ALTER TABLE `civicrm_membership_type` ADD `signup_fee` decimal(18,9) DEFAULT 0 AFTER `shippable_to`;
ALTER TABLE `civicrm_membership_type` ADD `renewal_fee` decimal(18,9) DEFAULT 0 AFTER `signup_fee`;
