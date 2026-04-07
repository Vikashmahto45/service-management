<?php
require_once 'app/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Connected to database: " . DB_NAME . "\n";

    // Disable foreign key checks to allow dropping tables
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    echo "Dropping services table if exists...\n";
    $pdo->exec("DROP TABLE IF EXISTS services");

    echo "Dropping categories table if exists...\n";
    $pdo->exec("DROP TABLE IF EXISTS categories");

    echo "Creating categories table...\n";
    try {
        $pdo->exec("CREATE TABLE `categories` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `description` text,
            `image` varchar(255) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), '1813') !== false) {
            echo "Tablespace exists for categories. Attempting to discard and recreate...\n";
            $pdo->exec("DROP TABLE IF EXISTS categories");
            // In some cases, we might need to manually delete the .ibd file if DROP TABLE fails to clean it up
            // But usually DROP TABLE followed by a clean CREATE is enough if engine is in sync.
            // If it still fails, we'll try a different approach.
             $pdo->exec("CREATE TABLE `categories` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text,
                `image` varchar(255) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        } else {
            throw $e;
        }
    }

    echo "Creating services table...\n";
    try {
        $pdo->exec("CREATE TABLE `services` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `category_id` int(11) NOT NULL,
            `name` varchar(255) NOT NULL,
            `description` text,
            `price` decimal(10,2) NOT NULL,
            `duration` int(11) NOT NULL COMMENT 'Duration in minutes',
            `status` enum('active','inactive') DEFAULT 'active',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), '1813') !== false) {
            echo "Tablespace exists for services. Attempting to discard and recreate...\n";
            $pdo->exec("DROP TABLE IF EXISTS services");
            $pdo->exec("CREATE TABLE `services` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `category_id` int(11) NOT NULL,
                `name` varchar(255) NOT NULL,
                `description` text,
                `price` decimal(10,2) NOT NULL,
                `duration` int(11) NOT NULL COMMENT 'Duration in minutes',
                `status` enum('active','inactive') DEFAULT 'active',
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`),
                FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        } else {
            throw $e;
        }
    }

    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "Successfully recreated categories and services tables!\n";

} catch (PDOException $e) {
    echo "FULL ERROR: " . $e->getMessage() . "\n";
    echo "SQLSTATE: " . $e->getCode() . "\n";
    if (isset($e->errorInfo)) {
        echo "Error Info: " . implode(" | ", $e->errorInfo) . "\n";
    }
}
