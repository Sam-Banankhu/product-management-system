<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connection.php';
require_once 'session.php';
require_once 'cart_functions.php';

function generateUniqueOrderID()
{
    return 'ORDER' . time();
}

// Check if the customer is logged in
if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$cartItems = getCartItems($userId);

// Check if the cart is empty
if (empty($cartItems)) {
    echo "<script>alert('Your cart is empty. Please add items to your cart before proceeding to checkout.'); window.location.href = 'index.php';</script>";
    exit();
}

$totalCost = calculateTotalCost($userId);

// Generate a unique order ID
$orderID = generateUniqueOrderID();

// Start a transaction to ensure atomicity (both orders and order_items tables are updated together)
$conn->begin_transaction();

try {
    // Add data to the orders table
    $query = "INSERT INTO orders (order_id, user_id, total_cost, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sis', $orderID, $userId, $totalCost);
    if ($stmt->execute()) {
        // Add data to the order_items table
        foreach ($cartItems as $item) {
            $itemID = $item['item_id'];
            $quantity = $item['cart_quantity'];

            // Fetch the item details from the items table
            $itemQuery = "SELECT price FROM items WHERE item_id = ?";
            $itemStmt = $conn->prepare($itemQuery);
            $itemStmt->bind_param('i', $itemID);
            $itemStmt->execute();
            $itemResult = $itemStmt->get_result();
            $itemData = $itemResult->fetch_assoc();
            $itemStmt->close();

            $itemPrice = $itemData['price'];

            // Insert into order_items table with the item details
            $orderItemQuery = "INSERT INTO order_items (order_id, item_id, quantity, item_price) VALUES (?, ?, ?, ?)";
            $orderItemStmt = $conn->prepare($orderItemQuery);
            $orderItemStmt->bind_param('siid', $orderID, $itemID, $quantity, $itemPrice);
            $orderItemStmt->execute();
            $orderItemStmt->close();
        }

        // Clear the cart
        clearCart($userId);

        // Commit the transaction
        $conn->commit();

        // Redirect to the receipt page passing the order ID as a parameter
        header("Location: receipt.php?order_id=$orderID");
        exit();
    } else {
        // Error creating the order
        echo "Error creating the order.";
    }
} catch (Exception $e) {
    // An error occurred, rollback the transaction
    $conn->rollback();
    echo "Error creating the order: " . $e->getMessage();
}

$conn->close();
?>
