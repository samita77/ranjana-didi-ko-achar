<?php
include 'connection.php';
// Email validation function
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Phone number validation function (only digits from 1 to 10 allowed)
function isValidPhoneNumber($phone_no)
{
    return preg_match('/^[1-9]{1,10}$/', $phone_no);
}

if (isset($_POST['btnReg'])) {
    if (isset($_POST['terms'])) {
        if (isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone_no'], $_POST['password'], $_POST['cPassword'])) {

            // Email validation
            $email = $_POST['email'];
            if (!isValidEmail($email)) {
                echo "<script>alert('Invalid email format!');</script>";
                exit();
            }

            // Phone number validation (only digits 1-10 allowed)
            $phone_no = $_POST['phone_no'];
            if (!isValidPhoneNumber($phone_no)) {
                echo "<script>alert('Invalid phone number! Only digits from 1 to 10 are allowed, without 0 or special characters.');</script>";
                exit();
            }

            if ($_POST['password'] !== $_POST['cPassword']) {
                echo "<script>alert('Passwords do not match');</script>";
                exit();
            }

            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $secret_code = $_POST['secret_code'] ?? '';

            // Default user type
            $type = 'user';
            $status = 1; // Active by default

            // **Admin validation: Only one admin allowed with a predefined email**
            $admin_email = "trackify123@gmail.com"; // Set your allowed admin email here

            if ($secret_code === "admin") {
                // Check if an admin already exists
                $check_admin_sql = "SELECT email FROM users WHERE type = 'admin' LIMIT 1";
                $admin_result = $conn->query($check_admin_sql);

                if ($admin_result->num_rows > 0) {
                    $existing_admin = $admin_result->fetch_assoc();

                    // Ensure only the predefined admin email can register
                    if ($email !== $admin_email) {
                        echo "<script>alert('Invalid admin registration! Only the predefined admin email can be used.');</script>";
                        exit();
                    }

                    // Prevent duplicate admin account
                    if ($existing_admin['email'] !== $email) {
                        echo "<script>alert('An admin account already exists! Only one admin is allowed.');</script>";
                        exit();
                    }
                } else {
                    // Ensure the provided email matches the predefined admin email
                    if ($email !== $admin_email) {
                        echo "<script>alert('Invalid admin registration!');</script>";
                        exit();
                    }
                }

                $type = 'admin'; // Set user type to admin
            }

            // Insert user data
            $sql = "INSERT INTO users (first_name, last_name, email, phone_no, password, type, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone_no, $password, $type, $status);

            if ($stmt->execute()) {
                $_SESSION['uid'] = $conn->insert_id;
                $_SESSION['email'] = $email;
                $_SESSION['usertype'] = $type;

                if ($type === 'admin') {
                    header("Location: admin/dashboard.php");
                    exit();
                } else {
                    header("Location: users/dashboard.php");
                    exit();
                }
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Please fill in all fields');</script>";
        }
    } else {
        echo "<script>alert('Please agree to the terms and conditions.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Hide the secret code field by default */
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-section">
            <!-- Add onclick event to the image -->
            <img src="undraw_pending_approval_xuu9.png" alt="Illustration" onclick="toggleSecretCode()">
        </div>
        <div class="right-section">
            <div class="square"></div>
            <h1>Register</h1>
            <p class="subheading">Manage all your inventory efficiently</p>
            <p class="description">
                Let's get you all set up so you can verify your personal account and begin setting up your work profile
            </p>
            <form action="registration.php" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first-name">First name</label>
                        <input type="text" name="first_name" id="first_name" value="<?php if (isset($_GET['uid'])) {
                                                                                        echo $user['first_name'];
                                                                                    } ?>" placeholder="Enter your first name" required>
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last name</label>
                        <input type="text" name="last_name" id="last_name" value="<?php if (isset($_GET['uid'])) {
                                                                                        echo $user['last_name'];
                                                                                    } ?>" placeholder="Enter your last name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?php if (isset($_GET['uid'])) {
                                                                                echo $user['email'];
                                                                            } ?>" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone no.</label>
                        <input type="tel" name="phone_no" id="phone" value="<?php if (isset($_GET['uid'])) {
                                                                                echo $user['phone_no'];
                                                                            } ?>" placeholder="Enter your phone number" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" value="<?php if (isset($_GET['uid'])) {
                                                                                        echo $user['password'];
                                                                                    } ?>" placeholder="Enter your password" required>
                        <i class="fa fa-eye toggle-password" data-target="password"></i>
                    </div>
                    <div class="form-group">
                        <label for="cPassword">Confirm Password</label>
                        <input type="password" name="cPassword" id="cPassword" value="<?php if (isset($_GET['uid'])) {
                                                                                            echo $user['password'];
                                                                                        } ?>" placeholder="Enter your confirm password" required>
                        <i class="fa fa-eye toggle-password" data-target="cPassword"></i>
                    </div>
                </div>

                <!-- Secret Code for Admin Registration (hidden by default) -->
                <div class="form-group hidden" id="secretCodeField">
                    <label for="secret_code">Secret Code (for admins only)</label>
                    <input type="text" name="secret_code" id="secret_code" placeholder="Enter secret code">
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" name="terms" id="terms" required>
                        <label for="terms">I agree to all <a href="#">terms</a>, <a href="#">privacy policies</a>, and <a href="#">fees</a>.</label>
                    </div>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn" name="btnReg" value="<?php if (isset($_GET['uid'])) {
                                                                                echo 'Update';
                                                                            } else {
                                                                                echo 'Sign Up';
                                                                            } ?>"></input>
                    <p class="login-link">Already have an account? <a href="login/login.php">Log in</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.addEventListener("DOMContentLoaded", function() {
            const togglePasswordElements = document.querySelectorAll('.toggle-password');

            togglePasswordElements.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordField = document.getElementById(targetId);

                    // Toggle password visibility
                    if (passwordField.type === "password") {
                        passwordField.type = "text";
                        this.classList.remove("fa-eye");
                        this.classList.add("fa-eye-slash");
                    } else {
                        passwordField.type = "password";
                        this.classList.remove("fa-eye-slash");
                        this.classList.add("fa-eye");
                    }
                });
            });
        });


        // Toggle secret code field visibility
        function toggleSecretCode() {
            const secretCodeField = document.getElementById('secretCodeField');
            secretCodeField.classList.toggle('hidden');

            // Save state in sessionStorage
            sessionStorage.setItem('secretVisible', !secretCodeField.classList.contains('hidden'));
        }

        // Restore visibility on page load
        document.addEventListener("DOMContentLoaded", function() {
            const secretCodeField = document.getElementById('secretCodeField');
            if (sessionStorage.getItem('secretVisible') === 'true') {
                secretCodeField.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>