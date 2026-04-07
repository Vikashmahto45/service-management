<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

echo "<h1>Team DB Update</h1>";

try {
    $db = new Database;
    $sql = file_get_contents('../database_team.sql');
    
    // Split by semicolon to handle multiple statements if any
    $queries = explode(';', $sql);
    
    foreach($queries as $query){
        if(trim($query) != ''){
            $db->query($query);
            $db->execute();
            echo "Executed: " . substr($query, 0, 50) . "...<br>";
        }
    }
    
    echo "<h2 style='color:green'>Team Table Created & Seeded!</h2>";
    echo "<a href='" . URLROOT . "'>Go Home</a>";

} catch(Exception $e){
    echo "<h2 style='color:red'>Error: " . $e->getMessage() . "</h2>";
}
