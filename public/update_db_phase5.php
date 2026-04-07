<?php
$root = dirname(__DIR__);
require_once $root . '/app/config/config.php';
require_once $root . '/app/core/Database.php';

$db = new Database();
echo "Start Phase 5 Database Update...\n";

// 1. Create NOTIFICATIONS table
$sql = "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(20) DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $db->query($sql);
    $db->execute();
    echo "Success: Created notifications table.\n";
} catch (PDOException $e) {
    echo "Error creating notifications: " . $e->getMessage() . "\n";
}

echo "Phase 5 Database Update Complete.\n";
