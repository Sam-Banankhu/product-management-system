<!-- update_cart.php -->
<?php
// Include the necessary files for database connection
require_once 'db_connection.php';

// Check if the item ID and quantity are provided via POST
if (isset($_POST['item_id']) && isset($_POST['quantity'])) {
    $itemID = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']);

    // Update the quantity of the item in the cart
    $query = "UPDATE cart SET quantity = $quantity WHERE item_id = $itemID";
    if ($conn->query($query)) {
        echo "Quantity updated successfully.";
    } else {
        echo "Error updating quantity.";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
