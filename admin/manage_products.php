<?php
     error_reporting(E_ALL);
     ini_set('display_errors', 1);
// Include the necessary files for database connection and session management
include("admin_header.php");
require_once '../db_connection.php';
require_once 'session.php';

// Check if the user is already logged in, redirect to the index if not
if (!isAdminLoggedIn()) {
    header('Location: admin_login.php');
    exit();
}

// Handle the item form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    requireAdminLogin();

    $item_name = $_POST['item_name'];
    $item_description = $_POST['item_description'];
    $item_quantity = $_POST['item_quantity'];
    $item_price = $_POST['item_price'];
    $item_category = $_POST['item_category'];

    // Server-side validation for price and quantity fields
    if ($item_quantity < 0 || $item_price < 0) {
        $error_message = "Quantity and price cannot be negative.";
    } else {
        // Insert the new item into the items table
        $query = "INSERT INTO items (name, description, quantity, price, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssidi', $item_name, $item_description, $item_quantity, $item_price, $item_category);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle the item deletion
if (isset($_GET['delete_item'])) {
    requireAdminLogin();

    $item_id = $_GET['delete_item'];

    // Check if there are any orders containing this item
    $query = "SELECT COUNT(*) AS order_count FROM order_items WHERE item_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $order_count = $row['order_count'];
    $stmt->close();

    if ($order_count > 0) {
        // If there are orders containing this item, delete the corresponding rows from order_items first
        $query = "DELETE FROM order_items WHERE item_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $item_id);
        $stmt->execute();
        $stmt->close();
    }

    // Now delete the item from the items table
    $query = "DELETE FROM items WHERE item_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $item_id);
    $stmt->execute();
    $stmt->close();
}


// Retrieve the total number of items
$query = "SELECT COUNT(*) AS total_items FROM items";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$total_items = $row['total_items'];
$result->free_result();

// Define the number of items to display per page
$items_per_page = 10;

// Calculate the total number of pages
$total_pages = ceil($total_items / $items_per_page);

// Get the current page number
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the starting and ending item indices for the current page
$start_index = ($current_page - 1) * $items_per_page;
$end_index = $start_index + $items_per_page - 1;

// Retrieve the list of items with their corresponding categories based on the current page
$query = "SELECT items.item_id, items.name, items.description, items.quantity, items.price, categories.name AS category_name
          FROM items
          INNER JOIN categories ON items.category_id = categories.category_id
          LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $start_index, $items_per_page);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Retrieve the list of categories from the categories table
$query = "SELECT * FROM categories";
$result = $conn->query($query);
$categories = $result->fetch_all(MYSQLI_ASSOC);
$result->free_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management System - Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css"> 
    <script src="../js/bootstrap.min.js"></script> 
</head>
<body>

    <div class="container">
        <h1>Admin Dashboard</h1>

        <h2>Add Item</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="item_name">Name:</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" required>
                    </div>
                    <div class="form-group">
                        <label for="item_description">Description:</label>
                        <textarea class="form-control" id="item_description" name="item_description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="item_quantity">Quantity:</label>
                        <input type="number" class="form-control" id="item_quantity" name="item_quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="item_price">Price:</label>
                        <input type="number" class="form-control" id="item_price" name="item_price" required>
                    </div>
                    <div class="form-group">
                        <label for="item_category">Category:</label>
                        <select class="form-control" id="item_category" name="item_category" required>
                            <option value="" selected disabled>Select a category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_item">Add Item</button>
                </div>
            </div>
        </form>

        <hr>

        <h2>Items</h2>
        <table class="table">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <!-- <td><?php echo $item['item_id']; ?></td> -->
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['description']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                        <td><?php echo $item['category_name']; ?></td>
                        <td>
                            <a href="manage_products.php?delete_item=<?php echo $item['item_id']; ?>" class="btn btn-danger">Delete</a>
                            <a href="edit.php?item_id=<?php echo $item['item_id']; ?>" class="btn btn-primary">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($current_page > 1): ?>
                    <li class="page-item"><a class="page-link" href="manage_products.php?page=<?php echo $current_page - 1; ?>">Previous</a></li>
                <?php endif; ?>

                <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                    <li class="page-item <?php if ($page == $current_page) echo 'active'; ?>"><a class="page-link" href="index.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="manage_products.php?page=<?php echo $current_page + 1; ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <hr>

        <a href="categories.php" class="btn btn-secondary">Manage Categories</a>
        <a href="../logout.php" class="btn btn-secondary">Logout</a>
    </div>
  
</body>
</html>
