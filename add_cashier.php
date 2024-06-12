<?php
include 'dbcon.php';
session_start();

if (!isset($_SESSION['cashierName']) || !isset($_SESSION['cashierCode'])) {
    header("Location: index.php");
    exit();
}

// Handle form submission to add a new cashier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if (isset($_POST['cashierName']) && isset($_POST['cashierCode']) && isset($_POST['cashierEmail']) && isset($_POST['cashierPassword']) && isset($_POST['startShift']) && isset($_POST['endShift'])) {
        $cashierName = $_POST['cashierName'];
        $cashierCode = $_POST['cashierCode'];
        $cashierEmail = $_POST['cashierEmail'];
        $cashierPassword = $_POST['cashierPassword'];
        $startShift = $_POST['startShift'];
        $endShift = $_POST['endShift'];

        $stmt = $conn->prepare("INSERT INTO cashiers (cashierName, generated_code, email, password, shift_start, shift_end, date_added) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $cashierName, $cashierCode, $cashierEmail, $cashierPassword, $startShift, $endShift);

        if ($stmt->execute()) {
            echo "New cashier added successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "All fields are required.";
    }
    exit();
}

// Handle form submission to delete a cashier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (isset($_POST['cashierId'])) {
        $cashierId = $_POST['cashierId'];

        $stmt = $conn->prepare("DELETE FROM cashiers WHERE id = ?");
        $stmt->bind_param("i", $cashierId);

        if ($stmt->execute()) {
            echo "Cashier deleted successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Cashier ID is required.";
    }
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
    <h2 class="text-center mb-4">Cashier Management</h2>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCashierModal">Add New Cashier</button>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Cashier Name</th>
                <th scope="col">Generated Code</th>
                <th scope="col">Shift Start</th>
                <th scope="col">Shift End</th>
                <th scope="col">Date Hired</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody id="cashierTableBody">
            <?php
            $result = $conn->query("SELECT id, cashierName, generated_code, shift_start, shift_end, date_added FROM cashiers");
            while ($row = $result->fetch_assoc()) {
                echo "<tr data-id='{$row['id']}'>
                        <td>{$row['cashierName']}</td>
                        <td>{$row['generated_code']}</td>
                        <td>{$row['shift_start']}</td>
                        <td>{$row['shift_end']}</td>
                        <td>{$row['date_added']}</td>
                        <td><button class='btn btn-danger btn-delete'>Fire</button></td>
                    </tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="addCashierModal" tabindex="-1" aria-labelledby="addCashierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCashierModalLabel">Add New Cashier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCashierForm">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="cashierName" class="form-label">Cashier Name</label>
                        <input type="text" class="form-control" id="cashierName" name="cashierName" required>
                    </div>
                    <div class="mb-3">
                        <label for="cashierCode" class="form-label">Cashier Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cashierCode" name="cashierCode" readonly required>
                            <button type="button" class="btn btn-primary" id="generateCode">Generate Code</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="cashierEmail" class="form-label">Cashier Email</label>
                        <input type="email" class="form-control" id="cashierEmail" name="cashierEmail" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shift</label>
                        <div class="shift-container">
                            <input type="time" class="form-control" id="startShift" name="startShift" required>
                            <input type="time" class="form-control" id="endShift" name="endShift" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="cashierPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="cashierPassword" name="cashierPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Cashier</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('generateCode').addEventListener('click', function() {
        var code = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for (var i = 0; i < 8; i++) {
            code += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        document.getElementById('cashierCode').value = code;
        alert("Make sure to copy the generated code!");
    });

    $('#addCashierForm').submit(function(event) {
        event.preventDefault();
        var startShift = document.getElementById('startShift').value;
        var endShift = document.getElementById('endShift').value;

        var startShiftTime = new Date('1970-01-01T' + startShift + 'Z');
        var endShiftTime = new Date('1970-01-01T' + endShift + 'Z');
        var openingTime = new Date('1970-01-01T07:00:00Z');
        var closingTime = new Date('1970-01-01T21:00:00Z');

        if (startShiftTime < openingTime || endShiftTime > closingTime) {
            alert('Shift times must be between 7:00 AM and 9:00 PM.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'add_cashier.php',
            data: $(this).serialize(),
            success: function(response) {
                alert(response);
                $('#addCashierForm')[0].reset();
                $('#addCashierModal').modal('hide');
                location.reload();
            }
        });
    });

    $('.btn-delete').click(function() {
        var row = $(this).closest('tr');
        var cashierId = row.data('id');

        if (confirm('Are you sure you want to delete this cashier?')) {
            $.ajax({
                type: 'POST',
                url: 'add_cashier.php',
                data: { action: 'delete', cashierId: cashierId },
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        }
    });
</script>
</body>
</html>
