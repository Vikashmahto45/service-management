<?php
// DB Params
if (in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', 'localhost:8080'])) {
    // Local DB Params
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'service_management_db');
    define('URLROOT', 'http://localhost/Service Management System');
} else {
    // Live DB Params (Hostinger)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'u523255408_servicemanager');
    define('DB_PASS', '3phuH1s&');
    define('DB_NAME', 'u523255408_servicemanager');
    define('URLROOT', 'https://honeydew-gazelle-593738.hostingersite.com');
}

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// Site Name
define('SITENAME', 'Service Management System');
