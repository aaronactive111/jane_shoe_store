<?php
// process_payment.php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the payment data
    $name = htmlspecialchars($_POST['name']);
    $card_number = htmlspecialchars($_POST['card_number']);
    $expiry = htmlspecialchars($_POST['expiry']);
    $cvv = htmlspecialchars($_POST['cvv']);
    $billing_address = htmlspecialchars($_POST['billing_address']);
    $delivery_method = htmlspecialchars($_POST['delivery_method']);

    // Example user ID - replace this with your actual logic to get the user ID
    $userId = 1;  // Replace with actual user ID logic

    // Generate a random total amount for the order
    $minAmount = 10.00; // Minimum amount for the order
    $maxAmount = 9000.00; // Maximum amount for the order
    $totalAmount = round(rand($minAmount * 100, $maxAmount * 100) / 100, 2); // Random amount rounded to 2 decimal places

    // Prepare the SQL statement to insert the order
    $stmt = $conn->prepare("INSERT INTO Orders (user_id, order_date, total_amount, delivery_status) VALUES (?, NOW(), ?, 'pending')");
    if ($stmt) {
        $stmt->bind_param("id", $userId, $totalAmount); // Bind parameters

        // Execute the statement
        if ($stmt->execute()) {
            // Get the generated order ID
            $orderId = $stmt->insert_id;

            // Set session variables for the order confirmation page
            session_start();
            $_SESSION['totalAmount'] = $totalAmount;
            $_SESSION['name'] = $name;

            // Insert payment record in the Payments table
            $payment_status = 'Completed'; // Assuming the payment is successful
            $payment_date = date("Y-m-d H:i:s");

            $stmt_payment = $conn->prepare("INSERT INTO Payments (order_id, amount, payment_date, payment_status) VALUES (?, ?, ?, ?)");
            if ($stmt_payment) {
                $stmt_payment->bind_param("idss", $orderId, $totalAmount, $payment_date, $payment_status);

                // Execute the payment statement
                if ($stmt_payment->execute()) {
                    // Payment record inserted successfully
                    $_SESSION['payment_id'] = $stmt_payment->insert_id; // Optionally store payment ID in session

                    // Now, insert a receipt record in the Receipts table
                    $date_issued = date("Y-m-d H:i:s");
                    $stmt_receipt = $conn->prepare("INSERT INTO Receipts (order_id, date_issued) VALUES (?, ?)");
                    if ($stmt_receipt) {
                        $stmt_receipt->bind_param("is", $orderId, $date_issued);

                        // Execute the receipt statement
                        if ($stmt_receipt->execute()) {
                            // Receipt record inserted successfully
                            $_SESSION['receipt_id'] = $stmt_receipt->insert_id; // Optionally store receipt ID in session
                        } else {
                            echo "Error inserting receipt record: " . $stmt_receipt->error;
                        }
                        $stmt_receipt->close();
                    } else {
                        echo "Error preparing receipt statement: " . $conn->error;
                    }
                } else {
                    echo "Error inserting payment record: " . $stmt_payment->error;
                }
                $stmt_payment->close();
            } else {
                echo "Error preparing payment statement: " . $conn->error;
            }

            // Redirect to order confirmation page
            header("Location: order_confirmation.php");
            exit(); // Ensure no further code is executed
        } else {
            echo "Error: " . $stmt->error; // Handle execution error
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error; // Handle preparation error
    }

    // Close the connection
    $conn->close();
} 
?>
