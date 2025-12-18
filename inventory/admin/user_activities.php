<?php
session_start();
require '../connection.php';

if (!isset($_GET['user_id'])) {
    echo "<script>alert('User ID not provided');</script>";
    header("Location: view_users.php");
    exit();
}

$user_id = $_GET['user_id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch user activities
$products = $conn->query("SELECT * FROM products WHERE user_id=$user_id");
$purchases = $conn->query("SELECT * FROM purchases WHERE user_id=$user_id");
$sales = $conn->query("SELECT * FROM sales WHERE user_id=$user_id");
$vendor = $conn->query("SELECT * FROM vendor WHERE user_id=$user_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../users/view.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>User Activities</title>
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
            <h1>Activities of <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h1>
        </header>
        <main>
            <section>
                <!-- Products -->
                <h2>Products</h2>
                <div class="overview-box">
                    <table>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Supplier</th>
                                <th>Quantity in Stock</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $products->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['product_name']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo $row['supplier']; ?></td>
                                    <td><?php echo $row['quantity_in_stock']; ?></td>
                                    <td><?php echo $row['unit_price']; ?></td>
                                    <td><?php echo $row['total_price']; ?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>


        <main>
            <section>
                <!-- Purchases -->
                <h2>Purchases</h2>
                <div class="overview-box">
                    <table>
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Vendor Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Purchase Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $purchases->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['product_id']; ?></td>
                                    <td><?php echo $row['vendor_name']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td><?php echo $row['unit_price']; ?></td>
                                    <td><?php echo $row['total_price']; ?></td>
                                    <td><?php echo $row['purchase_date']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <main>
            <section>


                <!-- Sales -->
                <h2>Sales</h2>
                <div class="overview_box">
                    <table>
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Sale Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $sales->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['product_id']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td><?php echo $row['unit_price']; ?></td>
                                    <td><?php echo $row['total_price']; ?></td>
                                    <td><?php echo $row['sale_date']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <main>
            <section>
                <!-- Vendors -->
                <h2>Vendors</h2>
                <div class="overview_box">
                    <table>
                        <thead>
                            <tr>
                                <th>Vendor Name</th>
                                <th>Contact Person</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $vendor->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['vendor_name']; ?></td>
                                    <td><?php echo $row['contact_person']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>