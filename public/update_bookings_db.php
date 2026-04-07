<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

try {
    $db = new Database;
    
    // Read the SQL file
    $sql = file_get_contents('../database_bookings.sql');
    
    if(!$sql){
        die("Could not read database_bookings.sql");
    }

    $db->query($sql);
    $db->execute();

    echo "<h2 style='color:green'>Bookings Table Created Successfully!</h2>";

} catch(Exception $e){
    echo "<h2 style='color:orange'>Notice: " . $e->getMessage() . "</h2>";
}
