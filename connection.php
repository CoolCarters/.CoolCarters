<?php
// Enable error reporting (for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Oracle connection
$username = 'bibash';               // your DB username
$password = 'bibash';               // your DB password
$connection_string = 'localhost/XE'; // or use '127.0.0.1/XE'

$conn = oci_connect($username, $password, $connection_string);

if (!$conn) {
    $e = oci_error();
    die("❌ Oracle connection error: " . htmlentities($e['message']));
} else {
    // Optional: confirm connection (comment in production)
    echo "✅ Connected to Oracle!";
}
