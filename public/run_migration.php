<?php
require_once '../app/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Running Map Fields Migration...\n";
    
    $sql = "ALTER TABLE `bookings` 
            ADD COLUMN IF NOT EXISTS `latitude` DECIMAL(10, 8) DEFAULT NULL AFTER `notes`,
            ADD COLUMN IF NOT EXISTS `longitude` DECIMAL(11, 8) DEFAULT NULL AFTER `latitude`,
            ADD COLUMN IF NOT EXISTS `formatted_address` TEXT DEFAULT NULL AFTER `longitude` ";
            
    $pdo->exec($sql);
    
    echo "Migration Completed Successfully!\n";

} catch (PDOException $e) {
    echo "Migration Failed: " . $e->getMessage() . "\n";
}
