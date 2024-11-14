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

// Initialize success message variable
$message = "";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Access the correct names from the form
    $designPreferences = $conn->real_escape_string($_POST['design']);
    $size = $conn->real_escape_string($_POST['size']);
    $materials = $conn->real_escape_string($_POST['materials']);
    $address = $conn->real_escape_string($_POST['address']);
    $userid = $conn->real_escape_string($_POST['user_id']); // Corrected to match the input name

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO CustomOrders (design_preferences, size, materials, address, userid) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssi", $designPreferences, $size, $materials, $address, $userid); // Updated bind_param types
        
        // Execute the statement
        if ($stmt->execute()) {
            $message = "Custom order placed successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Jane's Shoe Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: blue;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .message {
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            border-radius: 4px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: blue;
            color: #ffffff;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="message">
        <?php echo $message; ?>
    </div>
    <footer>
        <p>&copy; 2024 Jane's Shoe Store</p>
    </footer>
</body>
</html>
