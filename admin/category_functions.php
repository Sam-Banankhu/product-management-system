<?php
require_once '../db_connection.php';

function addCategory($categoryName)
{
    global $conn;
    $query = "INSERT INTO categories (name) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $categoryName);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function deleteCategory($categoryId)
{
    global $conn;
    $query = "DELETE FROM categories WHERE category_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $categoryId);
    $result = $stmt->execute();
    $stmt->close();

    // Delete related items
    if ($result) {
        $query = "DELETE FROM items WHERE category_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $categoryId);
        $result = $stmt->execute();
        $stmt->close();
    }

    return $result;
}
