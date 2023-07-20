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

        .cart-item-subtotal {
            font-size: 16px;
            color: #777;
            margin-top: 5px;
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
    include("header.php");
    require_once 'db_connection.php';
    require_once 'session.php';
    require_once 'cart_functions.php';

    $userId = $_SESSION['user_id'];
    $cartItems = getCartItems($userId);
    $totalCost = 0;
    ?>

    <div class="container">
        <h2>Cart Items</h2>
        <div class="cart-items">
            <?php foreach ($cartItems as $item) : ?>
                <?php
                $subtotal = $item['price'] * $item['cart_quantity'];
                $totalCost += $subtotal;
                ?>
                <div class="cart-item">
                    <div class="cart-item-info">
                        <div class="cart-item-name"><?php echo $item['name']; ?></div>
                        <div class="cart-item-description"><?php echo $item['description']; ?></div>
                        <div class="cart-item-price">MWK <?php echo $item['price']; ?></div>
                        <div class="cart-item-subtotal">Subtotal: MWK <?php echo number_format($subtotal, 2); ?></div>
                    </div>
                    <input type="number" class="cart-item-quantity" value="<?php echo $item['cart_quantity']; ?>" data-item-id="<?php echo $item['item_id']; ?>">
                    <button class="btn btn-danger cart-item-delete" data-item-id="<?php echo $item['item_id']; ?>" title="Delete item">Delete</button>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-total">
            Total: MWK <?php echo number_format($totalCost, 2); ?>
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

                    if (!(Number.isInteger(Number(quantity)) && quantity > 0)) {
                        alert("Invalid quantity!");
                        return;
                    }

                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
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

            document.addEventListener("click", function (event) {
                if (event.target.classList.contains("cart-item-delete")) {
                    var itemID = event.target.getAttribute("data-item-id");

                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
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
