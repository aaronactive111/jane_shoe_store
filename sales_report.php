<?php
// fetch_sales_data.php

header('Content-Type: application/json'); // Set the content type to JSON

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jane_shoe_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Total sales and total orders for the last month
$totalSalesQuery = "SELECT SUM(total_amount) AS total_sales, COUNT(order_id) AS total_orders 
                    FROM Orders 
                    WHERE delivery_status = 'completed' 
                    AND order_date >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
$totalSalesResult = $conn->query($totalSalesQuery);

if (!$totalSalesResult) {
    die(json_encode(["error" => "Error executing total sales query: " . $conn->error]));
}

$totalSalesData = $totalSalesResult->fetch_assoc();
$total_sales = $totalSalesData['total_sales'] ?? 0;
$total_orders = $totalSalesData['total_orders'] ?? 0;

// Payment details for the last month
$paymentDetailsQuery = "SELECT payment_id, order_id, amount, payment_date, payment_status 
                        FROM Payments 
                        WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
$paymentDetailsResult = $conn->query($paymentDetailsQuery);

if (!$paymentDetailsResult) {
    die(json_encode(["error" => "Error executing payment details query: " . $conn->error]));
}

// Prepare the payment data
$payments = [];
if ($paymentDetailsResult->num_rows > 0) {
    while ($row = $paymentDetailsResult->fetch_assoc()) {
        $payments[] = [
            "payment_id" => $row['payment_id'],
            "order_id" => $row['order_id'],
            "amount" => number_format($row['amount'], 2),
            "payment_date" => $row['payment_date'],
            "payment_status" => $row['payment_status']
        ];
    }
}

// Return the data as JSON
echo json_encode([
    "total_sales" => number_format($total_sales, 2),
    "total_orders" => $total_orders,
    "payments" => $payments
]);

// Close the connection
$conn->close();
