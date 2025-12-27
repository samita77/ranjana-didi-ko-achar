<?php
session_start();
require '../connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['btnLogin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE email=? AND status=TRUE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Store user details in session
            $_SESSION['uid'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['usertype'] = $user['type'];

            // Redirect based on user type
            if ($user['type'] == 'admin') {
                header("Location: ../admin/dashboard.php");
                exit();
            } else {
                header("Location: ../users/dashboard.php");
                exit();
            }
        } else {
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        echo "<script>alert('Invalid email or inactive account');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_for_login.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Login</title>
    <style>
        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input {
            width: 100%;
            padding-right: 40px; /* Space for the eye icon */
        }

        .password-container i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="square"></div>
            <h1>Login</h1>
            <p class="description">
                See your growth and get support!
            </p>

            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group password-container">
                    <label for="password">Password*</label>
                    <input type="password" name="password" id="password" placeholder="Enter password" required>
                    <i class="fa fa-eye toggle-password" data-target="password"></i>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" name="btnLogin" value="Login">
                </div>
            </form>
        </div>
        <div class="right-section">
            <img src="undraw_Fingerprint_login_re_t71l.png" alt="Illustration">
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const togglePasswordElements = document.querySelectorAll('.toggle-password');
            
            togglePasswordElements.forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const passwordField = document.getElementById(targetId);
                    
                    // Toggle password visibility
                    if (passwordField.type === "password") {
                        passwordField.type = "text";
                        this.classList.add("fa-eye-slash");
                        this.classList.remove("fa-eye");
                    } else {
                        passwordField.type = "password";
                        this.classList.add("fa-eye");
                        this.classList.remove("fa-eye-slash");
                    }
                });
            });
        });
    </script>
</body>
</html>
