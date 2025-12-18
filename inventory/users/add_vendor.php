<?php
session_start();
require '../connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['uid']; // Get the logged-in user ID

// Fetch vendors that exist in purchases but are not in vendor table
$unregistered_vendors = [];
$query = "SELECT DISTINCT v.vendor_name 
          FROM purchases p
          JOIN vendor v ON p.vendor_id = v.id
          WHERE p.vendor_id IS NOT NULL AND p.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $unregistered_vendors[] = $row['vendor_name'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor_name = trim($_POST['vendor_name']);
    $contact_person = trim($_POST['contact_person']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);

    // Validate required fields
    if (empty($vendor_name) || empty($contact_person) || empty($phone) || empty($email) || empty($address)) {
        echo "<script>alert('All fields are required!');</script>";
    } elseif (!preg_match('/^\d{7,15}$/', $phone)) {
        echo "<script>alert('Invalid phone number. Only 7-15 digits allowed.');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } else {
        // Check if the vendor already exists
        $stmt = $conn->prepare("SELECT id FROM vendor WHERE user_id = ? AND LOWER(vendor_name) = LOWER(?)");
        $stmt->bind_param("is", $user_id, $vendor_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Vendor exists: Update the vendor's details
            $vendor = $result->fetch_assoc();
            $vendor_id = $vendor['id'];

            $update_stmt = $conn->prepare("UPDATE vendor SET contact_person = ?, phone = ?, email = ?, address = ? WHERE id = ?");
            $update_stmt->bind_param("ssssi", $contact_person, $phone, $email, $address, $vendor_id);

            if ($update_stmt->execute()) {
                echo "<script>alert('Vendor details updated successfully!'); window.location.href = 'view_vendor.php';</script>";
            } else {
                echo "<script>alert('Error updating vendor: " . $conn->error . "');</script>";
            }
        } else {
            // Vendor doesn't exist: Insert new vendor
            // Get next vendor number (similar to sales_code logic in add_sales.php)
            $stmt = $conn->prepare("SELECT IFNULL(MAX(vendor_number), 0) + 1 AS next_vendor FROM vendor WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $vendor_number = max(1, $row['next_vendor']); // Ensure it starts from 1

            // Insert vendor into the database
            $stmt = $conn->prepare("INSERT INTO vendor (user_id, vendor_name, contact_person, phone, email, address, vendor_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssi", $user_id, $vendor_name, $contact_person, $phone, $email, $address, $vendor_number);

            if ($stmt->execute()) {
                $vendor_id = $stmt->insert_id;

                // Update purchases table (if needed)
                // Since the purchases table does not have a vendor_name column, this step is no longer needed.
                // If you need to link purchases to the new vendor, ensure the purchases table uses vendor_id.

                echo "<script>alert('Vendor added successfully!'); window.location.href = 'view_vendor.php';</script>";
            } else {
                echo "<script>alert('Error adding vendor: " . $conn->error . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="add.css">
    <title>Add Vendor</title>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h2>Add New Vendor</h2>
        </header>
        <main>
            <section>
                <form action="add_vendor.php" method="POST">
                    <div class="container-box">
                        <!-- Dropdown for unregistered vendors -->
                        <div class="content">
                            <label>Select Vendor from Purchases:</label>
                            <select id="vendor_name_select" onchange="fillVendorName()">
                                <option value="">-- Select a vendor --</option>
                                <?php foreach ($unregistered_vendors as $vendor) { ?>
                                    <option value="<?= htmlspecialchars($vendor) ?>"><?= htmlspecialchars($vendor) ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="content">
                            <label>Vendor Name:</label>
                            <input type="text" id="vendor_name" name="vendor_name" required>
                        </div>
                        <div class="content">
                            <label>Contact Person:</label>
                            <input type="text" id="contact_person" name="contact_person" required>
                        </div>
                        <div class="content">
                            <label>Phone:</label>
                            <input type="text" id="phone" name="phone" required>
                        </div>
                        <div class="content">
                            <label>Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="content">
                            <label>Address:</label>
                            <textarea id="address" name="address" required></textarea>
                        </div>
                        <div class="class">
                            <button type="submit">Add Vendor</button>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>
        function fillVendorName() {
            document.getElementById('vendor_name').value = document.getElementById('vendor_name_select').value;
        }
    </script>
</body>
</html>
