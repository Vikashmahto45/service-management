-- DATABASE UPDATE FOR PHASE 9: TRACKING STAFF COLLECTIONS
-- Run this in your Hostinger phpMyAdmin

ALTER TABLE invoices ADD COLUMN collected_by INT(11) AFTER customer_id;

-- Optional: If your invoices table was missing other columns from previous updates, ensure it looks like this:
-- CREATE TABLE IF NOT EXISTS `invoices` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `booking_id` int(11) DEFAULT NULL,
--   `customer_id` int(11) DEFAULT NULL,
--   `collected_by` int(11) DEFAULT NULL,
--   `invoice_number` varchar(20) DEFAULT NULL,
--   `total_amount` decimal(10,2) DEFAULT NULL,
--   `status` enum('pending','paid','cancelled') DEFAULT 'pending',
--   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
