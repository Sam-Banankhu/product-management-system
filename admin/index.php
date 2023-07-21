<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the necessary files for database connection and session management
require_once '../db_connection.php';
require_once 'session.php';
require_once 'category_functions.php'; // Include the submodule

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

// Function to get the statistics for customers, categories, products, and regular admins
function getStatistics($conn)
{
    $statistics = array();

    // Get the number of customers
    $query = "SELECT COUNT(*) AS num_customers FROM users";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $statistics['num_customers'] = $row['num_customers'];

    // Get the number of categories
    $query = "SELECT COUNT(*) AS num_categories FROM categories";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $statistics['num_categories'] = $row['num_categories'];

     // Get the number of items
     $query = "SELECT COUNT(*) AS num_items FROM items";
     $result = $conn->query($query);
     $row = $result->fetch_assoc();
     $statistics['num_items'] = $row['num_items'];

    // Get the number of regular admins
    $query = "SELECT COUNT(*) AS num_regular_admins FROM admins WHERE role = 'regular admin'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $statistics['num_regular_admins'] = $row['num_regular_admins'];

    return $statistics;
}

$statistics = getStatistics($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management System - Admin Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    
</head>
<body>
    <div class="container">
        <?php include("admin_header.php"); ?>

        <h1>Welcome, <?php echo $_SESSION['admin_username']; ?>!</h1>
        <p>This is the Admin Dashboard. You have <?php echo $admin_role; ?> privileges.</p>

        <?php if ($admin_role === 'super admin'): ?>
            <h2>Dashboard</h2>
            <div class="row">
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Number of Customers</h5>
                            <p class="card-text"><?php echo $statistics['num_customers']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Number of Categories</h5>
                            <p class="card-text"><?php echo $statistics['num_categories']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Number of Products</h5>
                            <p class="card-text"><?php echo $statistics['num_items']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Number of Regular Admins</h5>
                            <p class="card-text"><?php echo $statistics['num_regular_admins']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
