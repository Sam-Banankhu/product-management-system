<?php
// Include the necessary files for database connection
require_once 'db_connection.php';

// Function to get the cart items for a logged-in user
function getCartItems($userID)
{
    global $conn;

    $query = "SELECT cart.cart_id, items.item_id, items.name, items.price, cart.quantity 
              FROM cart 
              JOIN items ON cart.item_id = items.item_id 
              WHERE cart.user_id = $userID";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $cartItems = $result->fetch_all(MYSQLI_ASSOC);
        return $cartItems;
    } else {
        return array();
    }
}

// Function to calculate the total price of items in the cart
function calculateCartTotal($userID)
{
    global $conn;

    $query = "SELECT SUM(items.price * cart.quantity) AS total_price 
              FROM cart 
              JOIN items ON cart.item_id = items.item_id 
              WHERE cart.user_id = $userID";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_price'];
    } else {
        return 0;
    }
}

// Function to remove an item from the cart
function removeFromCart($cartID)
{
    global $conn;

    $query = "DELETE FROM cart WHERE cart_id = $cartID";
    $conn->query($query);
}
?>
