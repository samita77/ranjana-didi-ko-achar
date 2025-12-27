<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="report.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Generate Reports</h1>
        </header>
        <div class="report">
            <div class="report-box">
                <h2>Purchase Report</h2>
                <a href="generate_excel.php?report_type=purchases">Download as Excel</a>
                <a href="generate_pdf.php?report_type=purchases">Download as PDF</a>
            </div>

            <div class="report-box">
                <h2>Sales Report</h2>
                <a href="generate_excel.php?report_type=sales">Download as Excel</a>
                <a href="generate_pdf.php?report_type=sales">Download as PDF</a>
            </div>

            <div class="report-box">
                <h2>Product Report</h2>
                <a href="generate_excel.php?report_type=products">Download as Excel</a>
                <a href="generate_pdf.php?report_type=products">Download as PDF</a>
            </div>

            <div class="report-box">
                <h2>Vendor Report</h2>
                <a href="generate_excel.php?report_type=vendor">Download as Excel</a>
                <a href="generate_pdf.php?report_type=vendor">Download as PDF</a>
            </div>
        </div>

    </div>

</body>

</html>