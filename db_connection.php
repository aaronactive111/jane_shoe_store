<?php
// db_connection.php

// Database credentials
$servername = "localhost"; // Hostname
$username = "root";         // Default XAMPP username
$password = "";             // Default XAMPP password (usually empty)
$dbname = "jane_shoe_store"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set the character set to UTF-8 for proper encoding
$conn->set_charset("utf8");

// Uncomment the next line if you need to start transactions
$conn->autocommit(FALSE); // Start transaction

?>
