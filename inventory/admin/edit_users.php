<?php
session_start();
require '../connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user details
    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

if (isset($_POST['btnUpdateUser'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_no = $_POST['phone_no'];

    // Update user
    $sql = "UPDATE users SET first_name=?, last_name=?,  email=?, phone_no=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $first_name, $last_name,  $email, $phone_no, $id);

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully');</script>";
        header("Location: view_users.php");
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
    <link rel="stylesheet" href="../users/edit.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Edit User</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>Edit User</h1>
        </header>

        <form action="edit_users.php" method="POST">

            <div class="container-box">
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                <div class="content">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" placeholder="First Name" required>
                </div>

                <div class="content">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" placeholder="Last Name" required>
                </div>
                <div class="content">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email" required>
                </div>
                <div class="content">
                    <label for="phone_no">Phone No.</label>
                    <input type="text" name="phone_no" value="<?php echo $user['phone_no']; ?>" placeholder="Phone Number" required>
                </div>
                <div class="class">
                    <button type="submit" name="btnUpdateUser">Update User</button>
                </div>
            </div>
        </form>

    </div>
</body>

</html>