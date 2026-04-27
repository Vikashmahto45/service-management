<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Auto-Discover Repair Tool</h1>";

// 1. Find Config
$configPath = "";
$searchPaths = ['../app/config/config.php', '../../app/config/config.php', './app/config/config.php'];
foreach($searchPaths as $p){
    if(file_exists($p)){ $configPath = $p; break; }
}

if(!$configPath) die("Could not find config.php");
require $configPath;
echo "Config Loaded.<br>";

// 2. Smart Discovery for Database.php
function findFile($dir, $fileName) {
    if(!is_dir($dir)) return false;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            $res = findFile($path, $fileName);
            if ($res) return $res;
        } else if (strtolower($file) == strtolower($fileName)) {
            return $path;
        }
    }
    return false;
}

$startDir = dirname(dirname($configPath)); // Should be the 'app' directory
echo "Scanning directory: $startDir for Database.php...<br>";
$dbPath = findFile($startDir, 'Database.php');

if(!$dbPath) die("CRITICAL: Database.php not found in $startDir");

echo "Database found at: $dbPath<br>";
require $dbPath;

$db = new Database();
$email = 'admin@test.com';
$password_plain = '123456';
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

// 3. Force Reset
$db->query('DELETE FROM users WHERE email = :email');
$db->bind(':email', $email);
$db->execute();

$db->query('INSERT INTO users (name, email, phone, password, role_id, status, address) VALUES ("Primary Admin", :email, "0000000000", :password, 1, "active", "System HQ")');
$db->bind(':email', $email);
$db->bind(':password', $password_hashed);

if($db->execute()){
    echo "<h2 style='color:green;'>SUCCESS! Access Granted.</h2>";
    echo "<p>Account <b>$email</b> is now a Super Admin (Role 1).</p>";
    echo "<p>Login at: <a href='/admins/login'>/admins/login</a></p>";
} else {
    echo "<h2 style='color:red;'>Database Error</h2>";
}
