<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/libraries/Database.php';

$db = new Database();

echo "<h1>Debug Invoice System</h1>";

// 1. Check Invoices Table Columns
echo "<h2>1. Invoices Table Columns</h2>";
$db->query("SHOW COLUMNS FROM invoices");
$columns = $db->resultSet();
foreach($columns as $col){
    echo $col->Field . " (" . $col->Type . ")<br>";
}

// 2. Check a Booking's Price
echo "<h2>2. Booking Price Check</h2>";
// Fetch a recent booking
$db->query("SELECT id, service_id FROM bookings ORDER BY id DESC LIMIT 1");
$lastBooking = $db->single();

if($lastBooking){
    echo "Last Booking ID: " . $lastBooking->id . "<br>";
    
    // Manual Join Query (Mimicking Booking::getBookingById)
    $sql = "SELECT bookings.*, services.name as service_name, services.price as service_price 
            FROM bookings 
            JOIN services ON bookings.service_id = services.id 
            WHERE bookings.id = :id";
            
    $db->query($sql);
    $db->bind(':id', $lastBooking->id);
    $result = $db->single();
    
    echo "Service Name: " . $result->service_name . "<br>";
    echo "<strong>Service Price (from Join): " . $result->service_price . "</strong><br>";
    
    // Check Service Table directly
    $db->query("SELECT * FROM services WHERE id = :id");
    $db->bind(':id', $lastBooking->service_id);
    $service = $db->single();
    echo "Service Price (Direct): " . $service->price . "<br>";
    
} else {
    echo "No bookings found.<br>";
}
