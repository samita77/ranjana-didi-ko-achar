<?php
require '../connection.php';
session_start(); // Start session for authentication

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Unauthorized access! Please log in.'); window.location.href = '../login/login.php';</script>";
    exit();
}

// Check if vendor ID is provided and is numeric
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_vendor.php?msg=Invalid vendor ID');
    exit();
}

$id = (int)$_GET['id'];

// Check if the vendor exists before deleting
$query = "SELECT id FROM vendor WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: view_vendor.php?msg=Vendor not found');
    exit();
}

// Delete the vendor
$delete_query = "DELETE FROM vendor WHERE id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: view_vendor.php?msg=Vendor deleted successfully');
} else {
    header('Location: view_vendor.php?msg=Error deleting vendor: ' . $conn->error);
}
exit;
?>
