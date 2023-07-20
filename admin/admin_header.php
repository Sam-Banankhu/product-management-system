<nav>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link <?php if ($active_page === 'dashboard') echo 'active'; ?>" href="index.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($active_page === 'customers') echo 'active'; ?>" href="manage_customers.php">Manage Customers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($active_page === 'orders') echo 'active'; ?>" href="manage_orders.php">Manage Orders</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($active_page === 'products') echo 'active'; ?>" href="manage_products.php">Manage Products</a>
        </li>
        <?php if ($admin_role === 'super admin'): ?>
            <li class="nav-item">
                <a class="nav-link <?php if ($active_page === 'admins') echo 'active'; ?>" href="manage_admins.php">Manage Admins</a>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" href="../logout.php">Logout</a>
        </li>
    </ul>
</nav>
