<?php
session_start();

// If logout is confirmed via GET parameter
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    session_destroy();
    header("Location: ../login/login.php");
    exit();
}
?>

<script>
    let confirmLogout = confirm("Are you sure you want to log out?");
    if (confirmLogout) {
        // Redirect to the same file with a confirmation parameter
        window.location.href = "logout.php?confirm=yes";
    } else {
        // Redirect back to dashboard if user cancels
        window.location.href = "dashboard.php";
    }
</script>
