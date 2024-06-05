<?php
include 'dbcon.php';
session_start();

if (!isset($_SESSION['cashierName']) || !isset($_SESSION['cashierCode'])) {
    header("Location: index.php");
    exit();
}

// Move expired items to wastages table
$moveExpiredQuery = "
    INSERT INTO wastages (material_name, original_price, supplier_name, quantity, stocked_date, expiry_date)
    SELECT raw_materials.name, raw_materials.original_price, raw_materials.supplier_name, raw_materials.quantity, raw_materials.stocked_date, raw_materials.expiry_date
    FROM raw_materials
    WHERE raw_materials.expiry_date < CURDATE();
";

$conn->query($moveExpiredQuery);

// Delete expired items from raw_materials table
$deleteExpiredQuery = "
    DELETE FROM raw_materials 
    WHERE expiry_date < CURDATE();
";

$conn->query($deleteExpiredQuery);

// Select updated inventory
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        .nav-link:hover,
        .nav-link:focus {
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
                    <a href="add_raw_material.php" class="btn btn-primary">Add Raw Material</a>
                    <a href="wastages.php" class="btn btn-primary">Wastages</a>
                </div>
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
                    <tbody>
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
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
