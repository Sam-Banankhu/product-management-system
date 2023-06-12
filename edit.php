<?php
// Include the necessary files for database connection
require_once 'db_connection.php'; /

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $name = $_POST['item_name'];
    $description = $_POST['item_description'];
    $quantity = $_POST['item_quantity'];
    $price = $_POST['item_price'];
    $category_id = $_POST['item_category'];

    // Perform the database query to update the item
    $query = "UPDATE items SET category_id = ?, name = ?, description = ?, quantity = ?, price = ? WHERE item_id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isssii', $category_id, $name, $description, $quantity, $price, $item_id);
    $stmt->execute();

    // Redirect back to admin.php after successful item update
    header('Location: admin.php');
    exit();
}
?>
