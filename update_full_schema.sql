-- SQL Migration for Service Management System Upgrade

-- 1. Update Users Table (Staff/Payroll/HR)
ALTER TABLE `users` 
ADD COLUMN `department_id` INT(11) NULL AFTER `role_id`,
ADD COLUMN `bank_name` VARCHAR(100) NULL AFTER `office_address`,
ADD COLUMN `account_no` VARCHAR(50) NULL AFTER `bank_name`,
ADD COLUMN `ifsc_code` VARCHAR(20) NULL AFTER `account_no`,
ADD COLUMN `salary` DECIMAL(10,2) DEFAULT 0.00 AFTER `ifsc_code`,
ADD COLUMN `joining_date` DATE NULL AFTER `salary`;

-- 2. Update Bookings Table (Tickets/Operational)
ALTER TABLE `bookings`
ADD COLUMN `internal_remarks` TEXT NULL AFTER `notes`,
ADD COLUMN `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium' AFTER `internal_remarks`,
ADD COLUMN `product_id` INT(11) NULL AFTER `priority`,
ADD COLUMN `rating` INT(1) DEFAULT 0 AFTER `product_id`;

-- 3. Create Departments Table
CREATE TABLE IF NOT EXISTS `departments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Create Service Time Slots Table
CREATE TABLE IF NOT EXISTS `service_time_slots` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `slot_name` VARCHAR(50) NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Create Appliance Types Table
CREATE TABLE IF NOT EXISTS `appliance_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Create Customer Products Table
CREATE TABLE IF NOT EXISTS `customer_products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `customer_id` INT(11) NOT NULL,
  `appliance_type_id` INT(11) NOT NULL,
  `model_no` VARCHAR(100) NULL,
  `serial_no` VARCHAR(100) NULL,
  `specifications` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`appliance_type_id`) REFERENCES `appliance_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Create Booking Status History Table
CREATE TABLE IF NOT EXISTS `booking_status_history` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `booking_id` INT(11) NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `remarks` TEXT NULL,
  `changed_by` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
