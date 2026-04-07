<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

try {
    $db = new Database;
    
    // Read the SQL file
    $sql = file_get_contents('../database_users_update.sql');
    
    if(!$sql){
        die("Could not read database_users_update.sql");
    }

    // This is raw ALTER, so we just run it.
    // It might fail if columns exist.
    $db->query($sql);
    $db->execute();

    echo "<h2 style='color:green'>User Table Updated Successfully!</h2>";
    echo "<a href='" . URLROOT . "/adminUsers'>Go to Admin Users</a>";

} catch(Exception $e){
    echo "<h2 style='color:orange'>Notice: " . $e->getMessage() . "</h2>"; // Orange because "Duplicate column" is fine
    echo "<p>If the error is 'Duplicate column', you are good to go.</p>";
    echo "<a href='" . URLROOT . "/adminUsers'>Go to Admin Users</a>";
}
