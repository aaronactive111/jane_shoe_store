<?php
// products.php

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jane_shoe_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is an AJAX request
if (isset($_GET['ajax'])) {
    // Fetch products
    $sql = "SELECT * FROM products"; // Adjust this query to your table structure
    $result = $conn->query($sql);

    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    // Return products as JSON
    echo json_encode($products);
}

// Close the connection
$conn->close();
?>
