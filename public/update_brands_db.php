<?php
// Script to create the 'brands' table in the Service Management System database

require_once '../app/config/config.php';

// Database connection variables (should match your config.php)
$host = DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$dbname = DB_NAME;

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully to database: $dbname<br><br>";

// 1. Create Brands table
$sql = "CREATE TABLE IF NOT EXISTS brands (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    logo VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (name)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'brands' created successfully.<br>";
} else {
    echo "Error creating table 'brands': " . $conn->error . "<br>";
}

$conn->close();

echo "<br><strong>Database update completed successfully.</strong>";
echo "<br><a href='index.php'>Return to Home</a>";
?>
