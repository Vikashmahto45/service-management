<?php
/**
 * Migration Runner: Add Completion Data
 */
require_once '../app/config/config.php';
require_once '../app/libraries/Database.php';

$db = new Database();

$sql = "
-- Update Bookings Table
ALTER TABLE `bookings` 
ADD COLUMN IF NOT EXISTS `completion_notes` TEXT DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `completion_image` VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `completed_at` DATETIME DEFAULT NULL;

-- Update Complaints Table
ALTER TABLE `complaints` 
ADD COLUMN IF NOT EXISTS `completion_notes` TEXT DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `completion_image` VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `completed_at` DATETIME DEFAULT NULL;
";

try {
    // We run them one by one since some DB drivers don't like multi-query
    $db->query("ALTER TABLE `bookings` ADD COLUMN IF NOT EXISTS `completion_notes` TEXT DEFAULT NULL");
    $db->execute();
    $db->query("ALTER TABLE `bookings` ADD COLUMN IF NOT EXISTS `completion_image` VARCHAR(255) DEFAULT NULL");
    $db->execute();
    $db->query("ALTER TABLE `bookings` ADD COLUMN IF NOT EXISTS `completed_at` DATETIME DEFAULT NULL");
    $db->execute();

    $db->query("ALTER TABLE `complaints` ADD COLUMN IF NOT EXISTS `completion_notes` TEXT DEFAULT NULL");
    $db->execute();
    $db->query("ALTER TABLE `complaints` ADD COLUMN IF NOT EXISTS `completion_image` VARCHAR(255) DEFAULT NULL");
    $db->execute();
    $db->query("ALTER TABLE `complaints` ADD COLUMN IF NOT EXISTS `completed_at` DATETIME DEFAULT NULL");
    $db->execute();

    echo "<h3>Migration Successful!</h3>";
    echo "<p>Database tables updated with completion columns.</p>";
    echo "<a href='index.php'>Go to Home</a>";
} catch (Exception $e) {
    echo "<h3>Migration Failed</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
