<?php
require_once 'app/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $tables = [
        'roles', 'users', 'categories', 'services', 'item_units', 'gst_rates', 'items', 
        'party_groups', 'parties', 'party_addresses', 'bookings', 'calls', 'complaints', 
        'tasks', 'products', 'password_resets', 'team_members', 'attendance', 'leaves', 
        'attendance_settings', 'expenses', 'invoices', 'service_inventory', 'notifications'
    ];

    echo "Checking 24 tables...\n";
    $missing = [];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ $table exists.\n";
        } else {
            echo "❌ $table MISSING.\n";
            $missing[] = $table;
        }
    }

    if (empty($missing)) {
        echo "\nALL TABLES VERIFIED SUCCESSFULLY.\n";
        
        // Final check: User count
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();
        echo "Default users seeded: $count\n";
    } else {
        echo "\nWARNING: The following tables are missing: " . implode(", ", $missing) . "\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
