<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

echo "<h1>Urban Company DB Update</h1>";

try {
    $db = new Database;
    
    // 1. Schema Update (Columns)
    $sql_schema = file_get_contents('../database_urban_update.sql');
    if($sql_schema){
        $queries = explode(';', $sql_schema);
        foreach($queries as $query){
            $query = trim($query);
            if(!empty($query)){
                try {
                    $db->query($query);
                    $db->execute();
                    echo "Schema Updated: " . substr($query, 0, 30) . "...<br>";
                } catch (Exception $e) {
                     // Ignore 'Duplicate column name' type errors
                    echo "<span style='color:orange'>Notice: " . $e->getMessage() . "</span><br>";
                }
            }
        }
    }

    // 2. Data Seeding
    $sql_data = file_get_contents('../database_seeder_urban.sql');
    if($sql_data){
        // Handling multiple inserts can be tricky with simple explode if content has semicolons.
        // But our seed data is simple.
        $queries = explode(';', $sql_data);
        foreach($queries as $query){
            $query = trim($query);
            if(!empty($query)){
                 try {
                    $db->query($query);
                    $db->execute();
                    // Echoing every insert is too much, just success
                } catch (Exception $e) {
                    echo "<span style='color:red'>Data Error: " . $e->getMessage() . "</span><br>";
                }
            }
        }
        echo "Data Seeded Successfully.<br>";
    }

    echo "<h2 style='color:green'>Urban Company Transformation Complete!</h2>";
    echo "<a href='" . URLROOT . "'>Go to Home</a>";

} catch(Exception $e){
    echo "<h2 style='color:red'>Fatal Error: " . $e->getMessage() . "</h2>";
}
