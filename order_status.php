<!-- view_order_status.php -->
<!DOCTYPE html>
<html>
<head>
    <title>View Order Status</title>
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

        .order-details {
            margin-bottom: 20px;
        }

        .order-details p {
            margin: 0;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
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

    // Get the order ID from the URL parameter
    if (isset($_GET['order_id'])) {
        $orderId = $_GET['order_id'];

        // Fetch the order details from the orders table
        $query = "SELECT status FROM orders WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();

        if ($order) {
            // Display the order status
            echo '<div class="container">';
            echo '<div class="header">Order Status</div>';
            echo '<div class="order-details">';
            echo '<p><strong>Order ID:</strong> ' . $orderId . '</p>';
            echo '<p><strong>Status:</strong> ' . $order['status'] . '</p>';
            echo '</div>';
            // Add a link back to the customer's order history page
            echo '<a class="back-link" href="order_history.php">Back to Order History</a>';
            echo '</div>';
        } else {
            echo "Order not found.";
        }
    } else {
        echo "Invalid request.";
    }
    ?>
</body>
</html>
