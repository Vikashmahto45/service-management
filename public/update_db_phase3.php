<?php
$root = dirname(__DIR__);
require_once $root . '/app/config/config.php';
require_once $root . '/app/core/Database.php';

$db = new Database();
echo "Start Phase 3 Database Update...\n";

// 1. Update BOOKINGS table
try {
    $sql = "ALTER TABLE bookings 
            ADD COLUMN assigned_to INT NULL AFTER service_id,
            MODIFY COLUMN status ENUM('pending', 'confirmed', 'assigned', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
            ADD FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL";
    $db->query($sql);
    $db->execute();
    echo "Success: Updated bookings table.\n";
} catch (PDOException $e) {
    if(strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "Skipped: bookings table already updated.\n";
    } else {
        echo "Error updating bookings: " . $e->getMessage() . "\n";
    }
}

// 2. Update COMPLAINTS table
try {
    $sql = "ALTER TABLE complaints 
            ADD COLUMN assigned_to INT NULL AFTER user_id,
            MODIFY COLUMN status ENUM('pending', 'assigned', 'in_progress', 'resolved', 'closed') DEFAULT 'pending',
            ADD FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL";
    $db->query($sql);
    $db->execute();
    echo "Success: Updated complaints table.\n";
} catch (PDOException $e) {
    if(strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "Skipped: complaints table already updated.\n";
    } else {
         echo "Error updating complaints: " . $e->getMessage() . "\n";
    }
}

// 3. Create ATTENDANCE table
$sql = "CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    check_in TIME NULL,
    check_out TIME NULL,
    status ENUM('present', 'absent', 'leave') DEFAULT 'present',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
try {
    $db->query($sql);
    $db->execute();
    echo "Success: Created attendance table.\n";
} catch (PDOException $e) {
    echo "Error creating attendance: " . $e->getMessage() . "\n";
}

// 4. Create EXPENSES table
$sql = "CREATE TABLE IF NOT EXISTS expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    receipt_image VARCHAR(255) NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
try {
    $db->query($sql);
    $db->execute();
    echo "Success: Created expenses table.\n";
} catch (PDOException $e) {
    echo "Error creating expenses: " . $e->getMessage() . "\n";
}

echo "Phase 3 Database Update Complete.\n";
