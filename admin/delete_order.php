<?php
require_once '../db_connection.php';

// Check if the admin is logged in
require_once 'session.php';
requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the order_id is provided
    if (!isset($_POST['order_id'])) {
        http_response_code(400);
        echo "Order ID is missing.";
        exit();
    }

    $order_id = $_POST['order_id'];

    // Manually delete related records from order_items table
    $query = "DELETE FROM order_items WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $order_id);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo "Error deleting order items. " . $conn->error;
        exit();
    }

    $stmt->close();

    // Delete the order from the orders table
    $query = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $order_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        http_response_code(500);
        echo "Error deleting order. " . $conn->error;
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo "Method not allowed.";
}
