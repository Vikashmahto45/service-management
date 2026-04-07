-- Add image columns if they don't exist
-- We use a stored procedure or just simple ALTERs that might fail if exists, but for this setup:

-- Categories Table
ALTER TABLE `categories` ADD COLUMN `image` VARCHAR(255) DEFAULT NULL AFTER `description`;

-- Services Table
ALTER TABLE `services` ADD COLUMN `image` VARCHAR(255) DEFAULT NULL AFTER `description`;
ALTER TABLE `services` ADD COLUMN `rating` DECIMAL(2,1) DEFAULT 4.5 AFTER `status`;
