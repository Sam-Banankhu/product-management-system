
document.addEventListener("DOMContentLoaded", function () {
    // Function to handle the checkbox change event
    var checkoutCheckbox = document.getElementById("checkout-checkbox");
    var checkoutBtn = document.getElementById("checkout-btn");

    checkoutCheckbox.addEventListener("change", function () {
        checkoutBtn.disabled = !checkoutCheckbox.checked;
    });

    // Function to handle the checkout button click event
    checkoutBtn.addEventListener("click", function () {
        // Check if the checkbox is checked
        if (checkoutCheckbox.checked) {
            // Redirect to the checkout page
            window.location.href = "checkout.php";
        }
    });
});

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

// Show confirmation dialog
if (confirm("Are you sure you want to delete this item?")) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Parse the JSON response
                var response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            } else {
                alert("Error deleting item.");
            }
        }
    };

    xhr.open("POST", "delete_item.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("item_id=" + itemID);
}
}
});
});