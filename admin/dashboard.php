<?php
session_start();
require '../connection.php';

// Ensure only admin can access
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../login/login.php");
    exit();
}

// Function to get user counts securely
function getUserCount($conn, $condition) {
    $sql = "SELECT COUNT(*) AS count FROM users WHERE type != 'admin' AND $condition";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? intval($result->fetch_assoc()['count']) : 0;
}

// Fetching user counts
$total_users = getUserCount($conn, "1=1");  // All users excluding admin
$active_users = getUserCount($conn, "status = 1");
$deactivated_users = getUserCount($conn, "status = 0");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="content">
        <header class="header">
            <h1>Admin Dashboard</h1>
        </header>
        <main>
            <section>
                <h2>Overview</h2>
                <div class="overview-box">
                    <div class="overview-item">
                        <p><strong>Total Users:</strong><span id="total_users"><?= $total_users ?></span></p>
                    </div>
                    <div class="overview-item">
                        <p><strong>Active Users:</strong><span id="active_users"><?= $active_users ?></span></p>
                    </div>
                    <div class="overview-item">
                        <p><strong>Deactivated Users:</strong><span id="deactivated_users"><?= $deactivated_users ?></span></p>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
