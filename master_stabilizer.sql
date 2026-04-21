-- ======================================================
-- MASTER STABILIZER SCRIPT (Run this on Hostinger)
-- ======================================================

-- 1. NOTIFICATIONS TABLE
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `message` TEXT NOT NULL,
  `type` VARCHAR(20) DEFAULT 'info',
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. PREDEFINED PRODUCT NOTES LIBRARY
CREATE TABLE IF NOT EXISTS `predefined_product_notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `note_text` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. TICKET STATUS HISTORY
CREATE TABLE IF NOT EXISTS `ticket_status_history` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `booking_id` INT(11) NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `changed_by` INT(11) NOT NULL,
  `remarks` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. TICKET INTERNAL REMARKS
CREATE TABLE IF NOT EXISTS `ticket_remarks` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `booking_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `remark` TEXT NOT NULL,
  `visibility` ENUM('internal', 'public') DEFAULT 'internal',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. TIME SLOTS TABLE
CREATE TABLE IF NOT EXISTS `time_slots` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `slot_range` VARCHAR(100) NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. DEFAULT TIME SLOTS
INSERT INTO `time_slots` (`slot_range`, `is_active`) 
SELECT '09:00 AM - 11:00 AM', 1 WHERE NOT EXISTS (SELECT 1 FROM `time_slots`);
INSERT INTO `time_slots` (`slot_range`, `is_active`) 
SELECT '11:00 AM - 01:00 PM', 1 WHERE NOT EXISTS (SELECT 1 FROM `time_slots` WHERE `slot_range` = '11:00 AM - 01:00 PM');
INSERT INTO `time_slots` (`slot_range`, `is_active`) 
SELECT '02:00 PM - 04:00 PM', 1 WHERE NOT EXISTS (SELECT 1 FROM `time_slots` WHERE `slot_range` = '02:00 PM - 04:00 PM');
INSERT INTO `time_slots` (`slot_range`, `is_active`) 
SELECT '04:00 PM - 06:00 PM', 1 WHERE NOT EXISTS (SELECT 1 FROM `time_slots` WHERE `slot_range` = '04:00 PM - 06:00 PM');

-- 7. BOOKINGS COLUMN INFRASTRUCTURE (SAFE SYNC)
DELIMITER //
CREATE PROCEDURE FixAllBookingsInfrastructure()
BEGIN
    -- Columns for Advanced Bookings
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

CALL FixAllBookingsInfrastructure();
DROP PROCEDURE FixAllBookingsInfrastructure;
