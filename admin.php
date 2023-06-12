<?php
// Include the necessary files for database connection and session management
include("header.php");

require_once 'db_connection.php'; 
require_once 'session.php'; 

// Check if the user is already logged in, redirect to the index if true
if (!isLoggedIn()) {
    header('Location: index.php'); // Redirect to index.php if user is not logged in
    exit();
}

// Handle the category form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];

    // Insert the new category into the categories table
    $query = "INSERT INTO categories (name) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $category_name);
    $stmt->execute();
    $stmt->close();
}

// Retrieve the list of categories from the categories table
$query = "SELECT * FROM categories";
$result = $conn->query($query);
$categories = $result->fetch_all(MYSQLI_ASSOC);
$result->free_result();

// Handle the item form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $item_name = $_POST['item_name'];
    $item_description = $_POST['item_description'];
    $item_quantity = $_POST['item_quantity'];
    $item_price = $_POST['item_price'];
    $item_category = $_POST['item_category'];

    // Insert the new item into the items table
    $query = "INSERT INTO items (category_id, name, description, quantity, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isssi', $item_category, $item_name, $item_description, $item_quantity, $item_price);
    $stmt->execute();
    $stmt->close();
}

// Retrieve the list of items from the items table
$query = "SELECT * FROM items";
$result = $conn->query($query);
$items = $result->fetch_all(MYSQLI_ASSOC);
$result->free_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management System - Admin</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"> 
    <script src="js/bootstrap.min.js"></script> 
</head>
<body>
    <div class="container">
        <h1>Welcome, Admin!</h1>
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

        <h2>Add Item</h2>
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
                    <th>ID</th>
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
                        <td><?php echo $item['item_id']; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['description']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                        <td><?php echo $item['category_id']; ?></td>
                        <td>
    <a href="edit.php?item_id=<?php echo $item['item_id']; ?>" class="btn btn-primary">Edit</a>
    <a href="delete.php?item_id=<?php echo $item['item_id']; ?>" class="btn btn-danger">Delete</a>
</td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>

        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>
</body>
</html>
