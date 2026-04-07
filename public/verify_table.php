<?php
// Verify Table Script
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/libraries/Database.php';

$db = new Database();

echo "<h3>Checking for 'complaints' table...</h3>";

try {
    $db->query("SHOW TABLES LIKE 'complaints'");
    $result = $db->single();
    
    if($result){
        echo "<span style='color:green'>Table 'complaints' EXISTS.</span><br>";
        
        $db->query("SHOW COLUMNS FROM complaints");
        $columns = $db->resultSet();
        foreach($columns as $col){
            echo " - " . $col->Field . " (" . $col->Type . ")<br>";
        }
    } else {
        echo "<span style='color:red'>Table 'complaints' DOES NOT EXIST.</span>";
    }

} catch(Exception $e){
    echo "Error: " . $e->getMessage();
}
