<?php
// Function to add an item to the cart for a user
function addItemToCart($userID, $itemID, $quantity)
{
    global $conn;

    $query = "INSERT INTO cart (user_id, item_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $userID, $itemID, $quantity);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Function to update the quantity of an item in the cart for a user
function updateCartItemQuantity($userID, $itemID, $quantity)
{
    global $conn;

    $query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $quantity, $userID, $itemID);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Function to get the cart items for a user
function getCartItems($userID)
{
    global $conn;

    // Query to fetch cart items for the user with remaining quantity
    $query = "SELECT items.*, cart.quantity AS cart_quantity,
              (items.quantity - IFNULL(cart.quantity, 0)) AS remaining_quantity
              FROM items
              LEFT JOIN cart ON items.item_id = cart.item_id AND cart.user_id = '$userID'
              WHERE cart.quantity IS NOT NULL";

    $result = $conn->query($query);

    $cartItems = array();

    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    return $cartItems;
}

// Function to calculate the total cost of items in the cart for a user
function calculateTotalCost($userID)
{
    global $conn;

    $query = "SELECT SUM(cart.quantity * items.price) AS total_cost 
              FROM cart 
              JOIN items ON cart.item_id = items.item_id 
              WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalCost = $row['total_cost'];
    $stmt->close();
    return $totalCost;
}

function clearCart($userId)
{
    global $conn;

    $query = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


?>
