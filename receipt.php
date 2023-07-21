<!DOCTYPE html>
<html>
<head>
    <title>Order Receipt</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        /* Your custom styles here */

        .container {
            max-width: 960px;
            margin: 0 auto;
        }

        .receipt {
            margin-top: 50px;
            border: 1px solid #ddd;
            padding: 20px;
        }

        .receipt-heading {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .receipt-details {
            margin-bottom: 20px;
        }

        .receipt-details p {
            margin: 0;
        }

        .receipt-item {
            margin-bottom: 10px;
        }

        .receipt-item-name {
            font-size: 18px;
        }

        .receipt-item-quantity {
            font-size: 16px;
            color: #777;
            margin-top: 5px;
        }

        .receipt-total {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once 'db_connection.php';
    require_once 'session.php';
    require_once 'cart_functions.php';

    // Check if the customer is logged in
    if (!isUserLoggedIn()) {
        header('Location: login.php');
        exit();
    }

    // Get the order ID from the URL parameter
    if (isset($_GET['order_id'])) {
        $orderID = $_GET['order_id'];

        // Fetch the order details from the orders table
        $query = "SELECT * FROM orders WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $orderID);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();

        if ($order) {
            // Fetch the user's details from the users table
            $userID = $order['user_id'];
            $query = "SELECT * FROM users WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            // Fetch the cart items for the order from the cart table
            $cartItems = getCartItems($userID);
        } else {
            echo "Order not found.";
            exit();
        }
    } else {
        echo "Invalid request.";
        exit();
    }
    ?>

    <div class="container">
        <div class="receipt">
            <div class="receipt-heading">Order Receipt</div>
            <div class="receipt-details">
                <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
                <p><strong>Customer Name:</strong> <?php echo $user['username']; ?></p>
                <p><strong>Order Date:</strong> <?php echo $order['created_at']; ?></p>
            </div>

            <div class="receipt-items">
                <?php foreach ($cartItems as $item) : ?>
                    <div class="receipt-item">
                        <div class="receipt-item-name"><?php echo $item['name']; ?></div>
                        <div class="receipt-item-quantity">Quantity: <?php echo $item['cart_quantity']; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="receipt-total">
                Total: MWK <?php echo number_format($order['total_cost'], 2); ?>
            </div>
        </div>
    </div>
</body>
</html>
