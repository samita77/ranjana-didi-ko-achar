<?php
require 'function.php';

if (!isset($_GET['report_type'])) {
    die("Invalid request.");
}

$report_type = $_GET['report_type'];
$report_title = strtoupper($report_type) . " REPORT";

$data = [];
$columns = [];

if ($report_type == "purchases") {
    $columns = ["Purchase Code", "Product ID", "Product Code", "Vendor Name", "Quantity", "Unit Price", "Total Price", "Purchase Date"];
    $data = get_purchase_report();
} elseif ($report_type == "sales") {
    $columns = ["Sale Code", "Product ID", "Product Code", "Quantity", "Unit Price", "Total Price", "Sales Date"];
    $data = get_sales_report();
} elseif ($report_type == "products") {
    $columns = ["Product Code", "Product Name", "Description", "Image", "Quantity In Stock", "Unit Price", "Total Price", "Created At"];
    $data = get_product_report();
} elseif ($report_type == "vendor") {
    $columns = ["Vendor Number", "Vendor Name", "Contact Person", "Phone no", "Email", "Address"];
    $data = get_vendor_report();
} else {
    die("Invalid report type.");
}

// Generate HTML content
$html = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>$report_title</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>$report_title</h2>
    <table>
        <tr>";

// Add table headers
foreach ($columns as $col) {
    $html .= "<th>$col</th>";
}
$html .= "</tr>";

// Add table rows
foreach ($data as $row) {
    $html .= "<tr>";
    foreach ($row as $cell) {
        $html .= "<td>$cell</td>";
    }
    $html .= "</tr>";
}

$html .= "</table></body></html>";

// Force download as an .html file (User can print as PDF manually)
header("Content-Type: text/html");
header("Content-Disposition: attachment; filename={$report_type}_report.html");

echo $html;
exit;
?>