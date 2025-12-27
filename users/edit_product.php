<?php
session_start();
require '../connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Please log in first!'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get logged-in user ID

// Check if the product code is provided
if (!isset($_GET['product_code']) || empty($_GET['product_code'])) {
    echo "<script>alert('Product code is required!'); window.location.href = 'view_product.php';</script>";
    exit();
}

$product_code = $_GET['product_code'];

// Fetch product details (only if it belongs to the logged-in user)
$query = "SELECT * FROM products WHERE product_code = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $product_code, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<script>alert('Unauthorized access or product not found!'); window.location.href = 'view_product.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $quantity_in_stock = (int)$_POST['quantity_in_stock'];
    $unit_price = (float)$_POST['unit_price'];
    $total_price = $quantity_in_stock * $unit_price;
    $created_at = $_POST['created_at'];

    // Keep existing image path if no new image is uploaded
    $image_path = $product['image_path'];

    if (!empty($_FILES['product_image']['name'])) {
        $image_tmp = $_FILES['product_image']['tmp_name'];
        $image_name = time() . "_" . basename($_FILES['product_image']['name']); // Prevent overwriting
        $image_path = 'uploads/' . $image_name;

        if (!move_uploaded_file($image_tmp, $image_path)) {
            echo "<script>alert('Error uploading image.');</script>";
            exit();
        }
    }

    // Update the product details
    $query = "UPDATE products SET 
              product_name = ?, 
              description = ?, 
              quantity_in_stock = ?, 
              unit_price = ?, 
              total_price = ?, 
              image_path = ?, 
              created_at = ? 
              WHERE product_code = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiddsssi", $product_name, $description, $quantity_in_stock, $unit_price, $total_price, $image_path, $created_at, $product_code, $user_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Product updated successfully.');
                window.location.href = 'view_product.php';
              </script>";
    } else {
        echo "<script>alert('Error updating product: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Edit Product</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Edit Product</h1>
        </header>
        <main>
            <section>
                <form action="edit_product.php?product_code=<?php echo $product_code; ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="product_code" value="<?php echo $product_code; ?>">

                    <div class="container-box">
                        <div class="content">
                            <label for="product_name">Product Name:</label>
                            <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                        </div>
                        <div class="content">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>

                        <div class="content">
                            <label for="quantity_in_stock">Quantity in Stock:</label>
                            <input type="number" id="quantity_in_stock" name="quantity_in_stock" value="<?php echo htmlspecialchars($product['quantity_in_stock']); ?>" required>
                        </div>

                        <div class="content">
                            <label for="unit_price">Unit Price:</label>
                            <input type="number" id="unit_price" name="unit_price" step="0.01" value="<?php echo htmlspecialchars($product['unit_price']); ?>" required>
                        </div>

                        <div class="content">
                            <label for="total_price">Total Price:</label>
                            <input type="number" id="total_price" name="total_price" step="0.01" value="<?php echo htmlspecialchars($product['total_price']); ?>" readonly>
                        </div>

                        <div class="content">
                            <label for="product_image">Product Image:</label>
                            <input type="file" id="product_image" name="product_image" accept="image/*">
                            <br>
                            <?php if (!empty($product['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product Image">
                            <?php endif; ?>
                        </div>

                        <div class="content">
                            <label for="created_at">Created At:</label>
                            <input type="date" id="created_at" name="created_at" value="<?php echo htmlspecialchars(substr($product['created_at'], 0, 10)); ?>" required>
                        </div>

                        <div class="class">
                            <button type="submit">Update Product</button>
                        </div>

                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>
        function calculateTotal() {
            const quantity = parseFloat(document.getElementById('quantity_in_stock').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            document.getElementById('total_price').value = (quantity * unitPrice).toFixed(2);
        }

        window.onload = function() {
            calculateTotal();
            document.getElementById('quantity_in_stock').addEventListener('input', calculateTotal);
            document.getElementById('unit_price').addEventListener('input', calculateTotal);
        };
    </script>

</body>

</html>