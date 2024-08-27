<?php
include 'dbcon.php';
session_start();

if (!isset($_SESSION['cashierName']) || !isset($_SESSION['cashierCode'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'addMaterial') {
        $materialName = $_POST['materialName'];
        $originalPrice = $_POST['originalPrice'];
        $supplierName = $_POST['supplierName'];
        $quantity = $_POST['quantity'];
        $stockedDate = $_POST['stockedDate'];
        $expiryDate = $_POST['expiryDate'];

        $stmt = $conn->prepare("INSERT INTO raw_materials (name, original_price, supplier_name, quantity, stocked_date, expiry_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $materialName, $originalPrice, $supplierName, $quantity, $stockedDate, $expiryDate);

        if ($stmt->execute()) {
            $response = [
                'status' => 'success',
                'data' => [
                    'material_name' => $materialName,
                    'original_price' => $originalPrice,
                    'supplier_name' => $supplierName,
                    'quantity' => $quantity,
                    'stocked_date' => $stockedDate,
                    'expiry_date' => $expiryDate
                ]
            ];

        } else {
            $response = ['status' => 'error', 'message' => $stmt->error];
        }

        $stmt->close();
        echo json_encode($response);
        exit();
    } elseif ($_POST['action'] == 'getWastages') {
        $query = "
            SELECT material_name, original_price, supplier_name, quantity, expiry_date
            FROM wastages
        ";
        $result = $conn->query($query);

        $wastages = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $wastages[] = $row;
            }
        }

        echo json_encode($wastages);
        exit();
    }
}

$moveExpiredQuery = "
    INSERT INTO wastages (material_name, original_price, supplier_name, quantity, stocked_date, expiry_date)
    SELECT raw_materials.name, raw_materials.original_price, raw_materials.supplier_name, raw_materials.quantity, raw_materials.stocked_date, raw_materials.expiry_date
    FROM raw_materials
    WHERE raw_materials.expiry_date < CURDATE();
";
$conn->query($moveExpiredQuery);

$deleteExpiredQuery = "
    DELETE FROM raw_materials 
    WHERE expiry_date < CURDATE();
";
$conn->query($deleteExpiredQuery);

$query = "
    SELECT raw_materials.name AS material_name, raw_materials.original_price, raw_materials.supplier_name, raw_materials.quantity, raw_materials.stocked_date, raw_materials.expiry_date
    FROM raw_materials
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #F5F4F2; 
            color: black; 
        }

        .container { 
            background-color: #EEECDA; 
            padding: 20px; 
            border-radius: 10px; 
            margin-top: 50px; 
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

        .table-container { 
            width: 100%; 
            max-width: 800px; 
        }
        .form-label { 
            color: black;
        }
        h2 { 
            font-family: 'Inter', sans-serif; 
            font-weight: 700; 
        }

        table { 
            margin-top: 20px; 
            width: 100%; 
            border-collapse: collapse; 
        }
        
        th { 
            background-color: #d2a56c; 
            color: black; 
            padding: 8px; 
        }

        td { 
            padding: 8px; 
            border: 1px solid #d2a56c; 
        }

        .nav-link { 
            color: black; 
            padding: 12px; 
            border-radius: 5px; 
            text-decoration: none; 
        }
        
        .nav-link:hover, .nav-link:focus { 
            color: black; 
            background-color: #d2a56c; 
        }

        .dropdown-menu { 
            background-color: #D2AC67; 
        }

        .button-container { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 20px; 
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="center-container">
            <div class="table-container">
                <h2 class="text-center mb-4">Inventory</h2>
                <div class="button-container">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">Add Raw Material</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#wastagesModal">Wastages</button>
                </div>
                <div id="message"></div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Raw Material Name</th>
                            <th>Original Price</th>
                            <th>Supplier Name</th>
                            <th>Quantity</th>
                            <th>Stocked Date</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['material_name']) . "</td>
                                        <td>" . htmlspecialchars($row['original_price']) . "</td>
                                        <td>" . htmlspecialchars($row['supplier_name']) . "</td>
                                        <td>" . htmlspecialchars($row['quantity']) . "</td>
                                        <td>" . htmlspecialchars($row['stocked_date']) . "</td>
                                        <td>" . htmlspecialchars($row['expiry_date']) . "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No data found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMaterialModalLabel">Add Raw Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addMaterialForm">
                        <div class="mb-3">
                            <label for="materialName" class="form-label">Material Name</label>
                            <input type="text" class="form-control" id="materialName" name="materialName" required>
                        </div>
                        <div class="mb-3">
                            <label for="originalPrice" class="form-label">Original Price</label>
                            <input type="number" class="form-control" id="originalPrice" name="originalPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="supplierName" class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" id="supplierName" name="supplierName" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="stockedDate" class="form-label">Stocked Date</label>
                            <input type="date" class="form-control" id="stockedDate" name="stockedDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="expiryDate" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" id="expiryDate" name="expiryDate" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Material</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="wastagesModal" tabindex="-1" aria-labelledby="wastagesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="wastagesModalLabel">Wastages</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Material Name</th>
                                <th>Original Price</th>
                                <th>Supplier Name</th>
                                <th>Quantity</th>
                                <th>Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody id="wastagesTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addMaterialForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: 'inventory.php',
                    type: 'POST',
                    data: $(this).serialize() + '&action=addMaterial',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#addMaterialModal').modal('hide');
                            $('#addMaterialForm')[0].reset();

                            const newRow = `
                                <tr>
                                    <td>${response.data.material_name}</td>
                                    <td>${response.data.original_price}</td>
                                    <td>${response.data.supplier_name}</td>
                                    <td>${response.data.quantity}</td>
                                    <td>${response.data.stocked_date}</td>
                                    <td>${response.data.expiry_date}</td>
                                </tr>
                            `;
                            $('#inventoryTableBody').append(newRow);
                        } else {
                            $('#message').html('<div class="alert alert-danger">An error occurred: ' + response.message + '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#message').html('<div class="alert alert-danger">An error occurred: ' + error + '</div>');
                    }
                });
            });

            $('#wastagesModal').on('show.bs.modal', function () {
                $.ajax({
                    url: 'inventory.php',
                    type: 'POST',
                    data: { action: 'getWastages' },
                    dataType: 'json',
                    success: function(data) {
                        let wastagesTableBody = $('#wastagesTableBody');
                        wastagesTableBody.empty();
                        if (data.length > 0) {
                            data.forEach(function(item) {
                                wastagesTableBody.append(`
                                    <tr>
                                        <td>${item.material_name}</td>
                                        <td>${item.original_price}</td>
                                        <td>${item.supplier_name}</td>
                                        <td>${item.quantity}</td>
                                        <td>${item.expiry_date}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            wastagesTableBody.append('<tr><td colspan="5">No wastages found</td></tr>');
                        }
                    }
                    
                });
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
