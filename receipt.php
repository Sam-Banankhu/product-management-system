<!DOCTYPE html>
<html>
<head>
    <title>Order Receipt</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/reciept.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</head>
<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include("header.php");
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

            // Fetch the order items for the order from the order_items table
            $query = "SELECT items.name, order_items.quantity, order_items.item_price 
                      FROM order_items 
                      INNER JOIN items ON order_items.item_id = items.item_id 
                      WHERE order_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $orderID);
            $stmt->execute();
            $result = $stmt->get_result();
            $orderItems = array();

            while ($row = $result->fetch_assoc()) {
                $orderItems[] = $row;
            }

            $stmt->close();
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
                <?php foreach ($orderItems as $item) : ?>
                    <div class="receipt-item">
                        <div class="receipt-item-name"><?php echo $item['name']; ?></div>
                        <div class="receipt-item-quantity">Quantity: <?php echo $item['quantity']; ?></div>
                        <div class="receipt-item-price">Price: MWK <?php echo number_format($item['item_price'], 2); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="receipt-total">
                Total: MWK <?php echo number_format($order['total_cost'], 2); ?>
            </div>
        </div>

        <!-- Button to save the receipt as an image -->
        <button id="saveButton" class="btn btn-primary">Save Receipt (Image)</button>
        <div class="thank-you-message">Thank you for your order. We appreciate your business.</div>
    </div>

    <!-- JavaScript code to handle saving the receipt as an image -->
    <script>
        document.getElementById("saveButton").addEventListener("click", function () {
            html2canvas(document.querySelector(".receipt")).then(function (canvas) {
                var dataURL = canvas.toDataURL("image/png");

                var link = document.createElement("a");
                link.download = "order_receipt.png";
                link.href = dataURL;
                link.click();
            });
        });
    </script>
</body>
</html>
