<?php
session_start();
require '../connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Fetch existing products
$existing_products = [];
$product_query = $conn->prepare("SELECT product_name, quantity_in_stock FROM products WHERE user_id = ?");
$product_query->bind_param("i", $user_id);
$product_query->execute();
$result = $product_query->get_result();

while ($row = $result->fetch_assoc()) {
    $existing_products[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity_in_stock = (int) $_POST['quantity_in_stock']; // New quantity to add
    $unit_price = (float) $_POST['unit_price'];
    $created_at = date('Y-m-d H:i:s');
    $total_price = $quantity_in_stock * $unit_price;

    // Handle Image Upload
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_path = "";
    if (!empty($_FILES['product_image']['name'])) {
        $file_tmp = $_FILES['product_image']['tmp_name'];
        $file_name = basename($_FILES['product_image']['name']);
        $image_path = $target_dir . $file_name;
        // Validate the file is an image
        $check = getimagesize($file_tmp);
        if ($check !== false) {
            move_uploaded_file($file_tmp, $image_path);
        } else {
            echo "<script>alert('File is not an image!'); window.location.href = 'add_product.php';</script>";
            exit();
        }
    }

    // Step 2: Check if the product already exists for this user
    $stmt = $conn->prepare("SELECT id, quantity_in_stock FROM products WHERE product_name = ? AND user_id = ?");
    $stmt->bind_param("si", $product_name, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update stock if product already exists
        $row = $result->fetch_assoc();
        $new_quantity = $quantity_in_stock; // Replace existing stock with new quantity
        $total_price = $unit_price * $new_quantity; // Ensure total price is updated correctly
    
        $update_stmt = $conn->prepare("UPDATE products SET quantity_in_stock = ?, product_code = ?, description = ?, unit_price = ?, total_price = ?, image_path = ? WHERE id = ?");
        $update_stmt->bind_param("iissdsi", $new_quantity, $product_code, $description, $unit_price, $total_price, $image_path, $row['id']);
        $update_stmt->execute();
    
        echo "<script>alert('Product stock updated successfully!'); window.location.href = 'view_product.php';</script>";
        exit();
    } else {
        // Step 3: Insert new product with generated product_code
        $insert_stmt = $conn->prepare("INSERT INTO products (user_id, product_code, product_name, description, image_path, quantity_in_stock, unit_price, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("iisssidds", $user_id, $product_code, $product_name, $description, $image_path, $quantity_in_stock, $unit_price, $total_price, $created_at);
        $insert_stmt->execute();

        echo "<script>alert('Product added successfully!'); window.location.href = 'view_product.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Add Product</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Add New Product</h1>
        </header>
        <main>
            <section>
                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <div class="container-box">
                        <div class="content">
                            <label for="existing_product">Choose Existing Product:</label>
                            <select id="existing_product" onchange="fillProductDetails()">
                                <option value="">-- Select a Product --</option>
                                <?php foreach ($existing_products as $product) : ?>
                                    <option value="<?= $product['product_name'] ?>">
                                        <?= $product['product_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="content">
                            <label for="product_name">Or Enter New Product Name:</label>
                            <input type="text" id="product_name" name="product_name" required>
                        </div>
                        <div class="content">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description"></textarea>
                        </div>
                        <div class="content">
                            <label for="product_image">Product Image:</label>
                            <input type="file" id="product_image" name="product_image" accept="image/*">
                        </div>
                        <div class="content">
                            <label for="quantity_in_stock">Quantity in Stock:</label>
                            <input type="number" id="quantity_in_stock" name="quantity_in_stock" required>
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
                            <label for="created_at">Created At:</label>
                            <input type="date" id="created_at" name="created_at" required>
                        </div>
                        <div class="class">
                            <button type="submit">Add Product</button>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>
        function calculateTotalPrice() {
            const quantity = parseFloat(document.getElementById('quantity_in_stock').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            document.getElementById('total_price').value = (quantity * unitPrice).toFixed(2);
        }

        function fillProductDetails() {
            let selectedProduct = document.getElementById("existing_product");
            let productNameInput = document.getElementById("product_name");
            let quantityInput = document.getElementById("quantity_in_stock");

            if (selectedProduct.value !== "") {
                productNameInput.value = selectedProduct.value;
                quantityInput.value = ""; // Clear the quantity field
                quantityInput.readOnly = false; // Allow manual editing
            } else {
                productNameInput.value = "";
                quantityInput.value = "";
                quantityInput.readOnly = false;
            }
        }

        window.onload = function() {
            document.getElementById('quantity_in_stock').addEventListener('input', calculateTotalPrice);
            document.getElementById('unit_price').addEventListener('input', calculateTotalPrice);
        };
    </script>
</body>

</html>