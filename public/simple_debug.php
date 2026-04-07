<?php
// Simple Debug Script - No App Requires
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'service_management_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected.<br>";
    
    // 1. Check Service Prices
    echo "<h3>Service Prices</h3>";
    $stmt = $pdo->query("SELECT id, name, price FROM services LIMIT 5");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "ID: {$row['id']}, Name: {$row['name']}, Price: {$row['price']}<br>";
    }

    // 2. Check Last Booking details
    echo "<h3>Last Booking</h3>";
    $stmt = $pdo->query("SELECT id, service_id FROM bookings ORDER BY id DESC LIMIT 1");
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($booking){
        echo "Booking ID: {$booking['id']}, Service ID: {$booking['service_id']}<br>";
        
        // 3. Test the JOIN Query
        echo "<h3>Join Query Test</h3>";
        $sql = "SELECT bookings.*, services.name as service_name, services.price as service_price 
                FROM bookings 
                JOIN services ON bookings.service_id = services.id 
                WHERE bookings.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$booking['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result){
            echo "Service Name: {$result['service_name']}<br>";
            echo "<strong>Service Price (from Join): {$result['service_price']}</strong><br>";
        } else {
            echo "Join returned no result.<br>";
        }
    } else {
        echo "No bookings found.<br>";
    }

} catch(PDOException $e){
    echo "DB Error: " . $e->getMessage();
}
