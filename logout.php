<?php
// Include the necessary files for session management
require_once 'session.php'; 

// Logout the user
logout();

// Redirect to the index.php file
header('Location: index.php');
exit();
?>