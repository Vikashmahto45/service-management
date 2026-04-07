<?php
require_once 'app/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Updating users table schema...\n";

    // Aadhar File
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN aadhar_file VARCHAR(255) NULL AFTER profile_image");
        echo "Added aadhar_file column.\n";
    } catch (PDOException $e) { echo "aadhar_file might exist: " . $e->getMessage() . "\n"; }

    // PAN File
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN pan_file VARCHAR(255) NULL AFTER aadhar_file");
        echo "Added pan_file column.\n";
    } catch (PDOException $e) { echo "pan_file might exist: " . $e->getMessage() . "\n"; }

    // Employee ID
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN employee_id VARCHAR(50) NULL AFTER pan_file");
        // Add unique index on employee_id
        $pdo->exec("ALTER TABLE users ADD UNIQUE INDEX idx_employee_id (employee_id)");
        echo "Added employee_id column and unique index.\n";
    } catch (PDOException $e) { echo "employee_id might exist: " . $e->getMessage() . "\n"; }

    // GSTIN
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN gstin VARCHAR(50) NULL AFTER employee_id");
        echo "Added gstin column.\n";
    } catch (PDOException $e) { echo "gstin might exist: " . $e->getMessage() . "\n"; }

    // Office Address
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN office_address TEXT NULL AFTER gstin");
        echo "Added office_address column.\n";
    } catch (PDOException $e) { echo "office_address might exist: " . $e->getMessage() . "\n"; }

    echo "Database schema update completed successfully.\n";

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
