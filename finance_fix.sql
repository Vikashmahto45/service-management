-- =============================================
-- FINANCE & VENDOR PAYOUTS SCHEMA FIX
-- Use this to fix the Vendor Settlements and Ledger errors
-- Run this in your Hostinger phpMyAdmin -> SQL tab
-- =============================================

-- 1. Create missing user_profiles table (for bank details)
CREATE TABLE IF NOT EXISTS `user_profiles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `account_holder_name` varchar(255) DEFAULT NULL,
    `upi_id` varchar(100) DEFAULT NULL,
    `pan_no` varchar(10) DEFAULT NULL,
    `aadhar_no` varchar(12) DEFAULT NULL,
    `driving_license` varchar(50) DEFAULT NULL,
    `hra_allowance` decimal(10,2) DEFAULT 0.00,
    `travel_allowance` decimal(10,2) DEFAULT 0.00,
    `other_allowances` decimal(10,2) DEFAULT 0.00,
    `tds_deduction` decimal(10,2) DEFAULT 0.00,
    `pf_deduction` decimal(10,2) DEFAULT 0.00,
    `designation` varchar(100) DEFAULT NULL,
    `joining_date` date DEFAULT NULL,
    `phone_alt` varchar(20) DEFAULT NULL,
    `address` text DEFAULT NULL,
    `emergency_contact` varchar(100) DEFAULT NULL,
    `bank_name` varchar(100) DEFAULT NULL,
    `account_no` varchar(50) DEFAULT NULL,
    `ifsc_code` varchar(20) DEFAULT NULL,
    `basic_salary` decimal(10,2) DEFAULT 0.00,
    `payroll_status` varchar(20) DEFAULT 'active',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Create missing vendor_payouts table
CREATE TABLE IF NOT EXISTS `vendor_payouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payout_date` date NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Bank Transfer',
  `transaction_id` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Create missing salary_history table (required for ledger)
CREATE TABLE IF NOT EXISTS `salary_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `total_allowances` decimal(10,2) DEFAULT 0.00,
  `total_deductions` decimal(10,2) DEFAULT 0.00,
  `net_salary` decimal(10,2) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Create missing expenses table
CREATE TABLE IF NOT EXISTS `expenses` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `amount` decimal(10,2) NOT NULL,
    `description` text DEFAULT NULL,
    `receipt_image` varchar(255) DEFAULT NULL,
    `status` enum('pending', 'approved', 'rejected') DEFAULT 'pending',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Create missing invoices table
CREATE TABLE IF NOT EXISTS `invoices` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `booking_id` int(11) NOT NULL,
    `customer_id` int(11) NOT NULL,
    `invoice_number` varchar(50) NOT NULL UNIQUE,
    `amount` decimal(10,2) NOT NULL,
    `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
    `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
    `total_amount` decimal(10,2) NOT NULL,
    `status` enum('unpaid', 'paid', 'cancelled') DEFAULT 'unpaid',
    `transaction_id` varchar(255) DEFAULT NULL,
    `payment_date` datetime DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
