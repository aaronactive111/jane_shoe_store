<?php
// db_connection.php
$servername = "localhost";
$username = "root"; // your DB username
$password = ""; // your DB password
$dbname = "jane_shoe_store"; // your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to make a purchase.");
}

// Purchase processing logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id']; // Product ID from form
    $quantity = $_POST['quantity']; // Quantity from form
    $customer_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Get product price from the database
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($product_price);
    $stmt->fetch();
    $stmt->close();

    if ($product_price) {
        $total_price = $quantity * $product_price;

        // Insert order into the database
        $stmt = $conn->prepare("INSERT INTO orders (product_id, customer_id, quantity, total_price, order_date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiid", $product_id, $customer_id, $quantity, $total_price);

        if ($stmt->execute()) {
            echo "Purchase successful! Total price: $" . $total_price;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Product not found.";
    }
}

$conn->close();
?>
