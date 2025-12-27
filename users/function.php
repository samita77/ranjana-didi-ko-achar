<?php
// Check if a session is already active before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../connection.php';

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    die("Unauthorized access. Please log in.");
}

$user_id = $_SESSION['uid']; // Store logged-in user ID

// 1. Total Products (Filtered by User)
function get_total_products() {
    global $conn, $user_id;
    $sql = "SELECT COUNT(*) AS total FROM products WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}

// 2. Total Sales (Filtered by User)
function get_sales($type = 'today') {
    global $conn, $user_id;

    $today = date('Y-m-d'); // Get today's date in YYYY-MM-DD format

    if ($type === 'today') {
        $sql = "SELECT SUM(total_price) AS total_sales FROM sales WHERE user_id = ? AND DATE(sale_date) = ?";
    } elseif ($type === 'last_7_days') {
        $sql = "SELECT SUM(total_price) AS total_sales FROM sales WHERE user_id = ? AND sale_date >= ?";
        $today = date('Y-m-d', strtotime('-7 days')); // Get the date 7 days ago
    } elseif ($type === 'this_month') {
        $sql = "SELECT SUM(total_price) AS total_sales FROM sales WHERE user_id = ? AND MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())";
    } else {
        return 0;
    }

    $stmt = $conn->prepare($sql);
    if ($type === 'today' || $type === 'last_7_days') {
        $stmt->bind_param("is", $user_id, $today);
    } else {
        $stmt->bind_param("i", $user_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total_sales'] !== null ? $row['total_sales'] : 0;
    } else {
        return 0;
    }
}

function get_purchases($type = 'today') {
    global $conn, $user_id;

    $today = date('Y-m-d'); // Get today's date in YYYY-MM-DD format

    if ($type === 'today') {
        $sql = "SELECT SUM(total_price) AS total_purchases FROM purchases WHERE user_id = ? AND DATE(purchase_date) = ?";
    } elseif ($type === 'last_7_days') {
        $sql = "SELECT SUM(total_price) AS total_purchases FROM purchases WHERE user_id = ? AND purchase_date >= ?";
        $today = date('Y-m-d', strtotime('-7 days')); // Get the date 7 days ago
    } elseif ($type === 'this_month') {
        $sql = "SELECT SUM(total_price) AS total_purchases FROM purchases WHERE user_id = ? AND MONTH(purchase_date) = MONTH(CURDATE()) AND YEAR(purchase_date) = YEAR(CURDATE())";
    } else {
        return 0;
    }

    $stmt = $conn->prepare($sql);
    if ($type === 'today' || $type === 'last_7_days') {
        $stmt->bind_param("is", $user_id, $today);
    } else {
        $stmt->bind_param("i", $user_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total_purchases'] !== null ? $row['total_purchases'] : 0;
    } else {
        return 0;
    }
}

// 3. Total Stock (Filtered by User)
function get_total_stock() {
    global $conn, $user_id;
    $sql = "SELECT SUM(quantity_in_stock) AS total_stock FROM products WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total_stock'] ? $row['total_stock'] : 0;
    } else {
        return 0;
    }
}

// 4. Out of Stock Products (Filtered by User)
function get_out_of_stock_count() {
    global $conn, $user_id;
    $sql = "SELECT COUNT(*) AS out_of_stock FROM products WHERE user_id = ? AND quantity_in_stock = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['out_of_stock'];
    } else {
        return 0;
    }
}
// 5. Purchase Report (Filtered by User)
function get_purchase_report() {
    global $conn, $user_id;
    $sql = "SELECT p.purchase_code, p.product_id, pr.product_code, p.vendor_name, p.quantity, p.unit_price, p.total_price, DATE_FORMAT(p.purchase_date, '%Y-%m-%d') AS purchase_date 
            FROM purchases p
            JOIN products pr ON p.product_id = pr.id
            WHERE p.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// 6. Sales Report (Filtered by User)
function get_sales_report() {
    global $conn, $user_id;
    $sql = "SELECT s.sales_code, s.product_id, pr.product_code, s.quantity, s.unit_price, s.total_price, DATE_FORMAT(s.sale_date, '%Y-%m-%d') AS sale_date 
            FROM sales s
            JOIN products pr ON s.product_id = pr.id
            WHERE s.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// 7. Product Report (Filtered by User)
function get_product_report() {
    global $conn, $user_id;
    $sql = "SELECT product_code, product_name, description, supplier, image_path, quantity_in_stock, unit_price, total_price, DATE_FORMAT(created_at, '%Y-%m-%d') AS created_at 
            FROM products 
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// 8. Vendor Report (Filtered by User)
function get_vendor_report() {
    global $conn, $user_id;
    $sql = "SELECT vendor_number, vendor_name, contact_person, phone, email, address 
            FROM vendor 
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}
?>
