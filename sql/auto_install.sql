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

DROP TABLE IF EXISTS `civicrm_inventory_sales_detail`;
DROP TABLE IF EXISTS `civicrm_inventory_order_detail`;
DROP TABLE IF EXISTS `civicrm_inventory_order`;
DROP TABLE IF EXISTS `civicrm_inventory`;
DROP TABLE IF EXISTS `civicrm_inventory_warehouse_transfer`;
DROP TABLE IF EXISTS `civicrm_inventory_warehouse`;
DROP TABLE IF EXISTS `civicrm_inventory_supplier`;
DROP TABLE IF EXISTS `civicrm_inventory_sales`;
DROP TABLE IF EXISTS `civicrm_inventory_product_variant`;
DROP TABLE IF EXISTS `civicrm_inventory_product_meta`;
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
  `product_variant_details` text COMMENT 'Product Variant details.',
  `is_disable` tinyint NULL DEFAULT 0,
  `is_discontinued` tinyint NULL DEFAULT 0,
  `packed_weight` decimal(20,2) NULL COMMENT 'Packed Weight',
  `packed_height` decimal(20,2) NULL COMMENT 'Packed Height',
  `packed_width` decimal(20,2) NULL COMMENT 'Packed Width',
  `packed_depth` decimal(20,2) NULL COMMENT 'Packed Depth',
  `image_thumbnail` varchar(100) NULL,
  `image_actual` varchar(100) NULL,
  `uom` varchar(100) NULL COMMENT 'Feet, pounds, and gallons are all examples of units of measure.',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_product_variant_product_id FOREIGN KEY (`product_id`) REFERENCES `civicrm_inventory_product`(`id`) ON DELETE CASCADE
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
  `status_id` varchar(100) NOT NULL COMMENT 'Sales Status',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_sales_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL
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
-- * civicrm_inventory
-- *
-- * Product Inventory
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Inventory ID',
  `product_variant_id` int unsigned COMMENT 'FK to Product Variant',
  `warehouse_id` int unsigned COMMENT 'FK to Warehouse',
  `quantity_available` int unsigned COMMENT 'The quantity on hand.',
  `minimum_quantity_stock_level` int unsigned COMMENT 'The minimum number of units required to ensure no shortages occur at this warehouse.',
  `maximum_quantity_stock_level` int unsigned COMMENT 'The maximum number of units desired in stock, i.e. to avoid overstocking.',
  `reorder_point` int unsigned COMMENT 'The minimum number of units required to ensure no shortages occur at this warehouse.',
  `row` varchar(256) NULL COMMENT 'Use to locate the item in warehouse',
  `shelf` varchar(256) NULL COMMENT 'Use to locate the item in warehouse',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_product_variant_id FOREIGN KEY (`product_variant_id`) REFERENCES `civicrm_inventory_product_variant`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_warehouse_id FOREIGN KEY (`warehouse_id`) REFERENCES `civicrm_inventory_warehouse`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_order
-- *
-- * Orderdetail from supplier
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_order` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Inventory Order ID',
  `order_date` datetime,
  `contact_id` int unsigned COMMENT 'FK to Contact',
  `supplier_id` int unsigned COMMENT 'FK to Supplier',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_order_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_order_supplier_id FOREIGN KEY (`supplier_id`) REFERENCES `civicrm_inventory_supplier`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_inventory_order_detail
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_inventory_order_detail` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique InventoryOrderDetail ID',
  `order_id` int unsigned COMMENT 'FK to Order',
  `product_variant_id` int unsigned COMMENT 'FK to Product Variant',
  `supplier_id` int unsigned COMMENT 'FK to Supplier',
  `warehouse_id` int unsigned COMMENT 'FK to Warehouse',
  `order_quantity` int unsigned COMMENT 'Order quantiy to supplier',
  `expected_date` datetime,
  `actual_date` datetime,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_order_detail_order_id FOREIGN KEY (`order_id`) REFERENCES `civicrm_inventory_order`(`id`) ON DELETE CASCADE,
  CONSTRAINT FK_civicrm_inventory_order_detail_product_variant_id FOREIGN KEY (`product_variant_id`) REFERENCES `civicrm_inventory_product_variant`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_order_detail_supplier_id FOREIGN KEY (`supplier_id`) REFERENCES `civicrm_inventory_supplier`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_order_detail_warehouse_id FOREIGN KEY (`warehouse_id`) REFERENCES `civicrm_inventory_warehouse`(`id`) ON DELETE RESTRICT
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
  `product_unique_id` varchar(100) NULL COMMENT 'e.g IMEI (International Mobile Equipment Identity) number .',
  `purchase_price` decimal(20,2) NOT NULL COMMENT 'Product Purchase price',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_inventory_sales_detail_sales_id FOREIGN KEY (`sales_id`) REFERENCES `civicrm_inventory_sales`(`id`) ON DELETE CASCADE,
  CONSTRAINT FK_civicrm_inventory_sales_detail_product_variant_id FOREIGN KEY (`product_variant_id`) REFERENCES `civicrm_inventory_product_variant`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_inventory_sales_detail_warehouse_id FOREIGN KEY (`warehouse_id`) REFERENCES `civicrm_inventory_warehouse`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;
