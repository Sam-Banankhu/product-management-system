<?php
session_start();

// Function to check if the admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Function to get the current admin's ID
function getCurrentAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

// Function to get the current admin's username
function getCurrentAdminUsername() {
    return $_SESSION['admin_username'] ?? null;
}

// Function to redirect admins to the login page if not logged in
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: admin_login.php'); 
        exit();
    }
}

// Function to log out the user or admin
function logout() {
    session_destroy();
}
?>
