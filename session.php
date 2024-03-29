<?php
session_start();

// Function to check if the user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}


// Function to get the current user's ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}
// Function to get the current user's username
function getCurrentUsername() {
    return $_SESSION['username'] ?? null;
}

// Function to redirect users to the login page if not logged in
function requireLogin() {
    if (!isUserLoggedIn()) {
        header('Location: login.php'); 
        exit();
    }
}
// Function to log out the user or admin
function logout() {
    session_destroy();
}
?>
