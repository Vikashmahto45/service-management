-- =============================================
-- CRITICAL DATABASE SCHEMA FIX
-- Use this to fix the Ticket Management 500 error
-- Run this in your Hostinger phpMyAdmin -> SQL tab
-- =============================================

-- 1. Create missing appliance_types table
CREATE TABLE IF NOT EXISTS `appliance_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Create missing customer_products table
CREATE TABLE IF NOT EXISTS `customer_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `appliance_type_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `model_no` varchar(100) DEFAULT NULL,
  `serial_no` varchar(100) DEFAULT NULL,
  `installation_date` date DEFAULT NULL,
  `warranty_expiry` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Create missing ticket_status_history table
CREATE TABLE IF NOT EXISTS `ticket_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Create missing ticket_remarks table
CREATE TABLE IF NOT EXISTS `ticket_remarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `remark` text NOT NULL,
  `visibility` enum('internal','public') DEFAULT 'internal',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Add missing columns to bookings table
-- We use 'IF NOT EXISTS' logic by checking columns (this part might need manual adjustment if your DB version doesn't support it, but these ALTERs are standard)

ALTER TABLE `bookings` 
ADD COLUMN IF NOT EXISTS `appliance_type_id` int(11) DEFAULT NULL AFTER `notes`,
ADD COLUMN IF NOT EXISTS `customer_product_id` int(11) DEFAULT NULL AFTER `appliance_type_id`,
ADD COLUMN IF NOT EXISTS `complaint_description` text DEFAULT NULL AFTER `customer_product_id`,
ADD COLUMN IF NOT EXISTS `priority` enum('low','medium','high','urgent') DEFAULT 'medium' AFTER `complaint_description`,
ADD COLUMN IF NOT EXISTS `estimated_cost` decimal(10,2) DEFAULT 0.00 AFTER `priority`,
ADD COLUMN IF NOT EXISTS `is_warranty` tinyint(1) DEFAULT 0 AFTER `estimated_cost`,
ADD COLUMN IF NOT EXISTS `assigned_to` int(11) DEFAULT NULL AFTER `is_warranty`;

-- 6. Add Foreign Key constraints for the new columns
-- (Optional but recommended for data integrity)
ALTER TABLE `bookings` ADD CONSTRAINT `fk_booking_staff` FOREIGN KEY IF NOT EXISTS (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;
