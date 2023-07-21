<!DOCTYPE html>
<html>
<head>
    <title>Manage Customers</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_header.css">
    <style>
            
        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            display: inline-block;
        }

        .status-button {
            cursor: pointer;
            background-color: transparent;
            border: none;
            outline: none;
        }
        body {
            padding-top: 70px; 
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
        }

        .navbar-nav .admin-link {
            display: none;
        }
    </style>
</head>
<body>
    <?php
    require_once 'session.php';
    require_once '../db_connection.php';

    // Check if the user is logged in and is an admin
    if (!isAdminLoggedIn()) {
        header('Location: admin_login.php');
        exit();
    }

    // Fetch all customer records from the database
    $query = "SELECT * FROM users";
    $result = $conn->query($query);

    // Check if any customers found
    if ($result->num_rows > 0) {
        $customers = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $customers = array();
    }

    // Function to enable or disable customer accounts
    function toggleCustomerStatus($customerId, $enabled) {
        global $conn;
        $query = "UPDATE users SET enabled = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $enabled, $customerId);
        $stmt->execute();
        $stmt->close();
    }

    // Function to delete a customer account
    function deleteCustomerAccount($customerId) {
        global $conn;
        $query = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $customerId);
        $stmt->execute();
        $stmt->close();
    }

    // Handle form submissions and update customer information
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the form submission is for enabling/disabling or deleting a customer account
        if (isset($_POST['action']) && isset($_POST['customer_id'])) {
            $customerId = intval($_POST['customer_id']);
            $action = $_POST['action'];

            if ($action === 'toggle_status') {
                $enabled = isset($_POST['enabled']) ? 1 : 0;
                toggleCustomerStatus($customerId, $enabled);
            } elseif ($action === 'delete') {
                deleteCustomerAccount($customerId);
            }
        }
    }
    ?>

    <?php include("admin_header.php"); ?>

    <div class="container">
        <h2>Manage Customers</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer) : ?>
                    <tr>
                        <td><?php echo $customer['user_id']; ?></td>
                        <td><?php echo $customer['username']; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="customer_id" value="<?php echo $customer['user_id']; ?>">
                                <input type="checkbox" name="enabled" value="1" <?php if ($customer['enabled'] == 1) echo "checked"; ?>>
                                <input type="hidden" name="action" value="toggle_status">
                                <button type="submit" class="status-button">Save</button>
                            </form>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="customer_id" value="<?php echo $customer['user_id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="status-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
