<?php
require '../connection.php';
session_start(); // Start session to get user ID

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Unauthorized access! Please log in.'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Check if an ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('No sale ID specified.'); window.location.href = 'view_sales.php';</script>";
    exit();
}

$id = (int)$_GET['id'];

// Fetch the specific sale for the logged-in user
$query = "SELECT * FROM sales WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$sale = $result->fetch_assoc();

if (!$sale) {
    echo "<script>alert('Sale not found or unauthorized access.'); window.location.href = 'view_sales.php';</script>";
    exit();
}

// Fetch all products for the logged-in user for dropdown
$product_query = "SELECT id, product_name, unit_price FROM products WHERE user_id = ?";
$product_stmt = $conn->prepare($product_query);
$product_stmt->bind_param("i", $user_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $unit_price = (float)$_POST['unit_price'];
    $total_price = $quantity * $unit_price;

    // Input validation
    if ($quantity <= 0 || $unit_price <= 0) {
        echo "<script>alert('Quantity and unit price must be greater than zero.'); window.history.back();</script>";
        exit();
    }

    // Update query with user_id
    $update_query = "UPDATE sales 
                     SET product_id = ?, 
                         quantity = ?, 
                         unit_price = ?, 
                         total_price = ?
                     WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("iidiii", $product_id, $quantity, $unit_price, $total_price, $id, $user_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Sale updated successfully!');
                window.location.href = 'view_sales.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Error updating sale: " . $conn->error . "'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Edit Sale</title>
    <script>
        function calculateTotal() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            document.getElementById('total_price').value = (quantity * unitPrice).toFixed(2);
        }

        function updateUnitPrice() {
            const productSelect = document.getElementById('product_id');
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const unitPrice = selectedOption.getAttribute('data-price');
            document.getElementById('unit_price').value = unitPrice;
            calculateTotal();
        }

        window.onload = function() {
            document.getElementById('quantity').addEventListener('input', calculateTotal);
            document.getElementById('unit_price').addEventListener('input', calculateTotal);
            document.getElementById('product_id').addEventListener('change', updateUnitPrice);
            updateUnitPrice(); // Initialize unit price and total price on page load
        };
    </script>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Edit Sale</h1>
        </header>
    <main>
        <section>
            <form action="edit_sales.php?id=<?php echo $id; ?>" method="POST">
                <div class="container-box">
                    <div class="content">
                        <label for="product_id">Product:</label>
                        <select id="product_id" name="product_id" required>
                            <?php
                            while ($row = $product_result->fetch_assoc()) {
                                $selected = ($row['id'] == $sale['product_id']) ? "selected" : "";
                                echo "<option value='{$row['id']}' data-price='{$row['unit_price']}' $selected>
                      {$row['product_name']} (Price: {$row['unit_price']})
                      </option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="content">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($sale['quantity']); ?>" required>
                    </div>
                    <div class="content">
                        <label for="unit_price">Unit Price:</label>
                        <input type="number" id="unit_price" name="unit_price" step="0.01" value="<?php echo htmlspecialchars($sale['unit_price']); ?>" required readonly>
                    </div>
                    <div class="content">
                        <label for="total_price">Total Price:</label>
                        <input type="number" id="total_price" name="total_price" step="0.01" value="<?php echo htmlspecialchars($sale['total_price']); ?>" readonly>
                    </div>
                    <div class="class">
                        <button type="submit">Update Sale</button>
                    </div>
                </div>
            </form>
        </section>
    </main>
</body>

</html>