// JavaScript file (script.js) to handle shopping cart functionality

// Cart data structure to store the items added to the cart
let cartItems = [];

// Function to add item to the cart
// Function to add item to the cart using Ajax
function addToCart(itemId, itemName, itemPrice, itemQuantity) {
    // Create an object representing the item
    const newItem = {
        item_id: itemId,
        item_name: itemName,
        item_price: itemPrice,
        item_quantity: itemQuantity
    };

    // Send the item data to the cart_handler.php file using Ajax
    fetch('cart_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(newItem)
    })
    .then(response => response.json())
    .then(data => {
        // Display a message to the user based on the server response
        if (data.status === 'success') {
            alert(data.message);
            // You can perform additional actions here if needed, such as updating the cart summary
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
// Function to update the cart summary
function updateCartSummary() {
    // Get the cart summary element by ID
    const cartSummaryElement = document.getElementById('cart-summary');

    // Calculate the total quantity and total price of items in the cart
    let totalQuantity = 0;
    let totalPrice = 0;
    for (const item of cartItems) {
        totalQuantity += item.quantity;
        totalPrice += item.price * item.quantity;
    }

    // Update the cart summary text
    cartSummaryElement.textContent = `Cart (${totalQuantity} items) - Total: $${totalPrice.toFixed(2)}`;
}

// Add event listeners to all "Add to Cart" buttons
const addToCartButtons = document.querySelectorAll('.add-to-cart');
addToCartButtons.forEach(button => {
    button.addEventListener('click', () => {
        const itemId = button.dataset.itemId;
        const itemName = button.dataset.itemName;
        const itemPrice = parseFloat(button.dataset.itemPrice);
        const itemQuantity = parseInt(button.dataset.itemQuantity);

        addToCart(itemId, itemName, itemPrice, itemQuantity);
    });
});

// Call updateCartSummary initially to set the initial cart summary
updateCartSummary();
