<?php
session_start();
require '../connection.php';

if (isset($_POST['btnCreateUser'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_no = $_POST['phone_no'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $type = 'user'; // Default type
    $status = 1; // Default active status

    // Insert new user
    $sql = "INSERT INTO users (first_name, last_name, email, phone_no, password, type, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $first_name, $last_name,  $email, $phone_no, $password, $type, $status);

    if ($stmt->execute()) {
        echo "<script>alert('User created successfully');</script>";
        header("Location: view_users.php"); // Redirect to view users
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../users/add.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Create User</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Create User</h1>
        </header>
        <main>
            <section>
                <form action="add_users.php" method="POST">
                    <div class="container-box">
                        <div class="content">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name"  required>
                        </div>
                        <div class="content">
                        <label for="Last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" >
                        </div>
                        <div class="content">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="content">
                            <label for="phone_no">Phone no.</label>
                            <input type="text" id="phone_no" name="phone_no"  required>
                        </div>
                        <div class="content">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password"  required>
                        </div>
                        <div class="class">

                            <button type="submit" name="btnCreateUser">Add User</button>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>

</html>