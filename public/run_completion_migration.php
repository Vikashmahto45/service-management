<?php
/**
 * Migration Runner: Add Completion Data
 */
require_once '../app/config/config.php';

// Standard Autoloader
spl_autoload_register(function($className){
    if (file_exists('../app/core/' . $className . '.php')) {
        require_once '../app/core/' . $className . '.php';
    } 
});

$db = new Database();

function addColumnIfMissing($db, $table, $column, $type) {
    try {
        $db->query("ALTER TABLE `$table` ADD COLUMN `$column` $type");
        $db->execute();
        return true;
    } catch (Exception $e) {
        // Ignore if column already exists
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            return true;
        }
        throw $e;
    }
}

try {
    echo "<h3>Starting Migration...</h3>";
    
    // Update Bookings
    addColumnIfMissing($db, 'bookings', 'completion_notes', 'TEXT DEFAULT NULL');
    addColumnIfMissing($db, 'bookings', 'completion_image', 'VARCHAR(255) DEFAULT NULL');
    addColumnIfMissing($db, 'bookings', 'completed_at', 'DATETIME DEFAULT NULL');

    // Update Complaints
    addColumnIfMissing($db, 'complaints', 'completion_notes', 'TEXT DEFAULT NULL');
    addColumnIfMissing($db, 'complaints', 'completion_image', 'VARCHAR(255) DEFAULT NULL');
    addColumnIfMissing($db, 'complaints', 'completed_at', 'DATETIME DEFAULT NULL');

    echo "<h3 style='color:green;'>Migration Successful!</h3>";
    echo "<p>Database tables updated with completion columns.</p>";
    echo "<a href='index.php'>Go to Home Dashboard</a>";
} catch (Exception $e) {
    echo "<h3 style='color:red;'>Migration Failed</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
