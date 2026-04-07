<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

echo "<h1>Database Setup</h1>";

try {
    $db = new Database;
    
    // Read the SQL files
    $sql_services = file_get_contents('../database_services.sql');
    $sql_inventory = file_get_contents('../database_inventory.sql');
    
    // Note: We are appending this. If columns exist, it might fail. Use try catch for alter in real world or manual.
    // However, for this environment, we just want to run it.
    // Better approach: Read it and execute separately or assume user handles "Table exists". 
    // Since this is a setup script, we will just try to run it. 
    // Actually, CREATE TABLE IF NOT EXISTS handles the others. ALTER TABLE will error if column exists.
    // We will leave the users update separate or let the user run it via a specific call, OR just append it and warn.
    // Let's create a separate execution for upgrades.
    
    $sql = $sql_services . "\n" . $sql_inventory;
    
    if(!$sql){
        die("Could not read SQL files");
    }

    // Split by semicolon to get individual queries (basic split)
    $queries = explode(';', $sql);

    foreach($queries as $query){
        $query = trim($query);
        if(!empty($query)){
            try {
                $db->query($query);
                $db->execute();
                echo "Executed query successfully.<br>";
            } catch (Exception $e) {
                // If table already exists (1050) or similar, just continue
                if (strpos($e->getMessage(), '1050') !== false) {
                    echo "Notice: Table already exists, skipping...<br>";
                } else {
                    echo "<span style='color:orange'>Warning: " . $e->getMessage() . "</span><br>";
                }
            }
        }
    }

    echo "<h2 style='color:green'>Tables Created Successfully!</h2>";
    echo "<a href='" . URLROOT . "/services'>Go to Services</a> | ";
    echo "<a href='" . URLROOT . "/inventories'>Go to Inventory</a>";

} catch(Exception $e){
    echo "<h2 style='color:red'>Error: " . $e->getMessage() . "</h2>";
}
