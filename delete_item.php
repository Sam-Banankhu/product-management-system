<?php
require_once 'db_connection.php';

if (isset($_POST['item_id'])) {
    $itemID = intval($_POST['item_id']);

    // Delete the item from the cart
    $query = "DELETE FROM cart WHERE item_id = $itemID";
    if ($conn->query($query)) {
        // Return JSON response indicating successful deletion
        echo json_encode(array('status' => 'success', 'message' => 'Item deleted successfully.'));
    } else {
        // Return JSON response indicating error
        echo json_encode(array('status' => 'error', 'message' => 'Error deleting item.'));
    }

    // Close the database connection
    $conn->close();
} else {
    // Return JSON response indicating invalid request
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request.'));
}
?>
