<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

try {
    $db = new Database;
    
    // Read the SQL file
    $sql = file_get_contents('../database_complaints_tasks.sql');
    
    if(!$sql){
        die("Could not read database_complaints_tasks.sql");
    }

    // Split by semicolon
    $queries = explode(';', $sql);

    foreach($queries as $query){
        $query = trim($query);
        if(!empty($query)){
            try {
                $db->query($query);
                $db->execute();
                echo "Executed query successfully.<br>";
            } catch (Exception $e) {
                 if (strpos($e->getMessage(), '1050') !== false) {
                    echo "Notice: Table already exists, skipping...<br>";
                } else {
                    echo "<span style='color:orange'>Warning: " . $e->getMessage() . "</span><br>";
                }
            }
        }
    }

    echo "<h2 style='color:green'>Complaints & Tasks Tables Created Successfully!</h2>";

} catch(Exception $e){
    echo "<h2 style='color:red'>Error: " . $e->getMessage() . "</h2>";
}
