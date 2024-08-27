<?php
include 'dbcon.php';
session_start();

if (!isset($_SESSION['cashierName']) || !isset($_SESSION['cashierCode'])) {
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cashier Management</title>
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
        .form-label {
            color: black;
        }
        .form-control, .form-select {
            border: 1px solid #d2a56c;
            border-radius: 5px;
        }
        .shift-container {
            display: flex;
            justify-content: space-between;
        }
        .shift-container .form-control {
            width: calc(50% - 10px);
        }
        h2 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
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
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Transactions</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Cashier Name</th>
                <th scope="col">Payment Method</th>
                <th scope="col">Time</th>
                
            </tr>
        </thead>
        <tbody id="cashierTableBody">
            <?php
            $result = $conn->query("SELECT * FROM admin_transactions");
            while ($row = $result->fetch_assoc()) {
                echo "<tr data-id='{$row['id']}'>
                        <td>{$row['ordered_items']}</td>
                        <td>{$row['cashier_name']}</td>
                        <td>{$row['payment_method']}</td>
                        <td>{$row['transaction_date']}</td>
                    </tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
