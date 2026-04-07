<?php
/**
 * Migration: Attendance Module Enhancement
 * Run: http://localhost:8080/Service Management System/public/run_migration_attendance.php
 */

require_once dirname(__DIR__) . '/app/config/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "<h2>Running Attendance Migration...</h2>";

    $statements = [];

    // 1. Add new columns to attendance table (only if not exists)
    $cols = $pdo->query("DESCRIBE attendance")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('work_hours', $cols)) {
        $statements[] = "ALTER TABLE `attendance` ADD COLUMN `work_hours` decimal(5,2) DEFAULT NULL AFTER `status`";
    }
    if (!in_array('late_minutes', $cols)) {
        $statements[] = "ALTER TABLE `attendance` ADD COLUMN `late_minutes` int(11) NOT NULL DEFAULT 0 AFTER `work_hours`";
    }
    if (!in_array('overtime_minutes', $cols)) {
        $statements[] = "ALTER TABLE `attendance` ADD COLUMN `overtime_minutes` int(11) NOT NULL DEFAULT 0 AFTER `late_minutes`";
    }
    if (!in_array('notes', $cols)) {
        $statements[] = "ALTER TABLE `attendance` ADD COLUMN `notes` text DEFAULT NULL AFTER `overtime_minutes`";
    }
    if (!in_array('marked_by', $cols)) {
        $statements[] = "ALTER TABLE `attendance` ADD COLUMN `marked_by` enum('self','admin') NOT NULL DEFAULT 'self' AFTER `notes`";
    }

    // 2. Leaves table
    $statements[] = "CREATE TABLE IF NOT EXISTS `leaves` (
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
      KEY `fk_leave_user` (`user_id`),
      KEY `fk_leave_approver` (`approved_by`),
      CONSTRAINT `fk_leave_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
      CONSTRAINT `fk_leave_approver` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // 3. Attendance settings table
    $statements[] = "CREATE TABLE IF NOT EXISTS `attendance_settings` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `shift_start` time NOT NULL DEFAULT '09:00:00',
      `shift_end` time NOT NULL DEFAULT '18:00:00',
      `late_threshold_minutes` int(11) NOT NULL DEFAULT 15,
      `half_day_hours` decimal(3,1) NOT NULL DEFAULT 4.0,
      `weekly_offs` varchar(20) NOT NULL DEFAULT '0',
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // 4. Seed default settings
    $statements[] = "INSERT IGNORE INTO `attendance_settings` (`id`, `shift_start`, `shift_end`, `late_threshold_minutes`, `half_day_hours`, `weekly_offs`)
    VALUES (1, '09:00:00', '18:00:00', 15, 4.0, '0')";

    // Execute
    $success = 0;
    $errors = [];

    foreach ($statements as $i => $sql) {
        try {
            $pdo->exec($sql);
            $success++;
            echo "<p>✅ Statement " . ($i + 1) . " executed.</p>";
        } catch(PDOException $e) {
            $errors[] = $e->getMessage();
            echo "<p>⚠️ Statement " . ($i + 1) . ": " . $e->getMessage() . "</p>";
        }
    }

    echo "<hr><h3>Result: $success/" . count($statements) . " statements executed</h3>";

    // Verify
    echo "<h4>Table Verification:</h4><ul>";
    foreach (['attendance', 'leaves', 'attendance_settings'] as $t) {
        $count = $pdo->query("SHOW TABLES LIKE '$t'")->rowCount();
        echo "<li>" . ($count ? '✅' : '❌') . " $t</li>";
    }
    echo "</ul>";

    // Verify attendance columns
    echo "<h4>Attendance Columns:</h4><ul>";
    $cols = $pdo->query("DESCRIBE attendance")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($cols as $c) {
        echo "<li>$c</li>";
    }
    echo "</ul>";

    echo "<p><a href='" . URLROOT . "/adminAttendance'>Go to Attendance →</a></p>";

} catch(PDOException $e) {
    die("<h2>Error</h2><p>" . $e->getMessage() . "</p>");
}
