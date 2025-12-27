<?php
// Database connection parameters
$servername = "localhost:3307"; // Database server (localhost is common)
$username = "root";        // Your database username (default is 'root' for local environments)
$password = "";            // Your database password (leave empty for local development)
$dbname = "inventory";     // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>