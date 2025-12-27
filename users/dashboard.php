<?php
session_start(); // Start the session
require 'function.php'; // Include the function file
require '../connection.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['uid'];
$sql = "SELECT first_name, last_name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle AJAX request inside the same file
if (isset($_GET['ajax']) && $_GET['ajax'] == "dashboard_data") {
    $response = [
        "total_products" => get_total_products($user_id),
        "sales_today" => get_sales('today'),
        "sales_last_7_days" => get_sales('last_7_days'),
        "sales_this_month" => get_sales('this_month'),
        "total_stock" => get_total_stock($user_id),
        "out_of_stock" => get_out_of_stock_count($user_id),
        "purchases_today" => get_purchases('today'),
        "purchases_last_7_days" => get_purchases('last_7_days'),
        "purchases_this_month" => get_purchases('this_month'),
    ];
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <header class="header">
            <h1>Welcome, <?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?></h1>
        </header>
        <main>
            <section>
                <h2>Overview</h2>
                <div class="overview-box">
                    <div class="overview-item">
                        <p><strong>Total Products:</strong> <span id="total_products"><?php echo get_total_products($user_id); ?></span></p>
                    </div>
                    <div class="overview-item">
                        <p><strong>Sales Today:</strong> <span>Rs</span> <span id="sales_today"><?php echo get_sales('today'); ?></span></p>
                    </div>
                    <div class="overview-item">
                        <p><strong>Sales Last 7 Days:</strong> <span>Rs</span> <span id="sales_last_7_days"><?php echo get_sales('last_7_days'); ?></span></p>
                    </div>
                    <div class="overview-item">
                        <p><strong>Sales This Month:</strong> <span>Rs</span> <span id="sales_this_month"><?php echo get_sales('this_month'); ?></span></p>
                    </div>
                    <div class="overview-item">
                        <p><strong>Total Stock:</strong> <span id="total_stock"><?php echo get_total_stock($user_id); ?></span></p>
                    </div>
                    <div class="overview-item">
                        <p><strong>Out of Stock Products:</strong> <span id="out_of_stock"><?php echo get_out_of_stock_count($user_id); ?></span></p>
                    </div>
                    <div class="overview-item">
                        <p><strong>Purchases Today:</strong> <span>Rs</span> <span id="purchases_today"><?php echo get_purchases('today'); ?></span></p>
                    </div>
                    <div class="overview-item">
                        <p><strong>Purchases Last 7 Days:</strong> <span>Rs</span> <span id="purchases_last_7_days"><?php echo get_purchases('last_7_days'); ?></span></p>
                    </div>
                    <div class="overview-item">
                        <p><strong>Purchases This Month:</strong> <span>Rs</span> <span id="purchases_this_month"><?php echo get_purchases('this_month'); ?></span></p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function updateDashboard() {
            $.ajax({
                url: "dashboard.php", // Request the same file
                type: "GET",
                data: {
                    ajax: "dashboard_data"
                }, // Identify AJAX request
                dataType: "json",
                success: function(data) {
                    $("#total_products").text(data.total_products);
                    $("#sales_today").text(data.sales_today);
                    $("#sales_last_7_days").text(data.sales_last_7_days);
                    $("#sales_this_month").text(data.sales_this_month);
                    $("#total_stock").text(data.total_stock);
                    $("#out_of_stock").text(data.out_of_stock);
                    $("#purchases_today").text(data.purchases_today);
                    $("#purchases_last_7_days").text(data.purchases_last_7_days);
                    $("#purchases_this_month").text(data.purchases_this_month);
                }
            });
        }

        // Update dashboard every 10 seconds
        setInterval(updateDashboard, 10000);
    </script>
</body>

</html>
