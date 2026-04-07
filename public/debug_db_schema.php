<?php
require_once 'c:/xampp/htdocs/Service Management System/app/config/config.php';
require_once 'c:/xampp/htdocs/Service Management System/app/core/Database.php';

$db = new Database;

echo "Connected to Database: " . DB_NAME . "\n";

// 1. Show existing columns
echo "Current Columns in 'categories':\n";
$db->query("SHOW COLUMNS FROM categories");
$columns = $db->resultSet();
$hasIcon = false;
foreach ($columns as $col) {
    echo "- " . $col->Field . " (" . $col->Type . ")\n";
    if ($col->Field === 'icon') {
        $hasIcon = true;
    }
}

// 2. Add column if missing
if (!$hasIcon) {
    echo "\n'icon' column is MISSING. Attempting to add it...\n";
    $sql = "ALTER TABLE categories ADD COLUMN icon VARCHAR(50) DEFAULT 'fa-tools'";
    try {
        $db->query($sql);
        if($db->execute()){
            echo "SUCCESS: 'icon' column added.\n";
        } else {
            echo "FAILED: Could not execute ALTER TABLE.\n";
        }
    } catch (PDOException $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
} else {
    echo "\n'icon' column ALREADY EXISTS.\n";
}

// 3. Verify again
echo "\nVerifying columns again:\n";
$db->query("SHOW COLUMNS FROM categories");
$columns = $db->resultSet();
foreach ($columns as $col) {
    echo "- " . $col->Field . "\n";
}
