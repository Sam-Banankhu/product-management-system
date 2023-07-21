<!DOCTYPE html>
<html>
<head>
    <title>Manage Customers</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_header.css">
    <style>
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

        .table-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

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

        .status-form {
            display: inline-block;
        }

        .status-button {
            cursor: pointer;
            background-color: transparent;
            border: none;
            outline: none;
            color: #007bff;
            font-weight: bold;
            text-decoration: underline;
        }

        .delete-form {
            display: inline-block;
        }

        .delete-button {
            cursor: pointer;
            background-color: #dc3545;
            border: none;
            color: #fff;
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <?php
     error_reporting(E_ALL);
     ini_set('display_errors', 1);
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
        if (isset($_POST['action']) && isset($_POST['user_id'])) {
            $customerId = intval($_POST['user_id']);
            $action = $_POST['action'];

            if ($action === 'toggle_status') {
                $enabled = isset($_POST['enabled']) ? 1 : 0;
                toggleCustomerStatus($customerId, $enabled);
                echo "Success"; // Return success message to the XMLHttpRequest
                exit();
            } elseif ($action === 'delete') {
                deleteCustomerAccount($customerId);
                echo "Success"; // Return success message to the XMLHttpRequest
                exit();
            }
        }
    }
    ?>

    <?php include("admin_header.php"); ?>

    <div class="container">
        <h2>Manage Customers</h2>
        <div class="table-container">
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
                                <form class="status-form">
                                    <input type="hidden" name="customer_id" value="<?php echo $customer['user_id']; ?>">
                                    <input type="checkbox" name="enabled" value="1" <?php if (isset($customer['enabled']) && $customer['enabled'] == 1) echo "checked"; ?>>
                                    <input type="hidden" name="action" value="toggle_status">
                                    <button type="submit" class="status-button">Save</button>
                                </form>
                            </td>
                            <td>
    <form class="delete-form" onsubmit="return confirm('Are you sure you want to delete this customer?');" action="manage_customers.php">
        <input type="hidden" name="customer_id" value="<?php echo $customer['user_id']; ?>">
        <input type="hidden" name="action" value="delete">
        <button type="button" class="delete-button" onclick="deleteCustomer(this)">Delete</button>
    </form>
</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


    <script src="../js/bootstrap.min.js"></script>
    <script>
        // Function to handle the status toggle form submission using XMLHttpRequest
    document.querySelectorAll(".status-form").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "manage_customers.php");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert("Status updated successfully.");
                }
            };
            xhr.send(formData);
        });
    });

    // JavaScript code to handle asynchronous deletion
    function deleteCustomer(button) {
        var form = button.parentElement;
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.getAttribute('action'), true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Successful response
                location.reload(); // Refresh the page to reflect the changes
            } else {
                // Error handling
                console.error('Error deleting customer.');
            }
        };
        xhr.onerror = function () {
            console.error('Network error occurred.');
        };
        xhr.send(formData);
    }
    </script>
</body>
</html>
