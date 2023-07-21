<?php
require_once '../db_connection.php';

if (isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];

    // Delete the order from the orders table
    $query = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $orderId);
    if ($stmt->execute()) {
        echo "Order deleted successfully.";
    } else {
        echo "Error deleting order.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
