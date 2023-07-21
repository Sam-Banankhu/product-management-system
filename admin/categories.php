<?php
// Include the necessary files for database connection and session management
include("admin_header.php");
require_once '../db_connection.php';
require_once 'session.php';
require_once 'category_functions.php'; // Include the submodule

// Check if the user is already logged in, redirect to the index if true
if (!isAdminLoggedIn()) {
    header('Location: admin_login.php'); // Redirect to admin login if user is not logged in
    exit();
}

// Handle the category form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    addCategory($category_name); // Call the function from the submodule to add the category
}

// Handle the category deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $category_id = $_POST['category_id'];
    deleteCategory($category_id); // Call the function from the submodule to delete the category
}

// Pagination variables
$items_per_page = 10;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Retrieve the total number of categories
$query = "SELECT COUNT(*) AS total_categories FROM categories";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$total_categories = $row['total_categories'];

// Calculate the total number of pages
$total_pages = ceil($total_categories / $items_per_page);

// Retrieve the list of categories with pagination
$query = "SELECT * FROM categories LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $offset, $items_per_page);
$stmt->execute();
$result = $stmt->get_result();
$categories = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        function confirmDelete(categoryId) {
            if (confirm("Are you sure you want to delete this category?")) {
                $.ajax({
                    type: "POST",
                    url: "categories.php",
                    data: { delete_category: 1, category_id: categoryId },
                    success: function() {
                        // After successful deletion, remove the deleted category row from the table
                        $("#category-" + categoryId).remove();
                    }
                });
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Categories</h1>
        <h2>Add Category</h2>
        <form method="POST">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="category_name">Category Name:</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_category">Add Category</button>
                </div>
            </div>
        </form>

        <hr>

        <h2>Categories</h2>
        <table class="table">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr id="category-<?php echo $category['category_id']; ?>">
                        <!-- <td><?php echo $category['category_id']; ?></td> -->
                        <td><?php echo $category['name']; ?></td>
                        <td>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $category['category_id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Categories Pagination">
            <ul class="pagination">
                <?php if ($current_page > 1): ?>
                    <li class="page-item"><a class="page-link" href="categories.php?page=<?php echo $current_page - 1; ?>">Previous</a></li>
                <?php endif; ?>

                <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                    <li class="page-item <?php echo ($page == $current_page) ? 'active' : ''; ?>"><a class="page-link" href="categories.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="categories.php?page=<?php echo $current_page + 1; ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <hr>

        <a href="manage_products.php" class="btn btn-secondary">Go Back</a>
    </div>
</body>
</html>
