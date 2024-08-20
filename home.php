<?php
session_start();
include 'dbcon.php';
$query = "SELECT * FROM products ORDER BY category_id";
$result = mysqli_query($conn, $query);

$products = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

if (!isset($_SESSION['ordered_products'])) {
    $_SESSION['ordered_products'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = [
        'name' => $_POST['productName'],
        'price' => $_POST['productPrice']
    ];
    $_SESSION['ordered_products'][] = $product;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <title>Home</title>
    <style>
        body {
            font-family: Inter, Arial, sans-serif;
            min-height: 100vh;
            background-color: #F5F4F2;
            color: #241F1D;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 20px;
        }

        .nav-link {
            color: black;
            padding: 12px;
            border-radius: 5px;
            text-decoration: none;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: black;
            background-color: #d2a56c;
        }

        .main-content {
            flex: 3;
            padding: 20px;
            box-sizing: border-box;
        }

        .dropdown-menu {
            background-color: #D2AC67;
        }

        .dropdown-item {
            color: #FFFFFF !important;
        }

        .dropdown-item:hover {
            background-color: #8B4513 !important;
        }

        .category-btn {
            color: #241F1D;
            border-radius: 5px;
            border: none;
            background: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
        }

        .category-btn:hover,
        .category-btn:focus {
            color: #241F1D;
            background-color: #D2AC67;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-outline-success:hover {
            color: #FFFFFF;
            background-color: #D2AC67;
            border-color: #D2AC67;
        }

        .btn-outline-success {
            color: #D2AC67;
            border-color: #D2AC67;
        }

        .search-bar {
            width: 300px;
            margin-right: 10px;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-height: calc(100vh - 160px);
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #D2AC67 transparent;
        }

        .product-list::-webkit-scrollbar {
            width: 6px;
        }

        .product-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .product-list::-webkit-scrollbar-thumb {
            background-color: #D2AC67;
            border-radius: 10px;
        }

        .product-card {
            background-color: #FFFFFF;
            border-radius: 10px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .product-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-name {
            font-weight: bold;
            margin-top: 10px;
            font-size: 16px;
            text-align: center;
        }

        .product-price {
            color: #D2AC67;
            margin-top: 5px;
            font-size: 14px;
            text-align: center;
        }

        .ordered-list-container {
            background-color: #FFFFFF;
            border-radius: 10px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .ordered-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
            flex: 1;
            overflow-y: auto;
        }

        .ordered-list li {
            background-color: #F5F4F2;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-button {
            color: black;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            border: none;
        }

        .logout-button:hover,
        .logout-button:focus {
            color: black;
            background-color: #d2a56c;
        }

        .modal-body .btn-outline-primary {
            color: #D2AC67;
            border-color: #D2AC67;
        }

        .modal-body .btn-outline-primary:hover {
            background-color: #D2AC67;
            color: black;
            border-color: #D2AC67;
        }

        .btn-check:checked+.btn {
            background-color: #D2AC67;
            color: black;
            border-color: #D2AC67;
        }

        .btn-primary {
            background-color: #D2AC67;
            border-color: #D2AC67;
        }

        .btn-primary:hover {
            background-color: #8B4513;
            border-color: #8B4513;
        }

        .action-btn {
            margin-left: 10px;
            cursor: pointer;
        }

        .pay-now-container {
            width: 100%;
            text-align: center;
        }

        .pay-now-btn {
            background-color: #D2AC67;
            color: black;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .pay-now-btn:hover {
            background-color: #8B4513;
        }
    </style>
    <script>
         let selectedProduct = {};

function showAlert(productName, productImage, productPrice, categoryId, editMode = false) {
    selectedProduct = {
        productName,
        productImage,
        productPrice,
        categoryId,
        editMode
    };
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');
    modalTitle.textContent = productName;
    let chipHTML = '';
    let addOnsHTML = '';

    if (categoryId == 1) { 
        chipHTML = `
            <div class="d-flex justify-content-center mt-3">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="options" id="hotOption" autocomplete="off" onchange="toggleAddOns(false)">
                    <label class="btn btn-outline-primary" for="hotOption">Hot</label>

                    <input type="radio" class="btn-check" name="options" id="icedOption" autocomplete="off" onchange="toggleAddOns(true)">
                    <label class="btn btn-outline-primary" for="icedOption">Iced</label>
                </div>
            </div>
        `;
    }

    addOnsHTML = `
        <div class="d-flex justify-content-center mt-3">
            <div class="btn-group" role="group" aria-label="Add-ons">
                <input type="checkbox" class="btn-check" id="extraShot" autocomplete="off" ${categoryId == 2 || categoryId == 3 ? 'disabled' : ''} onchange="calculatePrice()">
                <label class="btn btn-outline-primary" for="extraShot">Extra shot (+₱20)</label>

                <input type="checkbox" class="btn-check" id="syrup" autocomplete="off" ${categoryId == 3 ? 'disabled' : ''} onchange="calculatePrice()">
                <label class="btn btn-outline-primary" for="syrup">Syrup (+₱15)</label>
            </div>
        </div>
    `;

    modalBody.innerHTML = `
        <div>
            <img src="${productImage}" alt="${productName}" class="img-fluid">
            <p class="text-center mt-3">Price: ₱<span id="productPrice">${productPrice}</span></p>
            ${chipHTML}
            ${addOnsHTML}
        </div>
    `;
    calculatePrice();
    new bootstrap.Modal(document.getElementById('productModal')).show();
}

function calculatePrice() {
    let finalPrice = selectedProduct.productPrice;
    const extraShot = document.getElementById('extraShot').checked;
    const syrup = document.getElementById('syrup').checked;

    if (extraShot) {
        finalPrice += 20;
    }

    if (syrup) {
        finalPrice += 15;
    }

    document.getElementById('productPrice').innerText = finalPrice;
}

function toggleAddOns(enable) {
    document.getElementById('extraShot').disabled = !enable;
    document.getElementById('syrup').disabled = !enable;
}

function toggleView(id) {
    var element = document.getElementById(id);
    if (element.style.display === "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}

function updateCartCount() {
    const orderedList = document.getElementById('orderedList');
    const cartCount = orderedList.children.length;
    const cartCountBadge = document.getElementById('cartCount');
    cartCountBadge.innerText = cartCount;

    const totalPriceElement = document.getElementById('totalPrice');
    let totalPrice = 0;
    for (let i = 0; i < cartCount; i++) {
        totalPrice += parseFloat(orderedList.children[i].dataset.price);
    }
    totalPriceElement.innerText = totalPrice.toFixed(2);
}

function addToCart() {
    const orderedList = document.getElementById('orderedList');
    const listItem = document.createElement('li');
    listItem.className = 'd-flex justify-content-between align-items-center';
    listItem.dataset.price = document.getElementById('productPrice').innerText;
    listItem.innerHTML = `
        ${selectedProduct.productName}
        <button class="btn btn-outline-danger btn-sm action-btn" onclick="removeFromCart(this)">Remove</button>
    `;
    orderedList.appendChild(listItem);
    updateCartCount();
}

function removeFromCart(button) {
    const listItem = button.parentNode;
    listItem.remove();
    updateCartCount();
}

        function payNow() {
            const orderedList = document.getElementById('orderedList');
            let itemsList = '';
            for (let i = 0; i < orderedList.children.length; i++) {
                itemsList += `<li>${orderedList.children[i].innerText.split('Remove')[0]}</li>`;
            }
            const totalPrice = document.getElementById('totalPrice').innerText;
            document.getElementById('payModalBody').innerHTML = `
                <p>Your total amount is ₱${totalPrice}</p>
                <ul>${itemsList}</ul>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="cash" value="cash" checked>
                    <label class="form-check-label" for="cash">Cash</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="gcash" value="gcash">
                    <label class="form-check-label" for="gcash">GCash</label>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('payModal')).show();
        }
        function confirmPayment() {
            const payModal = bootstrap.Modal.getInstance(document.getElementById('payModal'));
            payModal.hide();

            const orderedItems = [];
            const orderedList = document.getElementById('orderedList');
            for (let i = 0; i < orderedList.children.length; i++) {
                orderedItems.push({
                    name: orderedList.children[i].innerText.split('Remove')[0].trim(),
                    price: parseFloat(orderedList.children[i].dataset.price)
                });
            }

            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;

            document.getElementById('orderedItemsInput').value = JSON.stringify(orderedItems);
            document.getElementById('paymentMethodInput').value = paymentMethod;

            document.getElementById('paymentForm').submit();

            orderedList.innerHTML = '';
            updateCartCount();
        }



window.onload = function () {
    updateCartCount();
};
    </script>
</head>

    <body>
        <div class="container">
            <div class="ordered-list-container">
                <h4>Ordered List <span id="cartCount" class="badge bg-secondary">0</span></h4>
                <ul id="orderedList" class="ordered-list"></ul>
                <div class="pay-now-container">
                    <button class="pay-now-btn" onclick="payNow()">Pay Now</button>
                </div>
                <div class="mt-2 text-end">
                    <strong>Total: ₱<span id="totalPrice">0.00</span></strong>
                </div>
            </div>
            <div class="main-content">
                <h1 class="text-center">Menu</h1>
                <div class="product-list">
                    <?php foreach ($products as $product) : ?>
                        <div class="product-card"
                            onclick="showAlert('<?php echo $product['name']; ?>', '<?php echo $product['photo']; ?>', <?php echo $product['price']; ?>, <?php echo $product['category_id']; ?>)">
                            <img src="<?php echo $product['photo']; ?>" alt="<?php echo $product['name']; ?>">
                            <p class="product-name"><?php echo $product['name']; ?></p>
                            <p class="product-price">₱<?php echo $product['price']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Product Name</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBody">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="addToCart()">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="payModalLabel">Confirm Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="payModalBody">
                    </div>
                    <div class="modal-footer">
                    <form id="paymentForm" action="generate_receipt.php" method="POST" target="_blank">
    <input type="hidden" name="orderedItems" id="orderedItemsInput">
    <input type="hidden" name="paymentMethod" id="paymentMethodInput">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="button" class="btn btn-primary" onclick="confirmPayment()">Confirm Payment</button>
</form>

                    </div>
                </div>
            </div>
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>

