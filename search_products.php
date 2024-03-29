<?php
// Include the necessary files for database connection
require_once 'db_connection.php';

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $perPage = 10; // Number of items per page
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;

    // Calculate the starting index for the current page
    $start = ($page - 1) * $perPage;

    $query = "SELECT items.*, categories.name AS category_name 
              FROM items 
              JOIN categories ON items.category_id = categories.category_id
              WHERE items.name LIKE '%$searchTerm%'
              OR items.description LIKE '%$searchTerm%'
              OR categories.name LIKE '%$searchTerm%'
              ORDER BY items.name ASC
              LIMIT $start, $perPage";

    $result = $conn->query($query);

    // Check if there are any products matching the search term on the current page
    if ($result->num_rows > 0) {
        // Start building the HTML table
        echo "<table id=\"product-table\">";
        echo "<tr>";
        echo "<th onclick=\"sortTable(0)\">Name</th>";
        echo "<th onclick=\"sortTable(4)\">Category</th>";
        echo "<th onclick=\"sortTable(1)\">Description</th>";
        echo "<th onclick=\"sortTable(2)\">Quantity</th>";
        echo "<th onclick=\"sortTable(3)\">Price</th>";
        echo "<th>Action</th>"; // Add a new column for the "Add to Cart" button
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['category_name']}</td>";
            echo "<td>{$row['description']}</td>";
            echo "<td>{$row['quantity']}</td>";
            echo "<td>{$row['price']}</td>";
            echo "<td><input type=\"number\" class=\"quantityInput\" value=\"1\">";
            echo "<button class=\"addToCartBtn\" data-item-id=\"{$row['item_id']}\">Add to Cart</button></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No products found.</p>";
    }

    // Close the database connection
    $result->free_result();
    $conn->close();
} else {
    // If the search term is not provided, return an error message
    echo "<p>Invalid request.</p>";
}
?>
