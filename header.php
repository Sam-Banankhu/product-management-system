<?php
// Include the session functions
require_once 'session.php';

// Check if the user is logged in
$isLoggedIn = isUserLoggedIn();

// Handle logout action
if (isset($_GET['logout'])) {
    logout();
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LogicLab Inc.</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .navbar-nav .admin-link {
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="index.php">LogicLab Inc.</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Dashboard</a>
                <a class="nav-link" href="about.php">About</a>
                <?php if ($isLoggedIn) { ?>
                    <a class="nav-link admin-link" href="view_cart.php">View Cart</a>
                    <a class="nav-link" href="order_history.php">Order History</a> 
                    <a class="nav-link" href="?logout">Logout</a>
                <?php } else { ?>
                    <a class="nav-link" href="login.php">Login</a>
                <?php } ?>
            </div>
        </div>
    </nav>
    <script src="js/bootstrap.min.js"></script>
    <script>
        // Check if the user is logged in and show the admin link
        var isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        if (isLoggedIn) {
            var adminLink = document.querySelector('.admin-link');
            if (adminLink) {
                adminLink.style.display = 'block';
            }
        }
    </script>
</body>
</html>
