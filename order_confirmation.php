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



session_start(); // Start the session

// Generate a random order ID
$orderId = rand(10000, 99999);

// Retrieve total amount from session, default to 0 if not set
$totalAmount = isset($_SESSION['totalAmount']) ? $_SESSION['totalAmount'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Jane's Shoe Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Order Confirmation</h1>
    </header>
    <nav>
        <ul>
            <li><a href="products.html">Continue Shopping</a></li>
            <li><a href="logout.html">Logout</a></li>
        </ul>
    </nav>
    <div class="container">
        <h2>Thank you for your order!</h2>
        <p>Your order has been confirmed. We will send you a confirmation email shortly.</p>
    </div>
    <footer>
        <p>&copy; 2024 Jane's Shoe Store</p>
    </footer>
</body>
</html>
