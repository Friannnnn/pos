<?php
include 'dbcon.php'; 

$query = "SELECT * FROM products"; 
$result = mysqli_query($conn, $query);

$products = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <title>Home</title>
    <style>
        body {
            font-family: Inter;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-color: #F5F4F2;
            color: #241F1D;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 960px;
            margin: 0 auto;
            display: flex;
            padding: 20px;
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
        }
    </style>
    <script>
        function showAlert(productName) {
            alert("Product clicked: " + productName);
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
                            <li><a class="dropdown-item" href="#">Coffee</a></li>
                            <li><a class="dropdown-item" href="#">Frappe</a></li>
                            <li><a class="dropdown-item" href="#">Pasta</a></li>
                        </ul>
                    </div>
                    <form class="d-flex">
                        <input class="form-control me-2 search-bar" type="search" placeholder="Search Product" aria-label="Search">
                            </form>
                        </div>
                    </div>
        <div class="product-list">
                            <?php foreach ($products as $product): ?>
                    <div class="product-card" onclick="showAlert('<?php echo $product['name']; ?>')">
                        <img src="<?php echo $product['photo']; ?>" alt="<?php echo $product['name']; ?>">
                    <div class="product-name"><?php echo $product['name']; ?></div>
                            <div class="product-price">₱<?php echo number_format($product['price'], 2); ?></div>
                        </div>
                            <?php endforeach; ?>
                    </div>


                </div>
            <div class="ordered-list-container">
                <h4>Ordered List</h4>
                <ul class="ordered-list">
                    </ul>
            </div>
        </div>

</body>
</html>