<!-- delete_item.php -->
<?php
// Include the necessary files for database connection
require_once 'db_connection.php';

// Check if the item ID is provided via POST
if (isset($_POST['item_id'])) {
    $itemID = intval($_POST['item_id']);

    // Delete the item from the cart
    $query = "DELETE FROM cart WHERE item_id = $itemID";
    if ($conn->query($query)) {
        echo "Item deleted successfully.";
    } else {
        echo "Error deleting item.";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
