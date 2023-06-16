<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">

</head>
<body>
    <?php
    // Include the necessary files for database connection and session management
    include("header.php");

    require_once 'db_connection.php'; 
    require_once 'session.php'; 
    ?>

    <div class="search-form">
        <h2>Search Products</h2>
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Enter search term" required>
            <button type="submit" class="btn btn-success">Search</button>
        </form>
    </div>

    <?php
    $query = "SELECT items.*, categories.name AS category_name FROM items JOIN categories ON items.category_id = categories.category_id";
    $result = $conn->query($query);
    $items = $result->fetch_all(MYSQLI_ASSOC);
    $result->free_result();

    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];
        $matchingItems = array_filter($items, function ($item) use ($searchTerm) {
            return stripos($item['name'], $searchTerm) !== false || stripos($item['description'], $searchTerm) !== false;
        });

        if (!empty($matchingItems)) {
            echo "<h2>Search Results for '$searchTerm'</h2>";
            echo "<table id=\"product-table\">";
            echo "<tr>";
            echo "<th onclick=\"sortTable(0)\">Name</th>";
            echo "<th onclick=\"sortTable(4)\">Category</th>";
            echo "<th onclick=\"sortTable(1)\">Description</th>";
            echo "<th onclick=\"sortTable(2)\">Quantity</th>";
            echo "<th onclick=\"sortTable(3)\">Price</th>";
            echo "</tr>";

            
            // Pagination
            $perPage = 10;
            $totalItems = count($matchingItems);
            $totalPages = ceil($totalItems / $perPage);
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $start = ($currentPage - 1) * $perPage;
            $matchingItems = array_slice($matchingItems, $start, $perPage);
            
            foreach ($matchingItems as $item) {
                echo "<tr><td>{$item['name']}</td><td>{$item['category_name']}</td><td>{$item['description']}</td><td>{$item['quantity']}</td><td>{$item['price']}</td></tr>";
            }
            
            echo "</table>";
            
            // Pagination links
            echo "<nav aria-label=\"Pagination\">";
            echo "<ul class=\"pagination\">";
            if ($currentPage > 1) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=" . ($currentPage - 1) . "\">Previous</a></li>";
            }
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<li class=\"page-item" . ($i == $currentPage ? " active" : "") . "\"><a class=\"page-link\" href=\"?page=$i\">$i</a></li>";
            }
            if ($currentPage < $totalPages) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=" . ($currentPage + 1) . "\">Next</a></li>";
            }
            echo "</ul>";
            echo "</nav>";
            
            echo "<div class=\"view-all-button\"><button onclick=\"window.location.href='';\">View All</button></div>";
        } else {
            echo "<h2>No Results Found for '$searchTerm'</h2>";
        }
    } else {
        echo "<table id=\"product-table\">";
        echo "<tr>";
        echo "<th onclick=\"sortTable(0)\">Name</th>";
        echo "<th onclick=\"sortTable(4)\">Category</th>";
        echo "<th onclick=\"sortTable(1)\">Description</th>";
        echo "<th onclick=\"sortTable(2)\">Quantity</th>";
        echo "<th onclick=\"sortTable(3)\">Price</th>";
        echo "</tr>";

        
        // Pagination
        $perPage = 10;
        $totalItems = count($items);
        $totalPages = ceil($totalItems / $perPage);
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($currentPage - 1) * $perPage;
        $items = array_slice($items, $start, $perPage);
        
        foreach ($items as $item) {
            echo "<tr><td>{$item['name']}</td><td>{$item['category_name']}</td><td>{$item['description']}</td><td>{$item['quantity']}</td><td>{$item['price']}</td></tr>";
        }
        
        echo "</table>";
        
        // Pagination links
        echo "<nav aria-label=\"Pagination\">";
        echo "<ul class=\"pagination\">";
        if ($currentPage > 1) {
            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=" . ($currentPage - 1) . "\">Previous</a></li>";
        }
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<li class=\"page-item" . ($i == $currentPage ? " active" : "") . "\"><a class=\"page-link\" href=\"?page=$i\">$i</a></li>";
        }
        if ($currentPage < $totalPages) {
            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=" . ($currentPage + 1) . "\">Next</a></li>";
        }
        echo "</ul>";
        echo "</nav>";
    }
    ?>
    <footer>
        <?php include("footer.php");?>
    </footer>
    <script src="js/dashboard.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
