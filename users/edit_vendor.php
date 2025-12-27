<?php
require '../connection.php';
session_start(); // Start session to get user ID

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Unauthorized access! Please log in.'); window.location.href = '../login/login.php';</script>";
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Validate vendor ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Invalid vendor ID.'); window.location.href = 'view_vendor.php';</script>";
    exit();
}

$vendor_id = (int)$_GET['id'];

// Fetch the specific vendor (ensuring it's owned by the logged-in user)
$query = "SELECT * FROM vendor WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $vendor_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$vendor = $result->fetch_assoc();

if (!$vendor) {
    echo "<script>alert('Vendor not found or you do not have permission to edit.'); window.location.href = 'view_vendor.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor_name = trim($_POST['vendor_name']);
    $contact_person = trim($_POST['contact_person']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);

    // Validate inputs
    if (empty($vendor_name) || empty($contact_person) || empty($phone) || empty($email) || empty($address)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }

    // Ensure phone contains only digits
    if (!preg_match('/^\d+$/', $phone)) {
        echo "<script>alert('Invalid phone number. Only digits are allowed.'); window.history.back();</script>";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.history.back();</script>";
        exit();
    }

    // Update vendor details (without modifying user_id)
    $update_query = "UPDATE vendor SET vendor_name = ?, contact_person = ?, phone = ?, email = ?, address = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $vendor_name, $contact_person, $phone, $email, $address, $vendor_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Vendor updated successfully!'); window.location.href = 'view_vendor.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating vendor: " . $conn->error . "'); window.history.back();</script>";
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
    <title>Edit Vendor</title>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="container">
    <header>
    <h1>Edit Vendor</h1>
    </header>
    <form action="edit_vendor.php?id=<?php echo $vendor_id; ?>" method="POST">
        <div class="container-box">
            <div class="content">
            <label for="vendor_name">Vendor Name:</label>
            <input type="text" id="vendor_name" name="vendor_name" value="<?php echo htmlspecialchars($vendor['vendor_name']); ?>" required>
            </div>
        
            <div class="content">
            <label for="contact_person">Contact Person:</label>
            <input type="text" id="contact_person" name="contact_person" value="<?php echo htmlspecialchars($vendor['contact_person']); ?>" required>
            </div>
        
            <div class="content">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($vendor['phone']); ?>" required>
            </div>
        
            <div class="content">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($vendor['email']); ?>" required>
            </div>
        
            <div class="content">
            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?php echo htmlspecialchars($vendor['address']); ?></textarea>
            </div>
        
            <div class="class">
            <button type="submit">Update Vendor</button>
            </div>
    </form>
        </div>
        
    </div>
</body>
</html>
