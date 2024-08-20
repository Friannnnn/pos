<?php
include 'dbcon.php';
session_start();

if (!isset($_SESSION['cashierName']) || !isset($_SESSION['cashierCode'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['productPhoto']) || isset($_POST['productId'])) {
        $productName = $_POST['productName'];
        $productPrice = $_POST['productPrice'];
        $productCategory = $_POST['productCategory'];
        $productDescription = $_POST['productDescription'];
        $productPhoto = $_FILES['productPhoto'] ?? null;
        $productId = $_POST['productId'] ?? '';

        $uploadDirectory = 'uploads/';
        $uploadFile = $productPhoto ? $uploadDirectory . basename($productPhoto['name']) : '';

        if ($productPhoto && !is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        if ($productPhoto && move_uploaded_file($productPhoto['tmp_name'], $uploadFile) || $productId != '') {
            if ($productId != '') {
                $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, category_id = ?, description = ?, photo = ? WHERE id = ?");
                $stmt->bind_param("sdissi", $productName, $productPrice, $productCategory, $productDescription, $uploadFile, $productId);
            } else {
                $stmt = $conn->prepare("INSERT INTO products (name, price, category_id, description, photo) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sdiss", $productName, $productPrice, $productCategory, $productDescription, $uploadFile);
            }

            if ($stmt->execute()) {
                echo "Product saved successfully";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Failed to upload file.";
        }
        exit();
    }

    if (isset($_POST['deleteProductId'])) {
        $deleteProductId = $_POST['deleteProductId'];
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $deleteProductId);

        if ($stmt->execute()) {
            echo "Product deleted successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['getProductDetails'])) {
        $productId = $_GET['getProductDetails'];
        $stmt = $conn->prepare("SELECT id, name, price, category_id, description, photo FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        echo json_encode($result->fetch_assoc());
        $stmt->close();
        exit();
    }

    if (isset($_GET['getProductsTable'])) {
        $query = "SELECT p.id, p.name, p.price, c.name AS category_name, p.description
                  FROM products p       
                  JOIN categories c ON p.category_id = c.id";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['price']) . '</td>';
                echo '<td>' . htmlspecialchars($row['category_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                echo '<td>
                        <button class="btn btn-warning btn-sm" onclick="editProduct(' . $row['id'] . ')">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct(' . $row['id'] . ')">Delete</button>
                      </td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5">No products found</td></tr>';
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #F5F4F2;
            color: black;
        }
        .container {
            background-color: #EEECDA;
            padding: 20px;
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #d2a56c;
            border-color: #d2a56c;
        }
        .btn-primary:hover {
            background-color: #8B4513;
            border-color: #8B4513;
        }
        .btn-warning {
            background-color: #f0ad4e;
            border-color: #f0ad4e;
        }
        .btn-warning:hover {
            background-color: #ec971f;
            border-color: #ec971f;
        }
        .btn-danger {
            background-color: #d9534f;
            border-color: #d9534f;
        }
        .btn-danger:hover {
            background-color: #c9302c;
            border-color: #c9302c;
        }
        .table {
            margin-top: 20px;
        }
        .table th {
            background-color: #d2a56c;
            color: white;
        }
        .modal-header {
            background-color: #d2a56c;
            color: white;
        }
        .modal-footer {
            background-color: #F5F4F2;
        }
        .form-control, .form-select {
            border: 1px solid #d2a56c;
            border-radius: 5px;
        }
        .modal-content {
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="text-center">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditProductModal">Add New Product</button>
        </div>
        <h2 class="text-center mb-4 mt-4">Products List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price (P)</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productsTableBody">
                <!-- Product rows will be populated here by AJAX -->
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addEditProductModal" tabindex="-1" aria-labelledby="addEditProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEditProductModalLabel">Add/Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEditProductForm" enctype="multipart/form-data">
                        <input type="hidden" id="productId" name="productId">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Product Price</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Category</label>
                            <select class="form-select" id="productCategory" name="productCategory" required>
                                <?php
                                $categoryResult = $conn->query("SELECT id, name FROM categories");
                                if ($categoryResult->num_rows > 0) {
                                    while ($row = $categoryResult->fetch_assoc()) {
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No categories found</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="productDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="productDescription" name="productDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="productPhoto" class="form-label">Product Photo</label>
                            <input type="file" class="form-control" id="productPhoto" name="productPhoto" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            updateProductsTable();

            $('#addEditProductForm').submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: 'add_products.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response);
                        $('#addEditProductModal').modal('hide');
                        updateProductsTable();
                    }
                });
            });

            window.editProduct = function(id) {
                $.ajax({
                    url: 'add_products.php',
                    type: 'GET',
                    data: { getProductDetails: id },
                    success: function(response) {
                        var product = JSON.parse(response);
                        $('#productId').val(product.id);
                        $('#productName').val(product.name);
                        $('#productPrice').val(product.price);
                        $('#productCategory').val(product.category_id);
                        $('#productDescription').val(product.description);
                        $('#addEditProductModal').modal('show');
                    }
                });
            }

            window.deleteProduct = function(id) {
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        type: 'POST',
                        url: 'add_products.php',
                        data: { deleteProductId: id },
                        success: function(response) {
                            alert(response);
                            updateProductsTable();
                        }
                    });
                }
            }

            function updateProductsTable() {
                $.ajax({
                    url: 'add_products.php',
                    type: 'GET',
                    data: { getProductsTable: true },
                    success: function(data) {
                        $('#productsTableBody').html(data);
                    }
                });
            }
        });
    </script>
</body>
</html>
