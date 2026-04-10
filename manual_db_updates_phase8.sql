-- Phase 8: Financial Reporting & Settlement Database Updates

-- 1. Table to record payments made to Service Partners (Vendors)
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
  KEY `vendor_id` (`vendor_id`),
  CONSTRAINT `vendor_payouts_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Add indices to existing tables for faster reporting performance
ALTER TABLE `invoices` ADD INDEX `idx_invoice_status` (`status`);
ALTER TABLE `invoices` ADD INDEX `idx_invoice_date` (`created_at`);
ALTER TABLE `expenses` ADD INDEX `idx_expense_status` (`status`);
ALTER TABLE `salary_history` ADD INDEX `idx_salary_date` (`generated_at`);

-- 3. Configuration table for financial settings
CREATE TABLE IF NOT EXISTS `finance_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `finance_settings` (`setting_key`, `setting_value`) VALUES 
('currency', 'INR'),
('tax_enabled', '1'),
('tax_percentage', '18');
