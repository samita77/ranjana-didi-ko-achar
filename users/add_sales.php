<?php
session_start();
require '../connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Please log in first!'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input values
    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    $unit_price = (float) $_POST['unit_price'];
    $total_price = $quantity * $unit_price;
    $sale_date = date('Y-m-d H:i:s'); // Use the current timestamp

    // Step 1: Check if the product belongs to the logged-in user
    $stmt = $conn->prepare("SELECT id, quantity_in_stock FROM products WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "<script>alert('Unauthorized action!'); window.location.href = 'view_sales.php';</script>";
        exit();
    }

    // Step 2: Check stock availability
    if ($product['quantity_in_stock'] >= $quantity) {
        // Step 3: Generate the next sales_code for this user
        $stmt = $conn->prepare("SELECT IFNULL(MAX(sales_code), 0) + 1 AS next_sales_code FROM sales WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $sales_code = max(1, $row['next_sales_code']); // Ensure it starts from 1

        // Step 4: Insert sale record (linked to the logged-in user)
        $insert_stmt = $conn->prepare("INSERT INTO sales (user_id, sales_code, product_id, quantity, unit_price, total_price, sale_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("iiiidds", $user_id, $sales_code, $product_id, $quantity, $unit_price, $total_price, $sale_date);
        $insert_stmt->execute();

        // Step 5: Update stock in the product table
        $update_stmt = $conn->prepare("UPDATE products SET quantity_in_stock = quantity_in_stock - ? WHERE id = ? AND user_id = ?");
        $update_stmt->bind_param("iii", $quantity, $product_id, $user_id);
        $update_stmt->execute();

        echo "<script>alert('Sale recorded successfully!'); window.location.href = 'view_sales.php';</script>";
        exit();
    } else {
        echo "<script>alert('Not enough stock available!'); window.location.href = 'view_sales.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="add.css">
    <title>Add Sale</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Add New Sale</h1>
        </header>
        <main>
            <section>
                <form action="add_sales.php" method="POST">
                    <div class="container-box">
                        <div class="content">
                            <label for="product_id">Product:</label>
                            <select id="product_id" name="product_id" required>
                                <option value="">Select Product</option>
                                <?php
                                $result = $conn->prepare("SELECT id, product_name, unit_price, quantity_in_stock FROM products WHERE user_id = ?");
                                $result->bind_param("i", $user_id);
                                $result->execute();
                                $data = $result->get_result();

                                while ($row = $data->fetch_assoc()) {
                                    echo "<option value='{$row['id']}' data-price='{$row['unit_price']}' data-stock='{$row['quantity_in_stock']}'>
                      {$row['product_name']} (Stock: {$row['quantity_in_stock']})
                      </option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- <p id="stock_available" style="color: grey;">Stock: N/A</p> -->
                        <div class="content">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" required>
                        </div>
                        <div class="content">
                            <label for="unit_price">Unit Price:</label>
                            <input type="number" id="unit_price" name="unit_price" step="0.01" readonly required>
                        </div>
                        <div class="content">
                            <label for="total_price">Total Price:</label>
                            <input type="number" id="total_price" name="total_price" step="0.01" readonly>
                        </div>
                        <div class="content">
                            <label for="sale_date">Sales Date:</label>
                            <input type="date" id="sale_date" name="sale_date" required><br>
                        </div>
                        <div class="class">
                            <button type="submit">Add Sale</button>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>
    <script>
        // Automatically calculate total price
        function calculateTotalPrice() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            const totalPrice = quantity * unitPrice;
            document.getElementById('total_price').value = totalPrice.toFixed(2);
        }

        // Auto-update unit price and stock based on selected product
        function updateProductDetails() {
            const productSelect = document.getElementById('product_id');
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const unitPrice = selectedOption.getAttribute('data-price');
            const stock = selectedOption.getAttribute('data-stock');
            document.getElementById('unit_price').value = unitPrice;
            document.getElementById('stock_available').textContent = stock;
            calculateTotalPrice();
        }

        window.onload = function() {
            document.getElementById('quantity').addEventListener('input', calculateTotalPrice);
            document.getElementById('unit_price').addEventListener('input', calculateTotalPrice);
            document.getElementById('product_id').addEventListener('change', updateProductDetails);
        };
    </script>
</body>

</html>