<?php
// Simple Verify Table Script - No App Requires
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'service_management_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>Checking for 'complaints' table...</h3>";
    $stmt = $pdo->query("SHOW TABLES LIKE 'complaints'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($result){
        echo "<span style='color:green'>Table 'complaints' EXISTS.</span><br>";
        
        $stmt = $pdo->query("SHOW COLUMNS FROM complaints");
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
             echo " - " . $row['Field'] . " (" . $row['Type'] . ")<br>";
        }
    } else {
         echo "<span style='color:red'>Table 'complaints' DOES NOT EXIST.</span>";
    }

} catch(PDOException $e){
    echo "DB Error: " . $e->getMessage();
}
