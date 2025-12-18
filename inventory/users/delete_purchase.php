<?php
require '../connection.php';
session_start(); // Start session for user authentication

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Unauthorized access! Please log in.'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Check if a purchase_code is provided
if (!isset($_GET['purchase_code']) || empty($_GET['purchase_code'])) {
    header('Location: view_purchase.php?msg=Invalid purchase request');
    exit();
}

$purchase_code = $_GET['purchase_code'];

// Ensure the purchase exists and belongs to the logged-in user
$query = "SELECT id FROM purchases WHERE purchase_code = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $purchase_code, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: view_purchase.php?msg=Purchase not found or unauthorized');
    exit();
}

// Delete the purchase
$delete_query = "DELETE FROM purchases WHERE purchase_code = ? AND user_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("si", $purchase_code, $user_id);

if ($stmt->execute()) {
    header('Location: view_purchase.php?msg=Purchase deleted successfully');
} else {
    header('Location: view_purchase.php?msg=Error deleting purchase');
}
exit;
?>
