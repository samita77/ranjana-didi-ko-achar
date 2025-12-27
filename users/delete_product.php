<?php
require '../connection.php';
session_start(); // Start session for user authentication

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Unauthorized access! Please log in.'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get logged-in user ID

// Validate product code
if (!isset($_GET['product_code']) || empty($_GET['product_code'])) {
    header('Location: view_product.php?msg=Invalid product code');
    exit();
}

$product_code = $_GET['product_code'];

// Fetch the product details (Ensure user owns the product)
$query = "SELECT image_path FROM products WHERE product_code = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $product_code, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header('Location: view_product.php?msg=Unauthorized access or product not found');
    exit();
}

// Delete associated product image if it exists
if (!empty($product['image_path']) && file_exists($product['image_path'])) {
    unlink($product['image_path']); // Delete the image file
}

// Delete product from database
$delete_query = "DELETE FROM products WHERE product_code = ? AND user_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("si", $product_code, $user_id);

if ($stmt->execute()) {
    header('Location: view_product.php?msg=Product deleted successfully');
} else {
    header('Location: view_product.php?msg=Error deleting product: ' . $stmt->error);
}
exit;
?>