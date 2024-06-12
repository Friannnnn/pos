<?php
include 'dbcon.php';

$query = "SELECT * FROM products ORDER BY category_id";
$result = mysqli_query($conn, $query);

$products = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
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
            flex-direction: column;
            background-color: #F5F4F2;
            color: #241F1D;
            margin: 0;
        }

        .container {
            width: 960px;
            margin: 0 auto;
            display: flex;
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
            flex: 1;
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
            width: 100%;
            height: auto;
            max-height: 200px;
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
            height: 300px; 
            overflow-y: auto; 
        }

        .ordered-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
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

        .myModal {
            display: none;
        }

        .mt-auto {
            margin-top: 300px !important;
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

        .btn-check:checked + .btn {
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
    </style>
    <script>
        let selectedProduct = {};

        function showAlert(productName, productImage, productPrice, categoryId) {
            selectedProduct = { productName, productImage, productPrice, categoryId };
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
                        <input type="checkbox" class="btn-check" id="extraShot" autocomplete="off" ${categoryId == 2 || categoryId == 3 ? 'disabled' : ''}>
                        <label class="btn btn-outline-primary" for="extraShot">Extra shot</label>

                        <input type="checkbox" class="btn-check" id="syrup" autocomplete="off" ${categoryId == 3 ? 'disabled' : ''}>
                        <label class="btn btn-outline-primary" for="syrup">Syrup</label>

                        <input type="checkbox" class="btn-check" id="breveMilk" autocomplete="off" ${categoryId == 3 ? 'disabled' : ''}>
                        <label class="btn btn-outline-primary" for="breveMilk">Breve milk</label>

                        <input type="checkbox" class="btn-check" id="whippedCream" autocomplete="off" ${categoryId == 3 ? 'disabled' : ''}>
                        <label class="btn btn-outline-primary" for="whippedCream">Whipped cream</label>
                    </div>
                </div>
            `;

            modalBody.innerHTML = `
                <p class="mt-3">Product Name: ${productName}</p>
                <p class="mt-3">Price: ₱${productPrice}</p>
                ${chipHTML}
                ${addOnsHTML}
            `;
            var myModal = new bootstrap.Modal(document.getElementById('myModal'), {});
            myModal.show();
        }

        function toggleAddOns(enable) {
            document.getElementById('syrup').disabled = !enable;
            document.getElementById('whippedCream').disabled = !enable;
        }

        function filterProducts() {
            var input, filter, productCards, productName, i, txtValue;
            input = document.getElementById('searchBar');
            filter = input.value.toUpperCase();
            productCards = document.getElementsByClassName('product-card');

            for (i = 0; i < productCards.length; i++) {
                productName = productCards[i].getElementsByClassName('product-name')[0];
                txtValue = productName.textContent || productName.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    productCards[i].style.display = "";
                } else {
                    productCards[i].style.display = "none";
                }
            }
        }

        function filterByCategory(categoryId) {
            var productCards, i, cardCategory;
            productCards = document.getElementsByClassName('product-card');

            for (i = 0; i < productCards.length; i++) {
                cardCategory = productCards[i].getAttribute('data-category-id');
                if (cardCategory == categoryId || categoryId == 'all') {
                    productCards[i].style.display = "";
                } else {
                    productCards[i].style.display = "none";
                }
            }
        }

        function addItemToOrder() {
            const orderedList = document.querySelector('.ordered-list');
            const item = document.createElement('li');
            item.innerHTML = `
                ${selectedProduct.productName} - ₱${selectedProduct.productPrice}
                <span class="action-btn" onclick="editItem(this)">Edit</span>
                <span class="action-btn" onclick="deleteItem(this)">Delete</span>
            `;
            orderedList.appendChild(item);
            const myModal = bootstrap.Modal.getInstance(document.getElementById('myModal'));
            myModal.hide();
        }

        function editItem(element) {
            const item = element.parentElement;
            const itemDetails = item.firstChild.textContent.split(' - ');
            selectedProduct.productName = itemDetails[0];
            selectedProduct.productPrice = itemDetails[1].replace('₱', '');
            showAlert(selectedProduct.productName, '', selectedProduct.productPrice, selectedProduct.categoryId);
            item.remove();
        }

        function deleteItem(element) {
            const item = element.parentElement;
            item.remove();
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="main-content">
            <div class="row mb-3">
                <div class="col-md-12 d-flex align-items-center justify-content-between">
                    <div class="dropdown">
                        <button class="category-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Category
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="filterByCategory('all')">All</a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterByCategory(1)">Coffee</a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterByCategory(2)">Frappe</a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterByCategory(3)">Pasta</a></li>
                        </ul>
                    </div>
                    <form class="d-flex">
                        <input id="searchBar" class="form-control me-2 search-bar" type="search" placeholder="Search Product" aria-label="Search" onkeyup="filterProducts()">
                    </form>
                </div>
            </div>
            <div class="product-list">
                <?php foreach ($products as $product): ?>
                <div class="product-card" data-category-id="<?php echo $product['category_id']; ?>" onclick="showAlert('<?php echo $product['name']; ?>', '<?php echo $product['photo']; ?>', '<?php echo number_format($product['price'], 2); ?>', '<?php echo $product['category_id']; ?>')">
                    <img src="<?php echo $product['photo']; ?>" alt="<?php echo $product['name']; ?>">
                    <div class="product-name"><?php echo $product['name']; ?></div>
                    <div class="product-price">₱<?php echo number_format($product['price'], 2); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="ordered-list-container">
            <h4>Ordered List</h4>
            <ul class="ordered-list"></ul>
        </div>


        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBody">
                        Modal body text goes here.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="addItemToOrder()">Add Item</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
