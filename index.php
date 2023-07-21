<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/customer_index.css">
</head>
<body>
    <?php
    // Include the necessary files for database connection, session management, and cart functions
    include("header.php");
    require_once 'db_connection.php';
    require_once 'session.php';
    require_once 'cart_functions.php'; // New cart functions file
    ?>

    <div class="container">
        <div class="search-form">
            <h2>Search Products</h2>
            <input type="text" id="searchTerm" placeholder="Enter search term" required>
            <button type="button" id="searchButton" class="btn btn-success">Search</button>
        </div>

        <div class="table-wrapper" id="productList">
            <!-- Product list will be displayed here -->
        </div>
    </div>

    <footer>
        <?php include("footer.php"); ?>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Load the initial product list on page load
            loadProducts();

            // Function to load products asynchronously using AJAX
            function loadProducts() {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            document.getElementById("productList").innerHTML = xhr.responseText;
                        } else {
                            alert("Error loading products.");
                        }
                    }
                };

                xhr.open("GET", "get_products.php", true);
                xhr.send();
            }

            // Function to handle the search button click event
            document.getElementById("searchButton").addEventListener("click", function () {
                var searchTerm = document.getElementById("searchTerm").value;

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            document.getElementById("productList").innerHTML = xhr.responseText;
                        } else {
                            alert("Error searching products.");
                        }
                    }
                };

                xhr.open("POST", "search_products.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("search=" + searchTerm);
            });

            // Function to handle the add to cart button click event
            document.addEventListener("click", function (event) {
                if (event.target.classList.contains("addToCartBtn")) {
                    // Check if the user is logged in
                    var loggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
                    if (!loggedIn) {
                        // If not logged in, redirect to login page
                        window.location.href = "login.php";
                        return;
                    }

                    var itemID = event.target.getAttribute("data-item-id");
                    var quantityInput = event.target.previousElementSibling;
                    var quantity = quantityInput.value;

                    // Validate the quantity (you can add additional validation if needed)
                    if (!(Number.isInteger(Number(quantity)) && quantity > 0)) {
                        alert("Invalid quantity!");
                        return;
                    }

                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                alert("Item added to cart successfully!");
                            } else {
                                alert("Error adding item to cart.");
                            }
                        }
                    };

                    xhr.open("POST", "add_to_cart.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send("item_id=" + itemID + "&quantity=" + quantity);
                }
            });
        });
    </script>
</body>
</html>
