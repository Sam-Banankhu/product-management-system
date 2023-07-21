<!DOCTYPE html>
<html>
<head>
    <title>View Cart</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/view_cart.css">
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

        <div class="checkout-form">
            <input type="checkbox" id="checkout-checkbox">
            <label for="checkout-checkbox">I have reviewed my cart and wish to proceed to checkout.</label>
            <button class="checkout-btn" id="checkout-btn" disabled>Proceed to Checkout</button>
        </div>
    </div>

    <script src="js/view_cart.js"></script>
</body>
</html>
