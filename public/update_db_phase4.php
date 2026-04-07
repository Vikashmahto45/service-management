<?php
$root = dirname(__DIR__);
require_once $root . '/app/config/config.php';
require_once $root . '/app/core/Database.php';

$db = new Database();
echo "Start Phase 4 Database Update...\n";

// 1. Create INVOICES table
$sql = "CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    customer_id INT NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    amount DECIMAL(10,2) NOT NULL,
    tax_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    discount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('unpaid', 'paid', 'cancelled') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $db->query($sql);
    $db->execute();
    echo "Success: Created invoices table.\n";
} catch (PDOException $e) {
    echo "Error creating invoices: " . $e->getMessage() . "\n";
}

echo "Phase 4 Database Update Complete.\n";
