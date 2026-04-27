<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Ultimate Smart Repair Tool</h1>";

// Try different paths to find config
$paths = [
    '../app/config/config.php',
    '../../app/config/config.php',
    './app/config/config.php'
];

$configPath = "";
foreach($paths as $p){
    if(file_exists($p)){
        $configPath = $p;
        echo "Found Config at: $p<br>";
        break;
    }
}

if(!$configPath){
    die("FATAL ERROR: Could not locate app/config/config.php anywhere. Please check your folder structure.");
}

require $configPath;

// Adjust library paths based on found config
$libPath = dirname($configPath) . '/../libraries/Database.php';
if(!file_exists($libPath)){
    die("FATAL ERROR: Found config, but matching Database.php not at: $libPath");
}
require $libPath;

$db = new Database();

$email = 'admin@test.com';
$password_plain = '123456';
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

// 1. Wipe old admin attempts
echo "Cleaning up database...<br>";
$db->query('DELETE FROM users WHERE email = :email');
$db->bind(':email', $email);
$db->execute();

// 2. Create Fresh Super Admin
echo "Creating Fresh Super Admin account...<br>";
$db->query('INSERT INTO users (name, email, phone, password, role_id, status, address) VALUES ("Primary Admin", :email, "0000000000", :password, 1, "active", "System HQ")');
$db->bind(':email', $email);
$db->bind(':password', $password_hashed);

if($db->execute()){
    echo "<h2 style='color:green;'>SUCCESS!</h2>";
    echo "<p>Account <b>$email</b> created successfully with Role 1.</p>";
    echo "<p>Go to: <a href='/admins/login'>/admins/login</a> and use password: <b>$password_plain</b></p>";
} else {
    echo "<h2 style='color:red;'>FAILED!</h2>";
    echo "Check your database connection in config.php";
}

echo "<hr><h3>Current Users in System:</h3>";
$db->query('SELECT id, email, role_id, status FROM users');
$results = $db->resultSet();
foreach($results as $user){
    echo "ID: {$user->id} | Email: {$user->email} | Role: {$user->role_id} | Status: {$user->status}<br>";
}
