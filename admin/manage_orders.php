<?php
require_once '../db_connection.php';
require_once 'session.php';

// Check if the admin is logged in
if (!isAdminLoggedIn()) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all orders from the orders table
$query = "SELECT * FROM orders";
$result = $conn->query($query);

// Function to get the username of a customer based on the user ID
function getCustomerName($userId)
{
    global $conn;
    $query = "SELECT username FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
    return $username;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_panel.css">
</head>
<body>
    <?php include("admin_header.php"); ?>
    <div class="container">
        <h2>Manage Orders</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Cost</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo getCustomerName($order['user_id']); ?></td>
                        <td><?php echo 'MWK ' . number_format($order['total_cost'], 2); ?></td>
                        <td><?php echo $order['created_at']; ?></td>
                        <td>
                            <select class="form-control status-select" data-order-id="<?php echo $order['order_id']; ?>">
                                <option value="pending" <?php if ($order['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                <option value="shipped" <?php if ($order['status'] === 'shipped') echo 'selected'; ?>>Shipped</option>
                                <option value="delivered" <?php if ($order['status'] === 'delivered') echo 'selected'; ?>>Delivered</option>
                            </select>
                        </td>
                        <td>
                            <a href="view_order.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary">View Details</a>
                            <button class="btn btn-danger delete-btn" data-order-id="<?php echo $order['order_id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        // Update order status when the status select is changed
        document.addEventListener('change', function(event) {
            if (event.target.classList.contains('status-select')) {
                var orderId = event.target.getAttribute('data-order-id');
                var status = event.target.value;

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert('Order status updated successfully.');
                        } else {
                            alert('Error updating order status.');
                        }
                    }
                };

                xhr.open('POST', 'update_order_status.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('order_id=' + orderId + '&status=' + status);
            }
        });

        // Delete order when the delete button is clicked
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-btn')) {
                if (confirm('Are you sure you want to delete this order?')) {
                    var orderId = event.target.getAttribute('data-order-id');

                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                alert('Order deleted successfully.');
                                window.location.reload();
                            } else {
                                alert('Error deleting order.');
                            }
                        }
                    };

                    xhr.open('POST', 'delete_order.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('order_id=' + orderId);
                }
            }
        });
    </script>
</body>
</html>
