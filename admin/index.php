<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include the necessary files for database connection and session management
require_once '../db_connection.php';
require_once '../session.php';

// Redirect to the admin login page if not logged in as an admin
requireAdminLogin();

// Get the admin's role from the database
$admin_id = $_SESSION['admin_id'];
$query = "SELECT role FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$admin_role = $admin['role'];
$stmt->close();

// Set the active page for the admin header
$active_page = 'dashboard'; // You can set this dynamically based on the current page
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management System - Admin Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <?php include("admin_header.php"); ?>

        <!-- Rest of the index.php page content -->
        <h1>Welcome, <?php echo $_SESSION['admin_username']; ?>!</h1>
        <p>This is the Admin Dashboard. You have <?php echo $admin_role; ?> privileges.</p>

        <?php if ($active_page === 'dashboard'): ?>
            <!-- Display the customer management content here -->
            <h2>Dashboard</h2>
            <!-- ... -->
            <?php elseif ($active_page === 'customers'): ?>
            <!-- Display the order management content here -->
            <h2>Manage Customers</h2>
            <!-- ... -->
        <?php elseif ($active_page === 'orders'): ?>
            <!-- Display the order management content here -->
            <h2>Manage Orders</h2>
            <!-- ... -->
        <?php elseif ($active_page === 'products'): ?>
            <!-- Display the product management content here -->
            <h2>Manage Products</h2>
            <!-- ... -->
        <?php elseif ($active_page === 'admins' && $admin_role === 'super admin'): ?>
            <!-- Display the admin management content here (only for super admin) -->
            <h2>Manage Admins</h2>
            <!-- ... -->
        <?php endif; ?>

    </div>
    
</body>
</html>
