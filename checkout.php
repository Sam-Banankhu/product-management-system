<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connection.php';
require_once 'session.php';
require_once 'cart_functions.php';

// Function to generate a unique order ID (You can customize this function based on your requirements)
function generateUniqueOrderID()
{
    return 'ORDER' . time(); // Example: ORDER1634150294
}

// Check if the customer is logged in
if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$cartItems = getCartItems($userId);
$totalCost = calculateTotalCost($userId);

// Proceed with creating the order

// Generate a unique order ID
$orderID = generateUniqueOrderID();

// Add data to the orders table
$query = "INSERT INTO orders (order_id, user_id, total_cost, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param('sis', $orderID, $userId, $totalCost);
if ($stmt->execute()) {
    // Clear the cart
    clearCart($userId);

    // Redirect to the receipt page passing the order ID as a parameter
    header("Location: receipt.php?order_id=$orderID");
    exit();
} else {
    // Error creating the order
    echo "Error creating the order.";
}

$conn->close();
?>
