<?php
// Connect to DB
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'service_management_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to Database...\n";

    // Add transaction_id
    try {
        $pdo->exec("ALTER TABLE invoices ADD COLUMN transaction_id VARCHAR(255) NULL AFTER status");
        echo "Added transaction_id column.\n";
    } catch (PDOException $e) {
        echo "transaction_id column might already exist or error: " . $e->getMessage() . "\n";
    }

    // Add payment_date
    try {
        $pdo->exec("ALTER TABLE invoices ADD COLUMN payment_date DATETIME NULL AFTER transaction_id");
        echo "Added payment_date column.\n";
    } catch (PDOException $e) {
        echo "payment_date column might already exist or error: " . $e->getMessage() . "\n";
    }

    echo "Database Updated Successfully.\n";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
