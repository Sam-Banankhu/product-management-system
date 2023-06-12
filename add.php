<?php
// Include the necessary files for database connection
require_once 'db_connection.php'; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['item_name'];
    $description = $_POST['item_description'];
    $quantity = $_POST['item_quantity'];
    $price = $_POST['item_price'];
    $category_id = $_POST['item_category'];

    // Perform the database query to add the item
    $query = "INSERT INTO items (category_id, name, description, quantity, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isssi', $category_id, $name, $description, $quantity, $price);
    $stmt->execute();

    // Redirect back to admin.php after successful item addition
    header('Location: admin.php');
    exit();
}
?>
