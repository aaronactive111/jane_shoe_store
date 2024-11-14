<?php
// Database connection details
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

// Start session to check if the user is an admin
session_start();
if (!isset($_SESSION['admin'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Fetch total sales (sum of total_amount) and total orders (count of orders) for the last month
$salesQuery = "SELECT SUM(total_amount) AS total_sales, COUNT(order_id) AS total_orders 
               FROM Orders 
               WHERE order_date >= NOW() - INTERVAL 1 MONTH";
$salesResult = $conn->query($salesQuery);
$salesData = $salesResult ? $salesResult->fetch_assoc() : null;
$total_sales = $salesData['total_sales'] ?? 0;
$total_orders = $salesData['total_orders'] ?? 0;

// Fetch detailed sales data per product for the last month
$productSalesQuery = "
    SELECT p.name, p.stock, SUM(oi.quantity) AS total_sold, SUM(oi.price_at_purchase * oi.quantity) AS total_sales
    FROM Products p
    LEFT JOIN OrderItems oi ON p.id = oi.product_id
    LEFT JOIN Orders o ON oi.order_id = o.order_id
    WHERE o.order_date >= NOW() - INTERVAL 1 MONTH AND o.delivery_status = 'Completed'
    GROUP BY p.id
";
$productSalesResult = $conn->query($productSalesQuery);

$products = [];
if ($productSalesResult) {
    while ($product = $productSalesResult->fetch_assoc()) {
        $products[] = [
            'name' => $product['name'],
            'stock' => $product['stock'],
            'total_sold' => $product['total_sold'] ?? 0,
            'total_sales' => $product['total_sales'] ?? 0
        ];
    }
}

// Return the data as JSON
echo json_encode([
    'total_sales' => $total_sales,
    'total_orders' => $total_orders,
    'products' => $products
]);

$conn->close();
?>
