-- =============================================
-- PHASE 7: Professional Staff & Payroll Enhancement
-- Service Management System
-- Please run this in your LOCAL phpMyAdmin SQL tab
-- =============================================

-- 1. Upgrade user_profiles with Financial & Professional fields
-- We use CREATE TABLE IF NOT EXISTS first just in case, but then ALTER to add columns
CREATE TABLE IF NOT EXISTS `user_profiles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `user_profiles` 
ADD COLUMN IF NOT EXISTS `account_holder_name` varchar(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `upi_id` varchar(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `pan_no` varchar(10) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `aadhar_no` varchar(12) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `driving_license` varchar(50) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `hra_allowance` decimal(10,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `travel_allowance` decimal(10,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `other_allowances` decimal(10,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `tds_deduction` decimal(10,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `pf_deduction` decimal(10,2) DEFAULT 0.00;

-- 2. Create Salary History Table (Archiving for Pay Slips)
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
