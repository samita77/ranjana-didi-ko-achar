<?php
session_start();
require '../connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Please log in first!'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Fetch only products added by the logged-in user
$query = "SELECT id, product_code, product_name, description, quantity_in_stock, unit_price, total_price, image_path, created_at 
          FROM products 
          WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="view.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>View Products</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Product List</h1>
        </header>
    <table>
        <thead>
            <tr>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Stock Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity_in_stock']); ?></td>
                        <td><?php echo htmlspecialchars($row['unit_price']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                        <td>
                            <?php if (!empty($row['image_path'])) : ?>
                                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Product Image">
                            <?php else : ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo !empty($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : 'N/A'; ?>
                        </td>
                        <td>
                            <div class="action-btn-container">
                                <a href="edit_product.php?product_code=<?php echo urlencode($row['product_code']); ?>" class="action-btn edit-btn">Edit</a>
                                <a href="delete_product.php?product_code=<?php echo urlencode($row['product_code']); ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</body>

</html>