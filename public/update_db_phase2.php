<?php
$root = dirname(__DIR__);
require_once $root . '/app/config/config.php';
require_once $root . '/app/core/Database.php';

$db = new Database();
echo "Start Phase 2 Database Update...\n";

// Create service_inventory table
$sql = "CREATE TABLE IF NOT EXISTS service_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    inventory_id INT NOT NULL,
    quantity_needed INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (inventory_id) REFERENCES products(id) ON DELETE CASCADE
)";

try {
    $db->query($sql);
    $db->execute();
    echo "Success: Created service_inventory table.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Phase 2 Database Update Complete.\n";
