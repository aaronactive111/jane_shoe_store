<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Assuming you have cart items in the session
$cartItems = $_SESSION['cart']; // Example: array of items in the cart
$userId = $_SESSION['userID']; // User ID from session
$totalAmount = 0; // Initialize total amount

// Calculate total amount
foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity']; // Adjust based on your cart structure
}

// Prepare the SQL statement to insert the order
$stmt = $conn->prepare("INSERT INTO Orders (user_id, order_date, total_amount, delivery_status) VALUES (?, NOW(), ?, 'pending')");
if ($stmt) {
    $stmt->bind_param("id", $userId, $totalAmount); // 'id' means integer, decimal

    // Execute the statement
    if ($stmt->execute()) {
        // Optionally clear the cart after successful order placement
        unset($_SESSION['cart']);
        
        // Redirect to a confirmation page
        header("Location: order_confirmation.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

// Close the connection
$conn->close();
?>
