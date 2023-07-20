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

// Handle the signup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = $_POST['role']; // Get the selected role (super admin or regular admin)

    // Validate form data
    $errors = array();

    // Check if username is empty
    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    // Check if password is empty
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // Check if password and confirm password match
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check if the role is valid (either 'super admin' or 'regular admin')
    if ($role !== 'super admin' && $role !== 'regular admin') {
        $errors[] = "Invalid role selected.";
    }

    // Check if there are no validation errors
    if (empty($errors)) {
        // Hash the password before saving it to the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the admin data into the admins table
        $query = "INSERT INTO admins (username, password, role, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $username, $hashedPassword, $role);

        if ($stmt->execute()) {
            // Redirect the admin to the login page after successful registration
            header('Location: admin_login.php');
            exit();
        } else {
            $signupError = 'Error occurred while registering. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management System - Admin Signup</title>
    <!-- Include necessary CSS and JavaScript files -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>
    <style>
        .container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Signup</h1>
        <?php if (isset($signupError)): ?>
            <div class="alert alert-danger"><?php echo $signupError; ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
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
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role">
                    <option value="super admin">Super Admin</option>
                    <option value="regular admin">Regular Admin</option>
                </select>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Signup</button>
            </div>
            <div class="form-group text-center">
                <a href="admin_login.php" class="btn btn-link">Already have an account? Login</a>
            </div>
        </form>
    </div>
    <footer>
        <?php include("../footer.php"); ?>
    </footer>
</body>
</html>
