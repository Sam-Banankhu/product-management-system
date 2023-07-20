<?php
// Include the necessary files for database connection and session management
include("admin_header.php");
require_once '../db_connection.php';
require_once '../session.php';

// Check if the admin is already logged in, redirect to the admin dashboard if true
if (isAdminLoggedIn()) {
    header('Location: admin.php');
    exit();
}

// Handle the login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        // Store the admin's login session information
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['username'];

        header('Location: index.php');
        exit();
    } else {
        $loginError = 'Invalid username or password'; // Display an error message on the login form
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management System - Admin Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>
    <style>
        .container {
            max-width: 50%;
            margin: 0 auto;
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-5">Admin Login</h1>
        <?php if (isset($loginError)): ?>
            <div class="alert alert-danger"><?php echo $loginError; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
        <div class="form-group text-center">
            <a href="admin_signup.php" class="btn btn-link">Don't have an account? Signup</a>
        </div>
    </div>
    <footer>
        <?php include("../footer.php"); ?>
    </footer>
</body>
</html>
