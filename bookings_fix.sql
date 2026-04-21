-- Consolidated Fix for Ticket Management & Bookings

-- 1. Ensure ticket history table exists
CREATE TABLE IF NOT EXISTS `ticket_status_history` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `booking_id` INT(11) NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `changed_by` INT(11) NOT NULL,
  `remarks` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Ensure internal remarks table exists
CREATE TABLE IF NOT EXISTS `ticket_remarks` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `booking_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `remark` TEXT NOT NULL,
  `visibility` ENUM('internal', 'public') DEFAULT 'internal',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Ensure time slots table exists
CREATE TABLE IF NOT EXISTS `time_slots` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `slot_range` VARCHAR(100) NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Insert default time slots if table is empty
INSERT INTO `time_slots` (`slot_range`, `is_active`) 
SELECT '09:00 AM - 11:00 AM', 1 WHERE NOT EXISTS (SELECT 1 FROM `time_slots`);
INSERT INTO `time_slots` (`slot_range`, `is_active`) 
SELECT '11:00 AM - 01:00 PM', 1 WHERE NOT EXISTS (SELECT 1 FROM `time_slots` WHERE `slot_range` = '11:00 AM - 01:00 PM');
INSERT INTO `time_slots` (`slot_range`, `is_active`) 
SELECT '02:00 PM - 04:00 PM', 1 WHERE NOT EXISTS (SELECT 1 FROM `time_slots` WHERE `slot_range` = '02:00 PM - 04:00 PM');
INSERT INTO `time_slots` (`slot_range`, `is_active`) 
SELECT '04:00 PM - 06:00 PM', 1 WHERE NOT EXISTS (SELECT 1 FROM `time_slots` WHERE `slot_range` = '04:00 PM - 06:00 PM');

-- 5. Add missing columns to bookings table
-- Note: Using a procedure to safely add columns if they don't exist
DELIMITER //
CREATE PROCEDURE AddBookingColumns()
BEGIN
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'bookings' AND COLUMN_NAME = 'priority') THEN
        ALTER TABLE `bookings` ADD COLUMN `priority` VARCHAR(20) DEFAULT 'medium' AFTER `complaint_description`;
    END IF;
    
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'bookings' AND COLUMN_NAME = 'estimated_cost') THEN
        ALTER TABLE `bookings` ADD COLUMN `estimated_cost` DECIMAL(10,2) DEFAULT 0.00 AFTER `priority`;
    END IF;
    
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'bookings' AND COLUMN_NAME = 'is_warranty') THEN
        ALTER TABLE `bookings` ADD COLUMN `is_warranty` TINYINT(1) DEFAULT 0 AFTER `estimated_cost`;
    END IF;

    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'bookings' AND COLUMN_NAME = 'appliance_type_id') THEN
        ALTER TABLE `bookings` ADD COLUMN `appliance_type_id` INT(11) DEFAULT NULL AFTER `notes`;
    END IF;

    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'bookings' AND COLUMN_NAME = 'customer_product_id') THEN
        ALTER TABLE `bookings` ADD COLUMN `customer_product_id` INT(11) DEFAULT NULL AFTER `appliance_type_id`;
    END IF;
END //
DELIMITER ;

CALL AddBookingColumns();
DROP PROCEDURE AddBookingColumns;
