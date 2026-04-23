-- Migration: Map Location integration for Bookings
ALTER TABLE `bookings` 
ADD COLUMN `latitude` DECIMAL(10, 8) DEFAULT NULL AFTER `notes`,
ADD COLUMN `longitude` DECIMAL(11, 8) DEFAULT NULL AFTER `latitude`,
ADD COLUMN `formatted_address` TEXT DEFAULT NULL AFTER `longitude`;
