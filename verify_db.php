<?php
require_once 'app/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->query("SHOW TABLES LIKE 'categories'");
    if ($stmt->rowCount() > 0) {
        echo "Table 'categories' exists.\n";
    } else {
        echo "Table 'categories' NOT found.\n";
    }

    $stmt = $pdo->query("SHOW TABLES LIKE 'services'");
    if ($stmt->rowCount() > 0) {
        echo "Table 'services' exists.\n";
    } else {
        echo "Table 'services' NOT found.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
