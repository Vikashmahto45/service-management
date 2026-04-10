-- =============================================
-- PHASE 9: AMC (Annual Maintenance Contracts)
-- Service Management System
-- Please run this in your LOCAL phpMyAdmin SQL tab
-- =============================================

-- 1. Main AMC Contracts Table
CREATE TABLE IF NOT EXISTS `amc_contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `party_id` int(11) NOT NULL,
  `contract_no` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `visits_per_year` int(2) DEFAULT 4,
  `status` enum('active','expired','cancelled','pending') DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `contract_no` (`contract_no`),
  FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. AMC Items (Mapping products to contracts for Multi-Appliance support)
CREATE TABLE IF NOT EXISTS `amc_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amc_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`amc_id`) REFERENCES `amc_contracts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `customer_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Scheduled AMC Maintenance Visits
CREATE TABLE IF NOT EXISTS `amc_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amc_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `actual_date` date DEFAULT NULL,
  `status` enum('pending','completed','cancelled','rescheduled') DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `completed_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`amc_id`) REFERENCES `amc_contracts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Indices for Performance
ALTER TABLE `amc_contracts` ADD INDEX `idx_amc_expiry` (`end_date`);
ALTER TABLE `amc_contracts` ADD INDEX `idx_amc_status` (`status`);
ALTER TABLE `amc_visits` ADD INDEX `idx_visit_date` (`scheduled_date`);
ALTER TABLE `amc_visits` ADD INDEX `idx_visit_status` (`status`);
