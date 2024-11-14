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

// upload_product.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect product data
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_stock = $_POST['product_stock'];
    
    // Handle file upload
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    
    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
        // Add product to database
        addProduct($conn, $product_name, $product_description, $product_price, $product_stock, $target_file);
    } else {
        echo "Error uploading file.";
    }
}

function addProduct($conn, $name, $description, $price, $stock, $image_url) {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssdss", $name, $description, $price, $stock, $image_url);

    // Execute the query
    if ($stmt->execute()) {
        // Show success message with a button to navigate back to products
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Upload Successful</title>
            <link rel='stylesheet' href='styles.css'>
        </head>
        <body>
            <header>
                <h1>Product Upload Successful!</h1>
            </header>
            <div class='container'>
                <p>The product has been uploaded successfully!</p>
                
            </div>
            <footer>
                <p>&copy; 2024 Jane's Shoe Store</p>
            </footer>
        </body>
        </html>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
