<!-- order_history.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/order_history.css">
</head>
<body>
    <?php
       error_reporting(E_ALL);
       ini_set('display_errors', 1);
       include("header.php");
    require_once 'db_connection.php';
    require_once 'session.php';

    // Check if the customer is logged in
    if (!isUserLoggedIn()) {
        header('Location: login.php');
        exit();
    }

    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Fetch the list of orders for the user from the orders table
    $query = "SELECT order_id, status FROM orders WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    ?>

    <div class="container">
        <div class="header">Order History</div>

        <div class="order-list">
            <?php while ($order = $result->fetch_assoc()) : ?>
                <div class="order-item">
                    <div class="order-id"><?php echo $order['order_id']; ?></div>
                    <div class="order-status">Status: <?php echo $order['status']; ?></div>
                    <a class="order-details-link" href="order_status.php?order_id=<?php echo $order['order_id']; ?>">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
