<!-- order_history.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        /* Your custom styles here */

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .order-list {
            margin-bottom: 20px;
        }

        .order-item {
            margin-bottom: 10px;
        }

        .order-id {
            font-size: 18px;
            font-weight: bold;
        }

        .order-status {
            font-size: 16px;
            color: #777;
            margin-top: 5px;
        }

        .order-details-link {
            display: inline-block;
            font-size: 16px;
            color: #007bff;
            cursor: pointer;
        }
    </style>
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
