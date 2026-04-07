<?php
require_once 'app/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Connected to database: " . DB_NAME . "\n";

    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    $tables = [
        'roles', 'users', 'categories', 'services', 'item_units', 'gst_rates', 'items', 
        'party_groups', 'parties', 'party_addresses', 'bookings', 'calls', 'complaints', 
        'tasks', 'products', 'password_resets', 'team_members', 'attendance', 'leaves', 
        'attendance_settings', 'expenses', 'invoices', 'service_inventory', 'notifications'
    ];

    foreach ($tables as $table) {
        echo "Dropping table $table if exists...\n";
        $pdo->exec("DROP TABLE IF EXISTS `$table` ");
    }

    echo "Recreating tables...\n";

    // 1. roles
    $pdo->exec("CREATE TABLE `roles` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `permissions` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 2. users
    $pdo->exec("CREATE TABLE `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `phone` varchar(20) DEFAULT NULL,
        `address` text DEFAULT NULL,
        `password` varchar(255) NOT NULL,
        `role_id` int(11) NOT NULL,
        `status` enum('active','inactive','banned') DEFAULT 'active',
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `role_id` (`role_id`),
        CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 3. categories
    $pdo->exec("CREATE TABLE `categories` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text,
        `image` varchar(255) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 4. services
    $pdo->exec("CREATE TABLE `services` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `category_id` int(11) NOT NULL,
        `name` varchar(255) NOT NULL,
        `description` text,
        `price` decimal(10,2) NOT NULL,
        `image` varchar(255) DEFAULT NULL,
        `rating` decimal(2,1) DEFAULT 4.5,
        `duration` int(11) NOT NULL DEFAULT 60 COMMENT 'Duration in minutes',
        `status` enum('active','inactive') DEFAULT 'active',
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 5. item_units
    $pdo->exec("CREATE TABLE `item_units` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `short_name` varchar(10) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 6. gst_rates
    $pdo->exec("CREATE TABLE `gst_rates` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(30) NOT NULL,
        `rate` decimal(5,2) NOT NULL DEFAULT 0.00,
        `type` enum('GST','IGST') NOT NULL DEFAULT 'GST',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 7. items
    $pdo->exec("CREATE TABLE `items` (
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
        CONSTRAINT `fk_item_unit` FOREIGN KEY (`unit_id`) REFERENCES `item_units` (`id`) ON DELETE SET NULL,
        CONSTRAINT `fk_item_gst` FOREIGN KEY (`gst_rate_id`) REFERENCES `gst_rates` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 8. party_groups
    $pdo->exec("CREATE TABLE `party_groups` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 9. parties
    $pdo->exec("CREATE TABLE `parties` (
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
        CONSTRAINT `fk_party_group` FOREIGN KEY (`party_group_id`) REFERENCES `party_groups` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 10. party_addresses
    $pdo->exec("CREATE TABLE `party_addresses` (
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
        CONSTRAINT `fk_address_party` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 11. bookings
    // Using phase 3 enhanced version
    $pdo->exec("CREATE TABLE `bookings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `service_id` int(11) NOT NULL,
        `assigned_to` int(11) DEFAULT NULL,
        `booking_date` date NOT NULL,
        `booking_time` time NOT NULL,
        `status` enum('pending', 'confirmed', 'assigned', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
        `notes` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
        CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 12. calls
    $pdo->exec("CREATE TABLE `calls` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `category` enum('booking','complaint','manual') NOT NULL,
        `service_id` int(11) DEFAULT NULL,
        `subject` varchar(255) NOT NULL,
        `description` text DEFAULT NULL,
        `status` enum('pending','open','assigned','in-progress','resolved','cancelled') NOT NULL DEFAULT 'open',
        `assigned_to` int(11) DEFAULT NULL,
        `call_date` date NOT NULL,
        `call_time` time NOT NULL,
        `reference_id` int(11) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`),
        CONSTRAINT `fk_calls_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        CONSTRAINT `fk_calls_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
        CONSTRAINT `fk_calls_assigned` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 13. complaints
    $pdo->exec("CREATE TABLE `complaints` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `assigned_to` int(11) DEFAULT NULL,
        `subject` varchar(255) NOT NULL,
        `description` text NOT NULL,
        `status` enum('pending','assigned','in-progress','resolved','closed') DEFAULT 'pending',
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 14. tasks
    $pdo->exec("CREATE TABLE `tasks` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `description` text NOT NULL,
        `assigned_to` int(11) NOT NULL,
        `status` enum('assigned','in-progress','completed') NOT NULL DEFAULT 'assigned',
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 15. products
    $pdo->exec("CREATE TABLE `products` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `sku` varchar(50) NOT NULL,
        `price` decimal(10,2) NOT NULL,
        `stock` int(11) NOT NULL DEFAULT 0,
        `min_stock` int(11) NOT NULL DEFAULT 10,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 16. password_resets
    $pdo->exec("CREATE TABLE `password_resets` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `email` VARCHAR(255) NOT NULL,
        `token` VARCHAR(64) NOT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        INDEX `idx_token` (`token`),
        INDEX `idx_email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 17. team_members
    $pdo->exec("CREATE TABLE `team_members` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `designation` varchar(255) NOT NULL,
        `image` varchar(255) DEFAULT NULL,
        `linkedin` varchar(255) DEFAULT NULL,
        `twitter` varchar(255) DEFAULT NULL,
        `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 18. attendance
    $pdo->exec("CREATE TABLE `attendance` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `date` DATE NOT NULL,
        `check_in` TIME NULL,
        `check_out` TIME NULL,
        `status` ENUM('present', 'absent', 'leave') DEFAULT 'present',
        `work_hours` decimal(5,2) DEFAULT NULL,
        `late_minutes` int(11) NOT NULL DEFAULT 0,
        `overtime_minutes` int(11) NOT NULL DEFAULT 0,
        `notes` text DEFAULT NULL,
        `marked_by` enum('self','admin') NOT NULL DEFAULT 'self',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 19. leaves
    $pdo->exec("CREATE TABLE `leaves` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `leave_type` enum('casual','sick','earned','half_day','compensatory') NOT NULL DEFAULT 'casual',
        `from_date` date NOT NULL,
        `to_date` date NOT NULL,
        `days` decimal(3,1) NOT NULL DEFAULT 1.0,
        `reason` text DEFAULT NULL,
        `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
        `approved_by` int(11) DEFAULT NULL,
        `admin_remarks` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`),
        CONSTRAINT `fk_leave_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        CONSTRAINT `fk_leave_approver` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 20. attendance_settings
    $pdo->exec("CREATE TABLE `attendance_settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `shift_start` time NOT NULL DEFAULT '09:00:00',
        `shift_end` time NOT NULL DEFAULT '18:00:00',
        `late_threshold_minutes` int(11) NOT NULL DEFAULT 15,
        `half_day_hours` decimal(3,1) NOT NULL DEFAULT 4.0,
        `weekly_offs` varchar(20) NOT NULL DEFAULT '0',
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 21. expenses
    $pdo->exec("CREATE TABLE `expenses` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `amount` DECIMAL(10,2) NOT NULL,
        `description` TEXT,
        `receipt_image` VARCHAR(255) NULL,
        `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 22. invoices
    $pdo->exec("CREATE TABLE `invoices` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `booking_id` INT NOT NULL,
        `customer_id` INT NOT NULL,
        `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
        `amount` DECIMAL(10,2) NOT NULL,
        `tax_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `discount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `total_amount` DECIMAL(10,2) NOT NULL,
        `status` ENUM('unpaid', 'paid', 'cancelled') DEFAULT 'unpaid',
        `transaction_id` VARCHAR(255) NULL,
        `payment_date` DATETIME NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
        FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 23. service_inventory
    $pdo->exec("CREATE TABLE `service_inventory` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `service_id` INT NOT NULL,
        `inventory_id` INT NOT NULL,
        `quantity_needed` INT DEFAULT 1,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
        FOREIGN KEY (inventory_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 24. notifications
    $pdo->exec("CREATE TABLE `notifications` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `message` TEXT NOT NULL,
        `type` VARCHAR(20) DEFAULT 'info',
        `is_read` BOOLEAN DEFAULT FALSE,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    echo "Seeding default data...\n";

    // Seed roles
    $pdo->exec("INSERT INTO `roles` (`id`, `name`, `permissions`) VALUES
        (1, 'Admin', 'all'),
        (2, 'Manager', 'manage_services,manage_inventory,manage_employees'),
        (3, 'Employee', 'view_tasks,update_status'),
        (4, 'Vendor', 'manage_products'),
        (5, 'Customer', 'book_service,view_history')");

    // Seed admin user
    $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
    $pdo->exec("INSERT INTO `users` (`name`, `email`, `password`, `role_id`, `status`) VALUES
        ('Super Admin', 'admin@sms.com', '$hashed_password', 1, 'active'),
        ('Admin User', 'admin@admin.com', '$hashed_password', 1, 'active')");

    // Seed item units
    $pdo->exec("INSERT INTO `item_units` (`name`, `short_name`) VALUES
        ('Piece', 'PCS'), ('Kilogram', 'KG'), ('Gram', 'GM'), ('Litre', 'LTR'),
        ('Meter', 'MTR'), ('Box', 'BOX'), ('Pair', 'PR'), ('Set', 'SET'),
        ('Hour', 'HR'), ('Service', 'SRV'), ('Unit', 'UNT'), ('Dozen', 'DZN')");

    // Seed GST rates
    $pdo->exec("INSERT INTO `gst_rates` (`name`, `rate`, `type`) VALUES
        ('None', 0.00, 'GST'), ('GST@5%', 5.00, 'GST'), ('GST@12%', 12.00, 'GST'),
        ('GST@18%', 18.00, 'GST'), ('GST@28%', 28.00, 'GST')");

    // Seed party groups
    $pdo->exec("INSERT INTO `party_groups` (`name`) VALUES
        ('Sundry Debtors'), ('Sundry Creditors'), ('General'), ('Retail Customers')");

    // Seed attendance settings
    $pdo->exec("INSERT INTO `attendance_settings` (`id`, `shift_start`, `shift_end`, `late_threshold_minutes`, `half_day_hours`, `weekly_offs`)
        VALUES (1, '09:00:00', '18:00:00', 15, 4.0, '0')");

    // Seed Categories
    $pdo->exec("INSERT INTO `categories` (`name`, `description`) VALUES
        ('Home Appliances', 'Repair and maintenance of daily household electronic appliances'),
        ('Water Solutions', 'RO Plants, Water Heaters, and Geysers'),
        ('Solar Energy', 'Solar Water Heaters and Energy Systems')");

    // Enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "Master Database Reconstruction Complete!\n";

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    if (isset($e->errorInfo)) {
        print_r($e->errorInfo);
    }
}
