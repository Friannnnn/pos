<?php
include 'dbcon.php';
session_start();

if (!isset($_SESSION['cashierName']) || !isset($_SESSION['cashierCode'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['productPhoto'])) {
        $productName = $_POST['productName'];
        $productPrice = $_POST['productPrice'];
        $productCategory = $_POST['productCategory'];
        $productDescription = $_POST['productDescription'];
        $productPhoto = $_FILES['productPhoto'];

        $uploadDirectory = 'uploads/';
        $uploadFile = $uploadDirectory . basename($productPhoto['name']);

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        if (move_uploaded_file($productPhoto['tmp_name'], $uploadFile)) {
            
            $stmt = $conn->prepare("INSERT INTO products (name, price, category_id, description, photo) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sdiss", $productName, $productPrice, $productCategory, $productDescription, $uploadFile);

            if ($stmt->execute()) {
                echo "New product added successfully";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "Failed to upload file.";
        }
    } else {
        echo "Product photo is required.";
    }
    exit();
}
?>


            
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
            
                .center-container {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                }
            
                .form-container {
                    width: 100%;
                    max-width: 400px;
                }
            
                .form-label {
                    color: black;
                }
            
                .form-control {
                    border: 1px solid #d2a56c;
                    border-radius: 5px;
                }
            
                .form-select {
                    border: 1px solid #d2a56c;
                    border-radius: 5px;
                }
            
                h2 {
                    font-family: 'Inter', sans-serif;
                    font-weight: 700;
                }
            </style>
<div class="container mt-5">
    <div class="center-container">
        <div class="form-container">
            <h2 class="text-center mb-4">Add New Product</h2>
            <form id="addProductForm" enctype="multipart/form-data">
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
                        
                        $result = $conn->query("SELECT id, name FROM categories");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No categories found</option>';
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="productDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="productDescription" name="productDescription" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="productPhoto" class="form-label">Product Photo</label>
                    <input type="file" class="form-control" id="productPhoto" name="productPhoto" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>
</div>

<script>
    $('#addProductForm').submit(function(event) {
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
                $('#addProductForm')[0].reset();
            }
        });
    });
</script>