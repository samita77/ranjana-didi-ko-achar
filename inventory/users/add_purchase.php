<?php
session_start();
require '../connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Please log in first!'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get logged-in user ID

// Step 1: Get next purchase number for this user
$stmt = $conn->prepare("SELECT IFNULL(MAX(purchase_code), 0) + 1 AS next_purchase FROM purchases WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$purchase_code = max(1, $row['next_purchase']); // Ensure purchase_code starts at 1

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $vendor_name = mysqli_real_escape_string($conn, $_POST['vendor_name']);
    $quantity = (int) $_POST['quantity'];
    $unit_price = (float) $_POST['unit_price'];
    $total_price = $quantity * $unit_price;
    $purchase_date = date('Y-m-d H:i:s'); // Store current timestamp

    // Validate inputs
    if ($quantity <= 0 || $unit_price <= 0) {
        echo "<script>alert('Quantity and unit price must be positive numbers!'); window.location.href = 'add_purchase.php';</script>";
        exit();
    }

    if (empty($product_name) || empty($vendor_name)) {
        echo "<script>alert('Product name and vendor name are required!'); window.location.href = 'add_purchase.php';</script>";
        exit();
    }

    // Step 2: Get or insert Vendor
    $stmt = $conn->prepare("SELECT id FROM vendor WHERE vendor_name = ? AND user_id = ?");
    $stmt->bind_param("si", $vendor_name, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Vendor exists, use its ID
        $vendor = $result->fetch_assoc();
        $vendor_id = $vendor['id'];
    } else {
        // Vendor doesn't exist, insert new vendor
        // Get next vendor number (similar to product_code logic in add_product.php)
        $stmt = $conn->prepare("SELECT IFNULL(MAX(vendor_number), 0) + 1 AS next_vendor FROM vendor WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $vendor_number = max(1, $row['next_vendor']); // Ensure it starts from 1

        // Insert vendor into the database
        $insert_stmt = $conn->prepare("INSERT INTO vendor (user_id, vendor_name, vendor_number) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("isi", $user_id, $vendor_name, $vendor_number);
        if (!$insert_stmt->execute()) {
            die("Error inserting vendor: " . $conn->error);
        }
        $vendor_id = $insert_stmt->insert_id;
        $insert_stmt->close();
    }
    $stmt->close();

    // Step 3: Get product ID if it exists (DO NOT update stock)
    $stmt = $conn->prepare("SELECT id FROM products WHERE product_name = ? AND user_id = ?");
    $stmt->bind_param("si", $product_name, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product exists, use its ID
        $product = $result->fetch_assoc();
        $product_id = $product['id'];
    } else {
        // Product doesn't exist, insert new product with stock = 0
        $description = "No description"; // Default description
        $stock = 0; // Do NOT increase stock from purchase
        $image_path = ""; // Default image path
    
        $insert_stmt = $conn->prepare("INSERT INTO products (user_id, product_name, description, quantity_in_stock, unit_price, total_price, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("issidds", $user_id, $product_name, $description, $stock, $unit_price, $total_price, $image_path);
        if (!$insert_stmt->execute()) {
            die("Error inserting product: " . $conn->error);
        }
        $product_id = $insert_stmt->insert_id;
    }

    // Step 4: Check if the same purchase already exists
    $check_purchase_stmt = $conn->prepare("SELECT id, quantity FROM purchases WHERE user_id = ? AND product_id = ? AND vendor_id = ? AND unit_price = ?");
    $check_purchase_stmt->bind_param("iiid", $user_id, $product_id, $vendor_id, $unit_price);
    $check_purchase_stmt->execute();
    $check_result = $check_purchase_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Purchase already exists: Update the quantity
        $existing_purchase = $check_result->fetch_assoc();
        $new_quantity = $existing_purchase['quantity'] + $quantity;
        $new_total_price = $new_quantity * $unit_price;

        $update_purchase_stmt = $conn->prepare("UPDATE purchases SET quantity = ?, total_price = ? WHERE id = ?");
        $update_purchase_stmt->bind_param("idi", $new_quantity, $new_total_price, $existing_purchase['id']);
        if (!$update_purchase_stmt->execute()) {
            die("Error updating purchase: " . $conn->error);
        }
    } else {
        // Purchase does not exist: Insert a new one
        $purchase_stmt = $conn->prepare("INSERT INTO purchases (user_id, purchase_code, product_id, vendor_id, quantity, unit_price, total_price, purchase_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $purchase_stmt->bind_param("iiiiiids", $user_id, $purchase_code, $product_id, $vendor_id, $quantity, $unit_price, $total_price, $purchase_date);
        if (!$purchase_stmt->execute()) {
            die("Error inserting purchase: " . $conn->error);
        }
    }

    echo "<script>alert('Purchase added successfully!'); window.location.href = 'view_purchase.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="add.css">
    <title>Add Purchase</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Add New Purchase</h1>
        </header>
        <main>
            <section>
                <form action="add_purchase.php" method="POST">
                    <div class="container-box">
                        <div class="content">
                            <label for="product_name">Product Name:</label>
                            <input type="text" id="product_name" name="product_name"  required>
                        </div>
                        <div class="content">
                            <label for="vendor_name">Vendor Name:</label>
                            <input type="text" id="vendor_name" name="vendor_name" required>
                        </div>
                        <div class="content">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" required>
                        </div>
                        <div class="content">
                            <label for="unit_price">Unit Price:</label>
                            <input type="number" id="unit_price" name="unit_price" step="0.01" required>
                        </div>
                        <div class="content">
                            <label for="total_price">Total Price:</label>
                            <input type="number" id="total_price" name="total_price" step="0.01" readonly>
                        </div>
                        <div class="content">
                            <label for="purchase_date">Purchase Date:</label>
                            <input type="date" name="purchase_date" required>
                        </div>
                        <div class="class">
                            <button type="submit">Add Purchase</button>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>

        function calculateTotalPrice() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            const totalPrice = quantity * unitPrice;
            document.getElementById('total_price').value = totalPrice.toFixed(2);
        }

        window.onload = function() {
            document.getElementById('quantity').addEventListener('input', calculateTotalPrice);
            document.getElementById('unit_price').addEventListener('input', calculateTotalPrice);
        };
    </script>
</body>

</html>
