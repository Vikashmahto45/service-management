<?php
// Create Complaints Table Script
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/libraries/Database.php';

$db = new Database();

$sql = "CREATE TABLE IF NOT EXISTS complaints (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'open',
    assigned_to INT(11) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $db->query($sql);
    $db->execute();
    echo "<h1>Complaints Table Created Successfully!</h1>";
} catch(PDOException $e){
    echo "<h2 style='color:red'>Error: " . $e->getMessage() . "</h2>";
}
