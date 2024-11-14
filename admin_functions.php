<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome, Admin!</h2>
    <ul>
        <li><a href="view_products.php">View Products</a></li>
        <li><a href="process_sale.php">Process Sale</a></li>
        <li><a href="sales_report.php">View Sales Report</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
