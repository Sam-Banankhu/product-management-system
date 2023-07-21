<?php
// Include the necessary files for database connection and session management
require_once '../db_connection.php';
require_once 'session.php';

// Check if the admin is already logged in, redirect to dashboard if logged in
if (isAdminLoggedIn()) {
    header('Location: index.php');
    exit();
}

// Initialize variables to store form data and errors
$username = $password = $confirm_password = $role = '';
$errors = array();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the form inputs
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validate the form data
    if (empty($username)) {
        $errors[] = 'Username is required.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    // If there are no errors, proceed with signup
    if (empty($errors)) {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and execute the SQL query to insert admin data into the database
        $query = "INSERT INTO admins (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $username, $hashed_password, $role);
        if ($stmt->execute()) {
            // Signup successful, redirect to login page
            header('Location: admin_login.php');
            exit();
        } else {
            $signupError = "Error creating admin account.";
        }
        $stmt->close();
    }
}
?>
