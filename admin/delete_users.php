<?php
session_start();
require '../connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete user
    $sql = "DELETE FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
    $stmt->close();
}

header("Location: view_users.php");
exit();
?>