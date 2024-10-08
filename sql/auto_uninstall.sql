-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from drop.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the existing tables
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_inventory_product_changelog`;
DROP TABLE IF EXISTS `civicrm_inventory_warehouse_transfer`;
DROP TABLE IF EXISTS `civicrm_inventory_sales_detail`;
DROP TABLE IF EXISTS `civicrm_inventory_purchase_order_detail`;
DROP TABLE IF EXISTS `civicrm_inventory_product_variant_replacement`;
DROP TABLE IF EXISTS `civicrm_inventory_product_variant`;
DROP TABLE IF EXISTS `civicrm_inventory_shipment_labels`;
DROP TABLE IF EXISTS `civicrm_inventory_sales`;
DROP TABLE IF EXISTS `civicrm_inventory_purchase_order`;
DROP TABLE IF EXISTS `civicrm_inventory_product_meta`;
DROP TABLE IF EXISTS `civicrm_inventory_product_membership`;
DROP TABLE IF EXISTS `civicrm_inventory_product`;
DROP TABLE IF EXISTS `civicrm_inventory_warehouse`;
DROP TABLE IF EXISTS `civicrm_inventory_supplier`;
DROP TABLE IF EXISTS `civicrm_inventory_shipment`;
DROP TABLE IF EXISTS `civicrm_inventory_referrals`;
DROP TABLE IF EXISTS `civicrm_inventory_category`;
DROP TABLE IF EXISTS `civicrm_inventory_billing_plans`;
DROP TABLE IF EXISTS `civicrm_inventory_batch`;

SET FOREIGN_KEY_CHECKS=1;
