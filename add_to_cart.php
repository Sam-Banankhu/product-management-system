<?php
// add_to_cart.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the user is logged in
    session_start();
    if (!isset($_SESSION['user_id'])) {
        // If not logged in, return an error response
        http_response_code(401); // Unauthorized status code
        echo json_encode(["message" => "User not logged in"]);
        exit();
    }

    // Get item_id and quantity from the POST data
    $itemID = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Validate the input
    if (!(is_numeric($itemID) && is_numeric($quantity) && $quantity > 0)) {
        // If input is not valid, return an error response
        http_response_code(400); // Bad request status code
        echo json_encode(["message" => "Invalid input"]);
        exit();
    }

    // Database connection and query logic
    require_once 'db_connection.php';

    // Prepare and execute the SQL query to add the item to cart
    $stmt = $conn->prepare("INSERT INTO cart (user_id, item_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $_SESSION['user_id'], $itemID, $quantity);
    
    if ($stmt->execute()) {
        // Success response
        http_response_code(200); // OK status code
        echo json_encode(["message" => "Item added to cart successfully"]);
    } else {
        // Error response
        http_response_code(500); // Internal server error status code
        echo json_encode(["message" => "Error adding item to cart"]);
    }
    
    // Close the database connection
    $stmt->close();
    $conn->close();

} else {
    // Invalid request method
    http_response_code(405); // Method not allowed status code
    echo json_encode(["message" => "Invalid request method"]);
}
?>
