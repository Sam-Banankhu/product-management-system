<!DOCTYPE html>
<html>
<head>
    <title>Manage Admins | Admin Panel</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
    <?php
    require_once 'admin_header.php';
    require_once '../db_connection.php';

    // Check if the user is logged in and has admin privileges
    if (!isAdminLoggedIn()) {
        header('Location: admin_login.php');
        exit();
    }

    // Function to fetch all admin users from the database
    function getAdminUsers()
    {
        global $conn;
        $query = "SELECT * FROM admins";
        $result = $conn->query($query);

        $adminUsers = array();
        while ($row = $result->fetch_assoc()) {
            $adminUsers[] = $row;
        }
        return $adminUsers;
    }

    // Function to add a new admin user
    function addAdminUser($username, $password)
    {
        global $conn;

        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO admins (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $username, $hashedPassword);
        return $stmt->execute();
    }

    // Function to delete an admin user by ID
    function deleteAdminUser($adminID)
    {
        global $conn;

        $query = "DELETE FROM admins WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $adminID);
        return $stmt->execute();
    }

    // Handle form submission for adding a new admin user
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (addAdminUser($username, $password)) {
            echo '<div class="alert alert-success" role="alert">Admin user added successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Failed to add admin user.</div>';
        }
    }

    // Handle deletion of admin user
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $adminID = intval($_GET['id']);

        if (deleteAdminUser($adminID)) {
            echo '<div class="alert alert-success" role="alert">Admin user deleted successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Failed to delete admin user.</div>';
        }
    }

    // Fetch all admin users
    $adminUsers = getAdminUsers();
    ?>

    <div class="container mt-5">
        <h2>Manage Admin Users</h2>
        <table class="table table-bordered table-hover mt-3">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adminUsers as $adminUser) : ?>
                    <tr>
                        <td><?php echo $adminUser['username']; ?></td>
                        <td>
                            <a href="?action=delete&id=<?php echo $adminUser['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this admin user?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Add New Admin User</h3>
        <form method="post" class="form-inline">
            <div class="form-group mr-2">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-group mr-2">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Add Admin User</button>
        </form>
    </div>
</body>
</html>
