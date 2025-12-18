<?php
require '../connection.php';
session_start(); // Start session

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['uid']; // Get logged-in user's ID

// Fetch only products belonging to the logged-in user
function get_product_stock($conn, $user_id)
{
    $sql = "SELECT product_code, product_name, quantity_in_stock FROM products WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return ($result) ? $result->fetch_all(MYSQLI_ASSOC) : []; // ✅ Fixed: Return the products array
}

$products = get_product_stock($conn, $user_id); // ✅ Now, $products will have data
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Stock</title>
    <link rel="stylesheet" href="view.css">
    <style>
        section {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            color: #222831;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Product Stock</h1>
        </header>
        <main>
            <section>
                <h2>Stock Levels</h2>
                <div class="overview-box">
                    <table>
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <?php
                                    $stock = (int) $product['quantity_in_stock'];
                                    $stock_class = ($stock == 0) ? 'out-of-stock' : (($stock <= 5) ? 'low-stock' : 'in-stock');
                                    $stock_text = ($stock == 0) ? ' (Out of Stock)' : (($stock <= 5) ? ' (Low Stock)' : '');
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['product_code']); ?></td>
                                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                        <td class="<?= $stock_class; ?>">
                                            <?php echo $stock . $stock_text; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No products available for your account.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>