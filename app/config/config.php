<?php
// DB Params
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || $_SERVER['HTTP_HOST'] == '127.0.0.1' || strpos($_SERVER['HTTP_HOST'], ':8080') !== false) {
    // Local DB Params
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'service_management_db');
    define('URLROOT', 'http://localhost/Service Management System');
} else {
    // Live DB Params (Hostinger) - Comprehensive Check
    define('DB_HOST', 'localhost');
    define('DB_USER', 'u523255408_servicemanager');
    define('DB_PASS', '3phuH1s&');
    define('DB_NAME', 'u523255408_servicemanager');
    define('URLROOT', 'https://honeydew-gazelle-593738.hostingersite.com');
}

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// GSTIN Verification API Key (Obtain from gstincheck.co.in)
define('GSTIN_API_KEY', 'YOUR_API_KEY_HERE');

// Site Name
define('SITENAME', 'Service Management System');
