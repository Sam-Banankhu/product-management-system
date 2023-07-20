<?php
// Include the necessary files for database connection
require_once 'db_connection.php';

// Pagination settings
$perPage = 10; // Number of items per page

// Get the current page number from the request, default to page 1 if not provided or invalid
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;

// Calculate the starting index for the current page
$start = ($page - 1) * $perPage;

// Query to fetch products for the current page
$query = "SELECT items.*, categories.name AS category_name 
          FROM items 
          JOIN categories ON items.category_id = categories.category_id
          ORDER BY items.name ASC
          LIMIT $start, $perPage";

$result = $conn->query($query);

// Check if there are any products on the current page
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

    // Pagination links
    echo "<nav aria-label=\"Pagination\">";
    echo "<ul class=\"pagination\">";
    if ($page > 1) {
        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=" . ($page - 1) . "\">Previous</a></li>";
    }
    // Calculate total number of pages
    $query = "SELECT COUNT(*) AS total FROM items";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $totalPages = ceil($row['total'] / $perPage);
    // Display pagination links
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<li class=\"page-item" . ($i == $page ? " active" : "") . "\"><a class=\"page-link\" href=\"?page=$i\">$i</a></li>";
    }
    if ($page < $totalPages) {
        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=" . ($page + 1) . "\">Next</a></li>";
    }
    echo "</ul>";
    echo "</nav>";
} else {
    echo "<p>No products found.</p>";
}

// Close the database connection
$result->free_result();
$conn->close();
?>
