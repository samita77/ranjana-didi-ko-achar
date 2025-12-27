<?php
require 'function.php';

if (!isset($_GET['report_type'])) {
    die("Invalid request.");
}

$report_type = $_GET['report_type'];
$data = []; // ✅ Initialize `$data` as an empty array to prevent undefined variable warnings

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename={$report_type}_report.csv");

$output = fopen("php://output", "w");

// Define columns and get data
if ($report_type == "purchases") {
    fputcsv($output, ["Purchase Code", "Product ID", "Product Code", "Vendor Name", "Quantity", "Unit Price", "Total Price", "Purchase Date"]);
    $data = get_purchase_report();
} elseif ($report_type == "sales") {
    fputcsv($output, ["Sales Code", "Product ID", "Product Code", "Quantity", "Unit Price", "Total Price", "Sales Date"]);
    $data = get_sales_report();
} elseif ($report_type == "products") {
    fputcsv($output, ["Product Code", "Product Name", "Description", "Image", "Quantity In Stock", "Unit Price", "Total Price", "Created At"]);
    $data = get_product_report();
} elseif ($report_type == "vendor") {
    fputcsv($output, ["Vendor Number", "Vendor Name", "Contact Person", "Phone no", "Email", "Address"]);
    $data = get_vendor_report();
} else {
    die("Invalid report type."); // Stop execution if an invalid report type is passed
}

// Output data
foreach ($data as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>