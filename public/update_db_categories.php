<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database;

// Add 'icon' column to categories table
$sql = "ALTER TABLE categories ADD COLUMN icon VARCHAR(50) DEFAULT 'fa-tools'";

try {
    $db->query($sql);
    if($db->execute()){
        echo "Successfully added 'icon' column to categories table.";
    } else {
        echo "Failed to add column (it might already exist).";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
