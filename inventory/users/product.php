<?php
require '../connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <div class="sidebar">
        <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
            
            <li><a href="sales.php"> Sales</a></li>
            <li><a href="purchase.php"> Purchases</a></li>
            <li>
                <a href="product.php"> Products</a>
                <ul>
                    <li><a href="view_product.php">View Product</a></li>
                    <li><a href="add_product.php">Add Product</a></li>
                </ul>
            </li>
            <li><a href="stock.php"> Stock</a></li>
            <li><a href="vendor.php"> Vendor</a></li>
            <li><a href="report.php"> Report</a></li>
        </ul>
    </div>
    <div class="content">
        <header>
            <h1>Product</h1>
        </header>
        <main>
            <p>This is the product page where you can manage product information.</p>
        </main>
    </div>
</body>
</html>
