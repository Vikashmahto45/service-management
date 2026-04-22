-- Migration: Add Completion Details to Bookings and Complaints
-- Service Management System

-- Update Bookings Table
ALTER TABLE `bookings` 
ADD COLUMN `completion_notes` TEXT DEFAULT NULL,
ADD COLUMN `completion_image` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `completed_at` DATETIME DEFAULT NULL;

-- Update Complaints Table
ALTER TABLE `complaints` 
ADD COLUMN `completion_notes` TEXT DEFAULT NULL,
ADD COLUMN `completion_image` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `completed_at` DATETIME DEFAULT NULL;
