ALTER TABLE civicrm_membership_type DROP COLUMN shippable_to;
ALTER TABLE civicrm_membership_type DROP COLUMN may_renew;
ALTER TABLE civicrm_membership_type DROP COLUMN fair_value;

ALTER TABLE civicrm_line_item DROP COLUMN sale_id;
ALTER TABLE civicrm_line_item DROP COLUMN product_variant_id;
ALTER TABLE civicrm_line_item DROP COLUMN product_id;
ALTER TABLE civicrm_line_item DROP COLUMN membership_id;
ALTER TABLE civicrm_line_item DROP COLUMN subtitle;
ALTER TABLE civicrm_line_item DROP COLUMN `type`;
ALTER TABLE civicrm_line_item DROP COLUMN additional_details;
