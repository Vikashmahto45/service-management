<?php
/**
 * Run Migration: Items & Parties System
 * Visit: http://localhost:8080/Service Management System/public/run_migration_items_parties.php
 */

// Load config
require_once dirname(__DIR__) . '/app/config/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => true
        ]
    );

    echo "<h2>Running Migration...</h2>";

    // Execute each statement separately to avoid splitting issues
    $statements = [];

    // 1. item_units table
    $statements[] = "CREATE TABLE IF NOT EXISTS `item_units` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(50) NOT NULL,
      `short_name` varchar(10) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // 2. Seed item_units
    $statements[] = "INSERT IGNORE INTO `item_units` (`name`, `short_name`) VALUES
    ('Piece', 'PCS'), ('Kilogram', 'KG'), ('Gram', 'GM'), ('Litre', 'LTR'), ('Meter', 'MTR'),
    ('Box', 'BOX'), ('Pair', 'PR'), ('Set', 'SET'), ('Hour', 'HR'), ('Service', 'SRV'),
    ('Unit', 'UNT'), ('Dozen', 'DZN'), ('Feet', 'FT'), ('Square Feet', 'SQF'), ('Square Meter', 'SQM')";

    // 3. gst_rates table
    $statements[] = "CREATE TABLE IF NOT EXISTS `gst_rates` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(30) NOT NULL,
      `rate` decimal(5,2) NOT NULL DEFAULT 0.00,
      `type` enum('GST','IGST') NOT NULL DEFAULT 'GST',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // 4. Seed gst_rates
    $statements[] = "INSERT IGNORE INTO `gst_rates` (`name`, `rate`, `type`) VALUES
    ('None', 0.00, 'GST'), ('IGST@0%', 0.00, 'IGST'), ('GST@0%', 0.00, 'GST'),
    ('IGST@0.25%', 0.25, 'IGST'), ('GST@0.25%', 0.25, 'GST'),
    ('IGST@3%', 3.00, 'IGST'), ('GST@3%', 3.00, 'GST'),
    ('IGST@5%', 5.00, 'IGST'), ('GST@5%', 5.00, 'GST'),
    ('IGST@12%', 12.00, 'IGST'), ('GST@12%', 12.00, 'GST'),
    ('IGST@18%', 18.00, 'IGST'), ('GST@18%', 18.00, 'GST'),
    ('IGST@28%', 28.00, 'IGST'), ('GST@28%', 28.00, 'GST')";

    // 5. items table
    $statements[] = "CREATE TABLE IF NOT EXISTS `items` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `type` enum('product','service') NOT NULL DEFAULT 'product',
      `name` varchar(255) NOT NULL,
      `hsn_code` varchar(20) DEFAULT NULL,
      `unit_id` int(11) DEFAULT NULL,
      `item_code` varchar(50) DEFAULT NULL,
      `image` varchar(500) DEFAULT NULL,
      `batch_tracking` tinyint(1) NOT NULL DEFAULT 0,
      `serial_tracking` tinyint(1) NOT NULL DEFAULT 0,
      `sale_price` decimal(12,2) NOT NULL DEFAULT 0.00,
      `sale_price_tax_type` enum('without_tax','with_tax') NOT NULL DEFAULT 'without_tax',
      `discount_on_sale` decimal(12,2) NOT NULL DEFAULT 0.00,
      `discount_type` enum('percentage','amount') NOT NULL DEFAULT 'percentage',
      `wholesale_price` decimal(12,2) DEFAULT NULL,
      `purchase_price` decimal(12,2) DEFAULT NULL,
      `purchase_price_tax_type` enum('without_tax','with_tax') NOT NULL DEFAULT 'without_tax',
      `gst_rate_id` int(11) DEFAULT 1,
      `opening_qty` int(11) NOT NULL DEFAULT 0,
      `current_stock` int(11) NOT NULL DEFAULT 0,
      `at_price` decimal(12,2) DEFAULT NULL,
      `as_of_date` date DEFAULT NULL,
      `min_stock` int(11) NOT NULL DEFAULT 0,
      `location` varchar(255) DEFAULT NULL,
      `status` enum('active','inactive') NOT NULL DEFAULT 'active',
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `fk_item_unit` (`unit_id`),
      KEY `fk_item_gst` (`gst_rate_id`),
      CONSTRAINT `fk_item_unit` FOREIGN KEY (`unit_id`) REFERENCES `item_units` (`id`) ON DELETE SET NULL,
      CONSTRAINT `fk_item_gst` FOREIGN KEY (`gst_rate_id`) REFERENCES `gst_rates` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // 6. party_groups table
    $statements[] = "CREATE TABLE IF NOT EXISTS `party_groups` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(100) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // 7. Seed party_groups
    $statements[] = "INSERT IGNORE INTO `party_groups` (`name`) VALUES
    ('Sundry Debtors'), ('Sundry Creditors'), ('General'),
    ('Retail Customers'), ('Wholesale Customers'), ('Vendors'), ('Suppliers')";

    // 8. parties table
    $statements[] = "CREATE TABLE IF NOT EXISTS `parties` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `gstin` varchar(15) DEFAULT NULL,
      `phone` varchar(20) DEFAULT NULL,
      `email` varchar(255) DEFAULT NULL,
      `party_group_id` int(11) DEFAULT NULL,
      `gst_type` enum('unregistered','registered_regular','registered_composition','special_economic_zone','deemed_export') NOT NULL DEFAULT 'unregistered',
      `state` varchar(100) DEFAULT NULL,
      `opening_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
      `opening_balance_type` enum('to_receive','to_pay') NOT NULL DEFAULT 'to_receive',
      `credit_limit` decimal(12,2) DEFAULT NULL,
      `additional_fields` text DEFAULT NULL,
      `status` enum('active','inactive') NOT NULL DEFAULT 'active',
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `fk_party_group` (`party_group_id`),
      CONSTRAINT `fk_party_group` FOREIGN KEY (`party_group_id`) REFERENCES `party_groups` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // 9. party_addresses table
    $statements[] = "CREATE TABLE IF NOT EXISTS `party_addresses` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `party_id` int(11) NOT NULL,
      `type` enum('billing','shipping') NOT NULL DEFAULT 'billing',
      `address_line1` varchar(255) DEFAULT NULL,
      `address_line2` varchar(255) DEFAULT NULL,
      `city` varchar(100) DEFAULT NULL,
      `state` varchar(100) DEFAULT NULL,
      `pincode` varchar(10) DEFAULT NULL,
      `country` varchar(100) DEFAULT 'India',
      `is_default` tinyint(1) NOT NULL DEFAULT 0,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `fk_address_party` (`party_id`),
      CONSTRAINT `fk_address_party` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $success = 0;
    $errors = [];

    foreach($statements as $i => $sql) {
        try {
            $pdo->exec($sql);
            $success++;
            echo "<p>✅ Statement " . ($i + 1) . " executed.</p>";
        } catch(PDOException $e) {
            $errors[] = "Statement " . ($i + 1) . ": " . $e->getMessage();
            echo "<p>⚠️ Statement " . ($i + 1) . ": " . $e->getMessage() . "</p>";
        }
    }

    echo "<hr>";
    echo "<h3>Migration Result: $success/" . count($statements) . " statements executed</h3>";

    // Verify tables
    $tables = ['item_units', 'gst_rates', 'items', 'party_groups', 'parties', 'party_addresses'];
    echo "<h4>Table Verification:</h4><ul>";
    foreach($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'")->rowCount();
        $status = $result > 0 ? '✅' : '❌';
        echo "<li>$status $table</li>";
    }
    echo "</ul>";

    // Verify data counts
    echo "<h4>Data Counts:</h4><ul>";
    foreach(['item_units', 'gst_rates', 'party_groups'] as $t) {
        try {
            $count = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
            echo "<li>$t: $count rows</li>";
        } catch(Exception $e) {
            echo "<li>$t: error</li>";
        }
    }
    echo "</ul>";

    echo "<p><a href='" . URLROOT . "/items'>Go to Items Management →</a></p>";
    echo "<p><a href='" . URLROOT . "/parties'>Go to Parties Management →</a></p>";

} catch(PDOException $e) {
    die("<h2>Connection Error</h2><p>" . $e->getMessage() . "</p>");
}
