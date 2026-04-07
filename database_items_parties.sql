-- =============================================
-- Database Migration: Items & Parties System
-- Service Management System
-- =============================================

-- ----- LOOKUP TABLES -----

-- Item Units
CREATE TABLE IF NOT EXISTS `item_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `item_units` (`name`, `short_name`) VALUES
('Piece', 'PCS'),
('Kilogram', 'KG'),
('Gram', 'GM'),
('Litre', 'LTR'),
('Meter', 'MTR'),
('Box', 'BOX'),
('Pair', 'PR'),
('Set', 'SET'),
('Hour', 'HR'),
('Service', 'SRV'),
('Unit', 'UNT'),
('Dozen', 'DZN'),
('Feet', 'FT'),
('Square Feet', 'SQF'),
('Square Meter', 'SQM');

-- GST Rates
CREATE TABLE IF NOT EXISTS `gst_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `type` enum('GST','IGST') NOT NULL DEFAULT 'GST',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `gst_rates` (`name`, `rate`, `type`) VALUES
('None', 0.00, 'GST'),
('IGST@0%', 0.00, 'IGST'),
('GST@0%', 0.00, 'GST'),
('IGST@0.25%', 0.25, 'IGST'),
('GST@0.25%', 0.25, 'GST'),
('IGST@3%', 3.00, 'IGST'),
('GST@3%', 3.00, 'GST'),
('IGST@5%', 5.00, 'IGST'),
('GST@5%', 5.00, 'GST'),
('IGST@12%', 12.00, 'IGST'),
('GST@12%', 12.00, 'GST'),
('IGST@18%', 18.00, 'IGST'),
('GST@18%', 18.00, 'GST'),
('IGST@28%', 28.00, 'IGST'),
('GST@28%', 28.00, 'GST');

-- ----- ITEMS TABLE -----

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('product','service') NOT NULL DEFAULT 'product',
  `name` varchar(255) NOT NULL,
  `hsn_code` varchar(20) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `item_code` varchar(50) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,

  -- Tracking (product only)
  `batch_tracking` tinyint(1) NOT NULL DEFAULT 0,
  `serial_tracking` tinyint(1) NOT NULL DEFAULT 0,

  -- Sale Pricing
  `sale_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sale_price_tax_type` enum('without_tax','with_tax') NOT NULL DEFAULT 'without_tax',
  `discount_on_sale` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('percentage','amount') NOT NULL DEFAULT 'percentage',
  `wholesale_price` decimal(12,2) DEFAULT NULL,

  -- Purchase Pricing (product only)
  `purchase_price` decimal(12,2) DEFAULT NULL,
  `purchase_price_tax_type` enum('without_tax','with_tax') NOT NULL DEFAULT 'without_tax',

  -- Tax
  `gst_rate_id` int(11) DEFAULT 1,

  -- Stock (product only)
  `opening_qty` int(11) NOT NULL DEFAULT 0,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `at_price` decimal(12,2) DEFAULT NULL,
  `as_of_date` date DEFAULT NULL,
  `min_stock` int(11) NOT NULL DEFAULT 0,
  `location` varchar(255) DEFAULT NULL,

  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_item_unit` (`unit_id`),
  KEY `fk_item_gst` (`gst_rate_id`),
  CONSTRAINT `fk_item_unit` FOREIGN KEY (`unit_id`) REFERENCES `item_units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_item_gst` FOREIGN KEY (`gst_rate_id`) REFERENCES `gst_rates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ----- PARTY TABLES -----

-- Party Groups
CREATE TABLE IF NOT EXISTS `party_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `party_groups` (`name`) VALUES
('Sundry Debtors'),
('Sundry Creditors'),
('General'),
('Retail Customers'),
('Wholesale Customers'),
('Vendors'),
('Suppliers');

-- Parties (replaces client/vendor management)
CREATE TABLE IF NOT EXISTS `parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `gstin` varchar(15) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `party_group_id` int(11) DEFAULT NULL,

  -- GST Details
  `gst_type` enum('unregistered','registered_regular','registered_composition','special_economic_zone','deemed_export') NOT NULL DEFAULT 'unregistered',
  `state` varchar(100) DEFAULT NULL,

  -- Credit & Balance
  `opening_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `opening_balance_type` enum('to_receive','to_pay') NOT NULL DEFAULT 'to_receive',
  `credit_limit` decimal(12,2) DEFAULT NULL,

  -- Additional Fields (JSON for flexible custom fields)
  `additional_fields` text DEFAULT NULL,

  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_party_group` (`party_group_id`),
  CONSTRAINT `fk_party_group` FOREIGN KEY (`party_group_id`) REFERENCES `party_groups` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Party Addresses (multiple per party)
CREATE TABLE IF NOT EXISTS `party_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `party_id` int(11) NOT NULL,
  `type` enum('billing','shipping') NOT NULL DEFAULT 'billing',
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_address_party` (`party_id`),
  CONSTRAINT `fk_address_party` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
