<?php
// Schema Check Script
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'service_management_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>Service Inventory Columns</h3>";
    $stmt = $pdo->query("SHOW COLUMNS FROM service_inventory");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo $row['Field'] . "<br>";
    }

    echo "<h3>Products Columns</h3>";
    $stmt = $pdo->query("SHOW COLUMNS FROM products");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo $row['Field'] . "<br>";
    }

} catch(PDOException $e){
    echo "DB Error: " . $e->getMessage();
}
