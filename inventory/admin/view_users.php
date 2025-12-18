<?php
session_start();
require '../connection.php';

// Fetch all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../users/view.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>
        /* Style for email links in the user table */
        table tbody tr td a {
            color: #007bff;
            /* Blue color for visibility */
            text-decoration: none;
            /* Removes underline */
            font-weight: bold;
            /* Makes it stand out */
            transition: color 0.3s ease-in-out;
        }

        table tbody tr td a:hover {
            color: #ff5733;
            /* Changes to a different color on hover */
            text-decoration: underline;
            /* Adds underline for clarity */
        }
    </style>
    <title>View Users</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <header>
            <h1>User List</h1>
        </header>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['last_name']; ?></td>
                        <td>
                            <a href="user_activities.php?user_id=<?php echo $row['id']; ?>">
                                <?php echo $row['email']; ?>
                            </a>
                        </td>
                        <td><?php echo $row['phone_no']; ?></td>
                        <td>
                            <div class="action-btn-container">
                                <a href="edit_users.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">Edit</a>
                                <a href="delete_users.php?id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>