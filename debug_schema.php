<?php
// Hardcode local creds for CLI debugging
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'service_management_db');

require 'app/core/Database.php';

$db = new Database();

$tables = ['bookings', 'services', 'parties', 'users', 'party_addresses', 'appliance_types', 'customer_products', 'ticket_status_history'];

foreach ($tables as $table) {
    echo "--- TABLE: $table ---\n";
    try {
        $db->query("DESCRIBE `$table` ");
        $results = $db->resultSet();
        foreach ($results as $column) {
            echo "{$column->Field} ({$column->Type})\n";
        }
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
