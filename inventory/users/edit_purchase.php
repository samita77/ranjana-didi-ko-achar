<?php
require '../connection.php';
session_start(); // Start session to get logged-in user ID

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Unauthorized access! Please log in.'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Check if a purchase_code is provided
if (!isset($_GET['purchase_code']) || empty($_GET['purchase_code'])) {
    echo "<script>alert('No purchase specified.'); window.location.href = 'view_purchase.php';</script>";
    exit();
}

$purchase_code = $_GET['purchase_code'];

// Fetch the specific purchase **only if it belongs to the logged-in user**
$query = "SELECT purchases.*, products.product_name, products.id AS product_id 
          FROM purchases 
          JOIN products ON purchases.product_id = products.id 
          WHERE purchases.purchase_code = ? AND purchases.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $purchase_code, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$purchase = $result->fetch_assoc();

// If no purchase is found or it doesn't belong to the user
if (!$purchase) {
    echo "<script>alert('Purchase not found or unauthorized.'); window.location.href = 'view_purchase.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name']);
    $vendor_name = trim($_POST['vendor_name']);
    $quantity = (int)$_POST['quantity'];
    $purchase_date = trim($_POST['purchase_date']);
    $unit_price = (float)$_POST['unit_price'];
    $total_price = $quantity * $unit_price;

    if ($quantity <= 0 || $unit_price <= 0) {
        echo "<script>alert('Quantity and unit price must be greater than zero.'); window.history.back();</script>";
        exit();
    }

    // Check if the new product name already exists
    $product_check_query = "SELECT id FROM products WHERE LOWER(product_name) = LOWER(?) AND user_id = ? LIMIT 1";
    $stmt = $conn->prepare($product_check_query);
    $stmt->bind_param("si", $product_name, $user_id);
    $stmt->execute();
    $product_check_result = $stmt->get_result();
    $existing_product = $product_check_result->fetch_assoc();

    if ($existing_product) {
        // Use existing product ID
        $product_id = $existing_product['id'];
    } else {
        // Update product name in the database
        $update_product_query = "UPDATE products SET product_name = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($update_product_query);
        $stmt->bind_param("sii", $product_name, $purchase['product_id'], $user_id);
        
        if ($stmt->execute()) {
            $product_id = $purchase['product_id']; // Keep using the same product ID
        } else {
            echo "<script>alert('Failed to update product name.'); window.history.back();</script>";
            exit();
        }
    }

     // Check if vendor exists
     $vendor_check_query = "SELECT id FROM vendor WHERE LOWER(vendor_name) = LOWER(?) AND user_id = ? LIMIT 1";
     $stmt = $conn->prepare($vendor_check_query);
     $stmt->bind_param("si", $vendor_name, $user_id);
     $stmt->execute();
     $vendor_check_result = $stmt->get_result();
     $existing_vendor = $vendor_check_result->fetch_assoc();
 
     if ($existing_vendor) {
         $vendor_id = $existing_vendor['id'];
     } else {
         // Update vendor name in the database
         $update_vendor_query = "UPDATE vendors SET vendor_name = ? WHERE id = ? AND user_id = ?";
         $stmt = $conn->prepare($update_vendor_query);
         $stmt->bind_param("sii", $vendor_name, $purchase['vendor_id'], $user_id);
 
         if ($stmt->execute()) {
             $vendor_id = $purchase['vendor_id'];
         } else {
             echo "<script>alert('Failed to update vendor name.'); window.history.back();</script>";
             exit();
         }
     }

    // Update the purchase record
    $update_query = "UPDATE purchases 
                     SET product_id = ?, 
                         vendor_name = ?, 
                         quantity = ?, 
                         purchase_date = ?, 
                         total_price = ?, 
                         unit_price = ?
                     WHERE purchase_code = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("isisdisi", $product_id, $vendor_name, $quantity, $purchase_date, $total_price, $unit_price, $purchase_code, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Purchase updated successfully!'); window.location.href = 'view_purchase.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating purchase.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit.css">
    <title>Edit Purchase</title>
</head>
<body>
    <script>
        function calculateTotal() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            document.getElementById('total_price').value = (quantity * unitPrice).toFixed(2);
        }

        window.onload = function() {
            document.getElementById('quantity').addEventListener('input', calculateTotal);
            document.getElementById('unit_price').addEventListener('input', calculateTotal);
        };
    </script>

    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Edit Purchase</h1>
        </header>
        <main>
            <section>
                <form action="edit_purchase.php?purchase_code=<?php echo htmlspecialchars($purchase_code); ?>" method="POST">
                    <div class="container-box">
                        <div class="content">
                            <label for="product_name">Product Name: </label>
                            <input type="text" name="product_name" value="<?php echo htmlspecialchars($purchase['product_name']); ?>" required>
                        </div>
                        <div class="content">
                            <label for="vendor_name">Vendor Name:</label>
                            <input type="text" name="vendor_name" value="<?php echo htmlspecialchars($purchase['vendor_name']); ?>" required>
                        </div>
                        <div class="content">
                            <label for="quantity">Quantity: </label>
                            <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($purchase['quantity']); ?>" required>
                        </div>
                        <div class="content">
                            <label for="unit_price">Unit Price:</label>
                            <input type="number" id="unit_price" name="unit_price" step="0.01" value="<?php echo htmlspecialchars($purchase['unit_price']); ?>" required>
                        </div>
                        <div class="content">
                            <label for="total_price">Total Price: </label>
                            <input type="number" id="total_price" name="total_price" step="0.01" value="<?php echo htmlspecialchars($purchase['total_price']); ?>" readonly>
                        </div>
                        <div class="content">
                            <label for="purchase_date">Purchase Date: </label>
                            <input type="date" name="purchase_date" value="<?php echo htmlspecialchars($purchase['purchase_date']); ?>" required><br>
                        </div>
                        <div class="class">
                            <button type="submit">Update Purchase</button>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
