<?php
// Script to seed the 'brands' table with sample data

require_once '../app/config/config.php';

// Database connection
$host = DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$dbname = DB_NAME;

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Seeding Brands Table...<br><br>";

// Sample brands data
$brands = [
    ['name' => 'Bosch', 'description' => 'Premium power tools and automotive parts.', 'status' => 'active'],
    ['name' => '3M', 'description' => 'Industrial, safety, and everyday consumer products.', 'status' => 'active'],
    ['name' => 'Samsung', 'description' => 'Consumer electronics, appliances, and IT hardware.', 'status' => 'active'],
    ['name' => 'Karcher', 'description' => 'High-pressure cleaners, floor care equipment, and parts.', 'status' => 'active'],
    ['name' => 'Makita', 'description' => 'Professional power tools and accessories.', 'status' => 'active'],
    ['name' => 'Dell', 'description' => 'Computer hardware and enterprise technology solutions.', 'status' => 'active'],
    ['name' => 'Stanley', 'description' => 'Hand tools, power tools, and related accessories.', 'status' => 'active'],
    ['name' => 'LG', 'description' => 'Life\'s Good. Home appliances and electronics.', 'status' => 'active']
];

$successCount = 0;
$errorCount = 0;

$stmt = $conn->prepare("INSERT IGNORE INTO brands (name, description, status) VALUES (?, ?, ?)");
if ($stmt) {
    foreach ($brands as $brand) {
        $stmt->bind_param("sss", $brand['name'], $brand['description'], $brand['status']);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Added brand: " . $brand['name'] . "<br>";
                $successCount++;
            } else {
                echo "Skipped (already exists): " . $brand['name'] . "<br>";
            }
        } else {
            echo "Error adding " . $brand['name'] . ": " . $stmt->error . "<br>";
            $errorCount++;
        }
    }
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error . "<br>";
}

$conn->close();

echo "<br><strong>Seeding completed.</strong><br>";
echo "$successCount brands added. $errorCount errors.<br>";
echo "<a href='index.php'>Return to Home</a>";
?>
