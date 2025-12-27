<?php
session_start();
require '../connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Please log in first!'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Fetch only vendors added by the logged-in user, ordered by vendor number
$query = "SELECT vendor_number, contact_person, vendor_name, phone, email, address, id FROM vendor WHERE user_id = ? ORDER BY vendor_number ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check for query execution failure
if (!$result) {
    die("<script>alert('Error fetching vendors: " . $conn->error . "');</script>");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="view.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Vendors List</title>
</head>

<body>
<?php include 'sidebar.php'; ?>
<div class="container">
    <header>
        <h1>Vendor List</h1>
    </header>
   
    <table>
        <tr>
            <th>Vendor No.</th>
            <th>Contact Person</th>
            <th>Vendor Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['vendor_number']; ?></td>
                <td><?php echo htmlspecialchars($row['contact_person']); ?></td>
                <td><?php echo htmlspecialchars($row['vendor_name']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td>
                    <div class="action-btn-container">
                    <a href="edit_vendor.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">Edit</a> 
                    <a href="delete_vendor.php?id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </table>
    </div>
</body>

</html>