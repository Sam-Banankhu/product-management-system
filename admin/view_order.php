<!-- pms/admin/view_order.php -->
<!DOCTYPE html>
<html>
<head>
    <title>View Order</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
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

        .order-items {
            margin-bottom: 20px;
        }

        .order-item-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .order-item-card p {
            margin: 0;
        }

        .subtotal {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
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
    // include("admin_header.php");
    require_once '../db_connection.php';
    require_once 'session.php';

    // Check if the admin is logged in
    if (!isAdminLoggedIn()) {
        header('Location: admin_login.php');
        exit();
    }

    // Get the order ID from the URL parameter
    if (isset($_GET['order_id'])) {
        $orderId = $_GET['order_id'];

        // Fetch the order details from the orders table
        $query = "SELECT users.username, orders.status, orders.created_at FROM orders 
                  JOIN users ON orders.user_id = users.user_id WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();

        if ($order) {
            // Fetch the order items for the order from the order_items table
            $query = "SELECT items.name, order_items.quantity, order_items.item_price 
                      FROM order_items 
                      INNER JOIN items ON order_items.item_id = items.item_id 
                      WHERE order_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            $orderItems = array();

            while ($row = $result->fetch_assoc()) {
                $orderItems[] = $row;
            }

            $stmt->close();

            // Calculate the subtotal and total for the order
            $subtotal = 0;
            echo '<div class="container">';
            echo '<div class="header">View Order</div>';
            echo '<div class="order-details">';
            echo '<p><strong>Order ID:</strong> ' . $orderId . '</p>';
            echo '<p><strong>Customer Name:</strong> ' . $order['username'] . '</p>';
            echo '<p><strong>Status:</strong> ' . $order['status'] . '</p>';
            echo '<p><strong>Order Date:</strong> ' . $order['created_at'] . '</p>';
            echo '</div>';

            echo '<div class="order-items">';
            foreach ($orderItems as $item) {
                echo '<div class="order-item-card">';
                echo '<p><strong>Item Name:</strong> ' . $item['name'] . '</p>';
                echo '<p><strong>Quantity:</strong> ' . $item['quantity'] . '</p>';
                echo '<p><strong>Item Price:</strong> MWK ' . number_format($item['item_price'], 2) . '</p>';
                $itemSubtotal = $item['item_price'] * $item['quantity'];
                echo '<p><strong>Subtotal:</strong> MWK ' . number_format($itemSubtotal, 2) . '</p>';
                echo '</div>';
                $subtotal += $itemSubtotal;
            }
            echo '</div>';

            // Display the total
            echo '<div class="total">Total: MWK ' . number_format($subtotal, 2) . '</div>';

            // Add a link back to the admin's order history page
            echo '<a class="back-link" href="manage_orders.php">Back to Order History</a>';
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
