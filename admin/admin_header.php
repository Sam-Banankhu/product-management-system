<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_header.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark admin-nav">
    <div class="container">
        <a class="navbar-brand" href="index.php">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto"> 
                <li class="nav-item <?php if ($active_page === 'dashboard') echo 'active'; ?>">
                    <a class="nav-link" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item <?php if ($active_page === 'customers') echo 'active'; ?>">
                    <a class="nav-link" href="manage_customers.php">Manage Customers</a>
                </li>
                <li class="nav-item <?php if ($active_page === 'orders') echo 'active'; ?>">
                    <a class="nav-link" href="manage_orders.php">Manage Orders</a>
                </li>
                <li class="nav-item <?php if ($active_page === 'products') echo 'active'; ?>">
                    <a class="nav-link" href="manage_products.php">Manage Products</a>
                </li>
                <?php if (isset($admin_role) && $admin_role === 'super admin'): ?>
                    <li class="nav-item <?php if ($active_page === 'admins') echo 'active'; ?>">
                        <a class="nav-link" href="manage_admins.php">Manage Admins</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
