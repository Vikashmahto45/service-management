<?php
require 'app/config/config.php';
require 'app/libraries/Database.php';

$db = new Database();

$email = 'admin@test.com';

// Check if user exists
$db->query('SELECT * FROM users WHERE email = :email');
$db->bind(':email', $email);
$row = $db->single();

if($row){
    echo "USER FOUND:\n";
    echo "Email: " . $row->email . "\n";
    echo "Current Role ID: " . $row->role_id . "\n";
    echo "Status: " . $row->status . "\n";
    
    if($row->role_id != 1){
        echo "FIXING ROLE: Changing Role ID to 1...\n";
        $db->query('UPDATE users SET role_id = 1, status = "active" WHERE email = :email');
        $db->bind(':email', $email);
        if($db->execute()){
            echo "SUCCESS: Your account is now a Super Admin.\n";
        }
    } else {
        echo "Account already has Role ID 1.\n";
    }
} else {
    echo "USER NOT FOUND: 'admin@test.com' does not exist.\n";
    echo "CREATING ACCOUNT: Inserting fresh Super Admin account...\n";
    
    $password = password_hash('123456', PASSWORD_DEFAULT);
    $db->query('INSERT INTO users (name, email, password, role_id, status) VALUES ("Super Admin", :email, :password, 1, "active")');
    $db->bind(':email', $email);
    $db->bind(':password', $password);
    
    if($db->execute()){
        echo "SUCCESS: Fresh Super Admin account created with password '123456'.\n";
    } else {
        echo "ERROR: Could not create account.\n";
    }
}
