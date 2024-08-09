-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the existing tables - this section generated from drop.tpl
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_inventory_product_changelog`;
DROP TABLE IF EXISTS `civicrm_inventory_warehouse_transfer`;
DROP TABLE IF EXISTS `civicrm_inventory_sales_detail`;
DROP TABLE IF EXISTS `civicrm_inventory_purchase_order_detail`;
DROP TABLE IF EXISTS `civicrm_inventory_product_variant`;
DROP TABLE IF EXISTS `civicrm_inventory_shipment_labels`;
DROP TABLE IF EXISTS `civicrm_inventory_sales`;
DROP TABLE IF EXISTS `civicrm_inventory_purchase_order`;
DROP TABLE IF EXISTS `civicrm_inventory`;
DROP TABLE IF EXISTS `civicrm_inventory_warehouse`;
DROP TABLE IF EXISTS `civicrm_inventory_supplier`;
DROP TABLE IF EXISTS `civicrm_inventory_shipment`;
DROP TABLE IF EXISTS `civicrm_inventory_referrals`;
DROP TABLE IF EXISTS `civicrm_inventory_product_meta`;
DROP TABLE IF EXISTS `civicrm_inventory_product_membership`;
DROP TABLE IF EXISTS `civicrm_inventory_product`;
DROP TABLE IF EXISTS `civicrm_inventory_category`;

SET FOREIGN_KEY_CHECKS=1;
-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_inventory_category
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_category` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventoryCategory ID',
  `parent_id` int unsigned COMMENT 'FK to Parent Category',
  `title` varchar(256) NOT NULL,
  `meta_title` varchar(256) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `content` text COMMENT 'Category Content.',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_category_parent_id FOREIGN KEY (`parent_id`) REFERENCES `civicrm_inventory_category`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_product
-- *
-- * Product table.
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_product` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventoryProduct ID',
  `product_code` varchar(100) NOT NULL COMMENT 'Product Code SKU.',
  `external_code` varchar(100) NULL,
  `product_name` varchar(100) NOT NULL,
  `product_description` varchar(512) NULL,
  `product_brand` varchar(100) NULL,
  `product_note` text COMMENT 'Product details.',
  `product_category_id` int unsigned NOT NULL COMMENT 'FK to Category',
  `is_disable` tinyint NULL DEFAULT 0,
  `is_discontinued` tinyint NULL DEFAULT 0,
  `image_actual` varchar(100) NULL COMMENT 'File url.',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_product_product_category_id FOREIGN KEY (`product_category_id`) REFERENCES `civicrm_inventory_category`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_product_membership
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_product_membership` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Inventory Product Membership ID',
  `product_variant_sku_code` varchar(100) NULL,
  `product_name` varchar(100) NULL,
  `membership_type_id` int unsigned NOT NULL COMMENT 'Membership Type Associated with product.',
  `contact_id` int unsigned COMMENT 'FK to Contact',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_product_membership_membership_type_id FOREIGN KEY (`membership_type_id`) REFERENCES `civicrm_membership_type`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_product_membership_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_product_meta
