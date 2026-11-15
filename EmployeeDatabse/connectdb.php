<?php
// Shared database connection - included by every page
$db_host = 'localhost';
$db_name = 'dg250077289_cv';
$username = 'dg250077289';
$password = 'x3EHQOw2jWoWD9dkJUtHUNej6';

$conn = new mysqli($db_host, $username, $password, $db_name);
// checks if connection works, if not shows an error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Short helper to safely print user data in HTML
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
