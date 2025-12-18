<?php
session_start();
require '../connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Please log in first!'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Fetch only purchases related to the logged-in user, including purchase_code
$query = "SELECT p.id, p.purchase_code, v.vendor_name, p.quantity, p.unit_price, p.total_price, p.purchase_date, pr.product_name 
          FROM purchases p 
         JOIN products pr ON p.product_id = pr.id
         JOIN vendor v ON p.vendor_id = v.id
          WHERE p.user_id = ?";
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
    <title>View Purchases</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Purchase List</h1>
        </header>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Purchase Code</th>
                        <th>Product Name</th>
                        <th>Vendor Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Purchase Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['purchase_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['vendor_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['unit_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['purchase_date']); ?></td>
                            <td>
                                <div class="action-btn-container">
                                    <a href='edit_purchase.php?purchase_code=<?php echo urlencode($row['purchase_code']); ?>' class="action-btn edit-btn">Edit</a>
                                    <a href="delete_purchase.php?purchase_code=<?php echo urlencode($row['purchase_code']); ?>"
                                        class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this purchase item?');">
                                        Delete
                                    </a>
                                </div>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No purchases found.</p>
        <?php endif; ?>
    </div>
</body>

</html>