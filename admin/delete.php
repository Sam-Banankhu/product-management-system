<?php
// Include the necessary files for database connection
require_once '../db_connection.php'; 

// Check if the item_id is provided via GET
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    // Perform the database query to delete the item
    $query = "DELETE FROM items WHERE item_id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $item_id);
    $stmt->execute();

    // Redirect back to index.php after successful item deletion
    header('Location: index.php');
    exit();
}
?>
