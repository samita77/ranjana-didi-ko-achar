<?php
session_start();
require '../connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Please log in first!'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Fetch only sales related to the logged-in user
$query = "SELECT s.id, s.sales_code, s.quantity, s.unit_price, s.total_price, s.sale_date, p.product_name 
          FROM sales s 
          JOIN products p ON s.product_id = p.id 
          WHERE s.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="view.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>View Sales</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
        <h1>Sales List</h1>
        </header>
        <table>
            <thead>
                <tr>
                    <th>Sales Code</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Sale Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['sales_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['unit_price']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                        <td><?php echo htmlspecialchars($row['sale_date']); ?></td>

                        <td>
                            <div class="action-btn-container">
                            <a href="edit_sales.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">Edit</a>
                            <a href="delete_sales.php?id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>