-- *
-- * Product Meta details.
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_product_meta` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventoryProductMeta ID',
  `product_id` int unsigned COMMENT 'FK to Product',
  `product_meta_key` varchar(50) NOT NULL,
  `product_meta_content` text COMMENT 'Product Meta Content.',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_product_meta_product_id FOREIGN KEY (`product_id`) REFERENCES `civicrm_inventory_product`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_referrals
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_referrals` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventoryReferrals ID',
  `creator_id` int unsigned COMMENT 'FK to Contact',
  `consumer_id` int unsigned COMMENT 'FK to Contact',
  `created_date` datetime,
  `before_end_date` datetime,
  `after_end_date` datetime,
  `referral_code` varchar(100) NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_referrals_creator_id FOREIGN KEY (`creator_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE CASCADE,
  CONSTRAINT FK_civicrm_inventory_referrals_consumer_id FOREIGN KEY (`consumer_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_shipment
-- *
-- * Shipment Details
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_shipment` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventoryShipment ID',
  `contact_id` int unsigned COMMENT 'FK to Contact',
  `created_date` timestamp COMMENT 'When was the shipment was created.',
  `modified_id` int unsigned COMMENT 'FK to Contact ID of person under whose credentials this data modification was made.',
  `updated_date` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shipped_date` datetime,
  `is_shipped` tinyint NULL DEFAULT 0,
  `is_finished` tinyint NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `index_created_date`(created_date),
  INDEX `index_updated_date`(updated_date),
  CONSTRAINT FK_civicrm_inventory_shipment_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE CASCADE,
  CONSTRAINT FK_civicrm_inventory_shipment_modified_id FOREIGN KEY (`modified_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_supplier
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_supplier` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Inventory Supplier ID',
  `supplier_name` varchar(100) NOT NULL,
  `address_id` int unsigned COMMENT 'Supplier Location',
  `contact_id` int unsigned COMMENT 'FK to Contact',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_supplier_address_id FOREIGN KEY (`address_id`) REFERENCES `civicrm_address`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_supplier_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE RESTRICT
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_warehouse
-- *
-- * WareHouse Table
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_warehouse` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Inventory Warehouse ID',
  `name` varchar(100) NULL,
  `address_id` int unsigned COMMENT 'FK to Contact',
  `is_refrigerated` tinyint NULL DEFAULT 0,
  `size` varchar(100) NULL,
  `unused_size` varchar(100) NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_warehouse_address_id FOREIGN KEY (`address_id`) REFERENCES `civicrm_address`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory
-- *
-- * Product Inventory
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Inventory ID',
  `product_variant_sku_code` varchar(100) NULL,
  `warehouse_id` int unsigned COMMENT 'FK to Warehouse',
  `quantity_available` int unsigned COMMENT 'The quantity on hand.',
  `minimum_quantity_stock_level` int unsigned COMMENT 'The minimum number of units required to ensure no shortages occur at this warehouse.',
  `maximum_quantity_stock_level` int unsigned COMMENT 'The maximum number of units desired in stock, i.e. to avoid overstocking.',
  `reorder_point` int unsigned COMMENT 'The minimum number of units required to ensure no shortages occur at this warehouse.',
  `row` varchar(256) NULL COMMENT 'Use to locate the item in warehouse',
  `shelf` varchar(256) NULL COMMENT 'Use to locate the item in warehouse',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_warehouse_id FOREIGN KEY (`warehouse_id`) REFERENCES `civicrm_inventory_warehouse`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_purchase_order
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_purchase_order` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventoryPurchaseOrder ID',
  `order_date` datetime,
  `contact_id` int unsigned COMMENT 'FK to Contact',
  `supplier_id` int unsigned COMMENT 'FK to Supplier',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_purchase_order_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_purchase_order_supplier_id FOREIGN KEY (`supplier_id`) REFERENCES `civicrm_inventory_supplier`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_sales
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_sales` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventorySales ID',
  `contact_id` int unsigned COMMENT 'FK to Contact',
  `sale_date` datetime,
  `status_id` varchar(100) NOT NULL COMMENT 'Sales Status: \'placed\', \'shipped\', \'completed\'',
  `is_shipping_required` tinyint NULL DEFAULT 0,
  `shipment_id` int unsigned COMMENT 'FK to shipments',
  `is_paid` tinyint NULL DEFAULT 0,
  `is_fulfilled` tinyint NULL DEFAULT 0,
  `needs_assignment` tinyint NULL DEFAULT 0,
  `has_assignment` tinyint NULL DEFAULT 0,
  `is_tracking_sent` tinyint NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_sales_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_sales_shipment_id FOREIGN KEY (`shipment_id`) REFERENCES `civicrm_inventory_shipment`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_shipment_labels
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_shipment_labels` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventoryShipmentLabels ID',
  `created_date` datetime,
  `updated_date` datetime,
  `sales_id` int unsigned COMMENT 'FK to Sales',
  `is_valid` tinyint NULL DEFAULT 0,
  `is_paid` tinyint NULL DEFAULT 0,
  `has_error` tinyint NULL DEFAULT 0,
  `provider` varchar(100) NULL,
  `amount` decimal(20,2) NOT NULL COMMENT 'Shipment Amount',
  `currency` varchar(4) NULL,
  `resource_id` varchar(100) NULL,
  `tracking_id` varchar(100) NULL,
  `shipment` text COMMENT 'Shipment details.',
  `purchase` text COMMENT 'Shipment Purchase details.',
  `contact_id` int unsigned COMMENT 'FK to Contact',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_shipment_labels_sales_id FOREIGN KEY (`sales_id`) REFERENCES `civicrm_inventory_sales`(`id`) ON DELETE CASCADE,
  CONSTRAINT FK_civicrm_inventory_shipment_labels_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_product_variant
-- *
-- * Product Variant table
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_product_variant` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Inventory Product Variant ID',
  `product_id` int unsigned COMMENT 'FK to Product',
  `listed_price` decimal(20,2) NOT NULL COMMENT 'Product listed price',
  `current_price` decimal(20,2) NOT NULL COMMENT 'Product Current price',
  `product_variant_sku_code` varchar(100) NULL,
  `product_variant_phone_number` varchar(100) NULL COMMENT 'Phone number linked with device.',
  `product_variant_unique_id` varchar(100) NULL COMMENT 'e.g IMEI (International Mobile Equipment Identity) number .',
  `product_variant_details` text COMMENT 'Product Variant details.',
  `is_disable` tinyint NULL DEFAULT 0,
  `is_discontinued` tinyint NULL DEFAULT 0,
  `packed_weight` decimal(20,2) NULL COMMENT 'Packed Weight',
  `packed_height` decimal(20,2) NULL COMMENT 'Packed Height',
  `packed_width` decimal(20,2) NULL COMMENT 'Packed Width',
  `packed_depth` decimal(20,2) NULL COMMENT 'Packed Depth',
  `product_variant_battery` varchar(100) NULL COMMENT 'Battery backup time.',
  `product_variant_speed` varchar(100) NULL COMMENT 'Device Speed.',
  `antenna` tinyint NULL DEFAULT 0,
  `tether` tinyint NULL DEFAULT 0,
  `powerbank` tinyint NULL DEFAULT 0,
  `batteryless` tinyint NULL DEFAULT 0,
  `network_4g` tinyint NULL DEFAULT 0,
  `network_5g` tinyint NULL DEFAULT 0,
  `has_sim` tinyint NULL DEFAULT 0,
  `has_device` tinyint NULL DEFAULT 0,
  `warranty_type_id` int unsigned NULL,
  `image_thumbnail` varchar(100) NULL,
  `image_actual` varchar(100) NULL,
  `uom` varchar(100) NULL COMMENT 'Feet, pounds, and gallons are all examples of units of measure.',
  `product_variant_status` varchar(100) NULL,
  `replaced_product_id` int unsigned COMMENT 'Optional Product.',
  `is_serialize` tinyint NULL DEFAULT 0,
  `membership_id` int unsigned NULL COMMENT 'Membership ID Associated with product.',
  `warranty_start_date` datetime,
  `warranty_end_date` datetime,
  `sales_id` int unsigned NULL COMMENT 'Sales ID Associated with sales tables.',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_product_variant_product_id FOREIGN KEY (`product_id`) REFERENCES `civicrm_inventory_product`(`id`) ON DELETE CASCADE,
  CONSTRAINT FK_civicrm_inventory_product_variant_replaced_product_id FOREIGN KEY (`replaced_product_id`) REFERENCES `civicrm_inventory_product_variant`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_product_variant_membership_id FOREIGN KEY (`membership_id`) REFERENCES `civicrm_membership`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_product_variant_sales_id FOREIGN KEY (`sales_id`) REFERENCES `civicrm_inventory_sales`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_purchase_order_detail
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_purchase_order_detail` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventoryPurchaseOrderDetail ID',
  `order_id` int unsigned COMMENT 'FK to Order',
  `product_variant_id` int unsigned COMMENT 'FK to Product Variant',
  `supplier_id` int unsigned COMMENT 'FK to Supplier',
  `warehouse_id` int unsigned COMMENT 'FK to Warehouse',
  `order_quantity` int unsigned COMMENT 'Order quantiy to supplier',
  `expected_date` datetime,
  `actual_date` datetime,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_purchase_order_detail_order_id FOREIGN KEY (`order_id`) REFERENCES `civicrm_inventory_purchase_order`(`id`) ON DELETE CASCADE,
  CONSTRAINT FK_civicrm_inventory_purchase_order_detail_product_variant_id FOREIGN KEY (`product_variant_id`) REFERENCES `civicrm_inventory_product_variant`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_purchase_order_detail_supplier_id FOREIGN KEY (`supplier_id`) REFERENCES `civicrm_inventory_supplier`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_purchase_order_detail_warehouse_id FOREIGN KEY (`warehouse_id`) REFERENCES `civicrm_inventory_warehouse`(`id`) ON DELETE RESTRICT
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_sales_detail
-- *
-- * Sale order details
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_sales_detail` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Inventory Sales Detail ID',
  `sales_id` int unsigned COMMENT 'FK to Sales',
  `product_variant_id` int unsigned COMMENT 'FK to Product Variant',
  `product_quantity` int unsigned COMMENT 'The quantity sold.',
  `warehouse_id` int unsigned COMMENT 'FK to Warehouse',
  `purchase_price` decimal(20,2) NOT NULL COMMENT 'Product Purchase price',
  `product_title` varchar(100) NULL,
  `product_sub_title` varchar(100) NULL,
  `additional_details` text COMMENT 'Additional product details',
  `membership_id` int unsigned NULL COMMENT 'Membership Associated with product.',
  `contribution_id` int unsigned NULL COMMENT 'FK to payment table.',
  `type` varchar(100) NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_sales_detail_sales_id FOREIGN KEY (`sales_id`) REFERENCES `civicrm_inventory_sales`(`id`) ON DELETE CASCADE,
  CONSTRAINT FK_civicrm_inventory_sales_detail_product_variant_id FOREIGN KEY (`product_variant_id`) REFERENCES `civicrm_inventory_product_variant`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_sales_detail_warehouse_id FOREIGN KEY (`warehouse_id`) REFERENCES `civicrm_inventory_warehouse`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_sales_detail_membership_id FOREIGN KEY (`membership_id`) REFERENCES `civicrm_membership`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_sales_detail_contribution_id FOREIGN KEY (`contribution_id`) REFERENCES `civicrm_contribution`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_warehouse_transfer
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_warehouse_transfer` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Inventory Warehouse Transfer ID',
  `lot_id` varchar(100) NOT NULL,
  `product_variant_id` int unsigned COMMENT 'FK to Product Variant',
  `from_warehouse_id` int unsigned COMMENT 'FK to Warehouse',
  `to_warehouse_id` int unsigned COMMENT 'FK to Warehouse',
  `from_contact_id` int unsigned COMMENT 'FK to Contact',
  `to_contact_id` int unsigned COMMENT 'FK to Contact',
  `created_date` datetime,
  `status_id` varchar(100) NOT NULL COMMENT 'IN = into location, OUT = OUT of location',
  `status_date` datetime,
  `from_stock_quantity` int unsigned COMMENT 'The quantity sent.',
  `received_stock_quantity` int unsigned COMMENT 'The quantity Received.',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_warehouse_transfer_product_variant_id FOREIGN KEY (`product_variant_id`) REFERENCES `civicrm_inventory_product_variant`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_warehouse_transfer_from_warehouse_id FOREIGN KEY (`from_warehouse_id`) REFERENCES `civicrm_inventory_warehouse`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_warehouse_transfer_to_warehouse_id FOREIGN KEY (`to_warehouse_id`) REFERENCES `civicrm_inventory_warehouse`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_warehouse_transfer_from_contact_id FOREIGN KEY (`from_contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_warehouse_transfer_to_contact_id FOREIGN KEY (`to_contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_product_changelog
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_product_changelog` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventoryProductChangelog ID',
  `contact_id` int unsigned COMMENT 'FK to Contact',
  `product_variant_id` int unsigned COMMENT 'FK to Product Variant',
  `batch_id` int unsigned COMMENT 'FK to Contact',
  `created_date` datetime,
  `status_id` varchar(100) NOT NULL COMMENT 'UPDATE,REACTIVATE,TERMINATE,SUSPEND',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_product_changelog_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE CASCADE,
  CONSTRAINT FK_civicrm_inventory_product_changelog_product_variant_id FOREIGN KEY (`product_variant_id`) REFERENCES `civicrm_inventory_product_variant`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;
