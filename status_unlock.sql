-- ======================================================
-- STATUS UNLOCK SCRIPT
-- RUN THIS IN YOUR HOSTINGER PHPMYADMIN
-- ======================================================

-- 1. Unlock the main bookings table
-- This converts it from a restrictive List (ENUM) to flexible text (VARCHAR)
ALTER TABLE `bookings` MODIFY COLUMN `status` VARCHAR(50) DEFAULT 'pending';

-- 2. Unlock the history table
ALTER TABLE `ticket_status_history` MODIFY COLUMN `status` VARCHAR(50) NOT NULL;

-- 3. Optimization: Add backticks to the existing procedure columns just in case
-- This and the VARCHAR change together will ensure the database never rejects 'assigned' again.
