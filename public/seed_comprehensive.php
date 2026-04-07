<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

try {
    $db = new Database;
    
    // Read the SQL file
    $sql = file_get_contents('../database_seeder_comprehensive.sql');
    
    if(!$sql){
        die("Could not read database_seeder_comprehensive.sql");
    }

    // Rough split by semicolon to handle statements
    $queries = explode(';', $sql);

    foreach($queries as $query){
        $query = trim($query);
        if(!empty($query)){
            try {
                $db->query($query);
                $db->execute();
                echo "Inserted data successfully: " . substr($query, 0, 50) . "...<br>";
            } catch (Exception $e) {
                echo "<span style='color:orange'>Notice: " . $e->getMessage() . "</span><br>";
            }
        }
    }

    echo "<h2 style='color:green'>Comprehensive Data Seeded!</h2>";
    echo "<a href='" . URLROOT . "/services'>View Services</a>";

} catch(Exception $e){
    echo "<h2 style='color:red'>Error: " . $e->getMessage() . "</h2>";
}
