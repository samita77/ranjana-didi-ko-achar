<?php
require '../connection.php';
session_start(); // Start session for authentication

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Unauthorized access! Please log in.'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Check if sale ID is provided
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_sales.php?msg=Invalid sale ID');
    exit();
}

$id = (int)$_GET['id'];

// Check if the sale exists and belongs to the logged-in user
$query = "SELECT id FROM sales WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: view_sales.php?msg=Sale not found or unauthorized access');
    exit();
}

// Delete the sale
$delete_query = "DELETE FROM sales WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("ii", $id, $user_id);

if ($stmt->execute()) {
    header('Location: view_sales.php?msg=Sale deleted successfully');
} else {
    header('Location: view_sales.php?msg=Error deleting sale: ' . $conn->error);
}
exit;
?>