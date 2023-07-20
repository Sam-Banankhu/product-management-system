<!-- view_cart.php -->
<!DOCTYPE html>
<html>
<head>
    <title>View Cart</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        /* Your custom styles here */

        .container {
            max-width: 960px;
            margin: 0 auto;
        }

        .cart-items {
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .cart-item img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .cart-item-name {
            font-size: 18px;
        }

        .cart-item-price {
            font-size: 16px;
            color: #999;
        }

        .cart-item-quantity {
            margin-left: auto;
            width: 50px;
            height: 30px;
            padding: 5px;
            font-size: 16px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .cart-item-delete {
            margin-left: 10px;
            cursor: pointer;
        }

        .cart-total {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin-bottom: 20px;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
            text-align: center;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
    // Include the necessary files for database connection and session management
    include("header.php");
    require_once 'db_connection.php';
    require_once 'session.php';
    require_once 'cart_functions.php'; // New cart functions file

    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Get the cart items for the current user
    $cartItems = getCartItems($userId);

    // Function to calculate the total cost of all items in the cart
    function calculateTotalCost($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    $totalCost = calculateTotalCost($cartItems);
    ?>

    <div class="container">
        <h2>Cart Items</h2>
        <div class="cart-items">
            <?php foreach ($cartItems as $item) : ?>
                <div class="cart-item">
                    <img src="img/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <div>
                        <div class="cart-item-name"><?php echo $item['name']; ?></div>
                        <div class="cart-item-price">$<?php echo $item['price']; ?></div>
                    </div>
                    <input type="number" class="cart-item-quantity" value="<?php echo $item['quantity']; ?>" data-item-id="<?php echo $item['item_id']; ?>">
                    <button class="cart-item-delete" data-item-id="<?php echo $item['item_id']; ?>" title="Delete item">&times;</button>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-total">
            Total: $<?php echo $totalCost; ?>
        </div>

        <button class="checkout-btn" onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Function to handle the quantity input change event
            document.addEventListener("change", function (event) {
                if (event.target.classList.contains("cart-item-quantity")) {
                    var itemID = event.target.getAttribute("data-item-id");
                    var quantity = event.target.value;

                    // Validate the quantity (you can add additional validation if needed)
                    if (!(Number.isInteger(Number(quantity)) && quantity > 0)) {
                        alert("Invalid quantity!");
                        return;
                    }

                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                // Reload the page after updating the quantity
                                window.location.reload();
                            } else {
                                alert("Error updating quantity.");
                            }
                        }
                    };

                    xhr.open("POST", "update_cart.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send("item_id=" + itemID + "&quantity=" + quantity);
                }
            });

            // Function to handle the delete button click event
            document.addEventListener("click", function (event) {
                if (event.target.classList.contains("cart-item-delete")) {
                    var itemID = event.target.getAttribute("data-item-id");

                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                // Reload the page after deleting the item
                                window.location.reload();
                            } else {
                                alert("Error deleting item.");
                            }
                        }
                    };

                    xhr.open("POST", "delete_item.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send("item_id=" + itemID);
                }
            });
        });
    </script>
</body>
</html>
