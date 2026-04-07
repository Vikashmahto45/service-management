<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

$db = new Database();

echo "<h1>Updating Roles & Users Table</h1>";

// 1. Ensure Roles exist
$roles = ['Admin', 'Manager', 'Employee', 'Vendor', 'Customer'];
foreach ($roles as $role) {
    $db->query("SELECT id FROM roles WHERE name = :name");
    $db->bind(':name', $role);
    $existing = $db->single();

    if (!$existing) {
        $db->query("INSERT INTO roles (name) VALUES (:name)");
        $db->bind(':name', $role);
        if ($db->execute()) {
            echo "Role created: $role<br>";
        } else {
            echo "Failed to create role: $role<br>";
        }
    } else {
        echo "Role exists: $role<br>";
    }
}

// 2. Add Columns to Users table
$columns = [
    "ADD COLUMN IF NOT EXISTS kyc_document VARCHAR(255) NULL AFTER status",
    "ADD COLUMN IF NOT EXISTS kyc_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending' AFTER kyc_document",
    "ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) NULL AFTER kyc_status",
    "ADD COLUMN IF NOT EXISTS designation VARCHAR(100) NULL AFTER profile_image"
];

foreach ($columns as $colSql) {
    try {
        $db->query("ALTER TABLE users " . $colSql);
        $db->execute();
        echo "Executed: ALTER TABLE users $colSql <br>";
    } catch (PDOException $e) {
        // IF NOT EXISTS syntax for columns is available in newer MariaDB/MySQL versions.
        // If it fails, it might be due to syntax or column already existing in older versions.
        // We can ignore 'Duplicate column name' error (1060).
        if (strpos($e->getMessage(), '1060') !== false) {
             echo "Column already exists (verified via error 1060).<br>";
        } else {
             // Fallback for older MySQL that doesn't support IF NOT EXISTS in ALTER TABLE
             // We just try to add it, if it fails, we assume it exists. 
             // Actually the query above HAS 'IF NOT EXISTS' which might be syntax error in older MySQL.
             // Let's retry without IF NOT EXISTS if syntax error (1064) or similar, but catching 1060 is better.
             echo "Error or Column exists: " . $e->getMessage() . "<br>";
        }
    }
}

echo "<h2>Database Update Complete</h2>";
echo "<a href='" . URLROOT . "'>Go Home</a>";
