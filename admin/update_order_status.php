<?php
require_once '../db_connection.php';

if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];

    // Update the order status in the orders table
    $query = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $status, $orderId);
    if ($stmt->execute()) {
        echo "Order status updated successfully.";
    } else {
        echo "Error updating order status.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
