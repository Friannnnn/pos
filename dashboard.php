<?php
session_start();

if (!isset($_SESSION['cashierName']) || !isset($_SESSION['cashierCode'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['terminal_report_generated'])) {
    if ($_SESSION['cashierCode'] == 'admin') {
        $_SESSION['terminal_report_generated'] = true;
    } else {
        $_SESSION['terminal_report_generated'] = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Inter;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-color: #F5F4F2;
            color: black;
        }

        .container-fluid {
            position: relative;
        }

        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #EEECDA;
            padding-top: 1rem;
            display: flex;
            flex-direction: column;
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
            margin-left: 210px;
            padding: 20px;
            flex: 1;
            color: #000;
            max-width: 800px;
            margin: 0 auto;
        }

        .profile {
            padding: 10px;
        }

        .dropdown-menu {
            background-color: #D2AC67;
        }

        .dropdown-item {
            color: #fff !important;
        }

        .dropdown-item:hover {
            background-color: #8B4513 !important;
        }

        .disabled-link {
            pointer-events: none;
            color: grey;
        }
    </style>
</head>

<body>
    <div class="containerr">
        <div class="row">
            <div class="col-md-4">
                <div class="sidebar">
                    <div class="containerr">
                        <span class="brand-icon"><i class="bi bi-shop"></i></span>
                        <span class="brand-name">Blend n' Sip</span>
                    </div>

                    <ul class="nav flex-column">
                        <br><br>
                        <?php
                        if ($_SESSION['cashierCode'] == 'admin') {
                            echo '
                            <li class="nav-item sidelink">  
                                <a class="nav-link linkcol main-content-load" href="cashiers.php">Cashiers</a>
                            </li>
                            <li class="nav-item sidelink">
                                <a class="nav-link linkcol main-content-load" href="dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item sidelink">
                                <a class="nav-link linkcol main-content-load" href="add_products.php">Products</a>
                            </li>
                            <li class="nav-item sidelink">
                                <a class="nav-link linkcol main-content-load" href="#">Inventory</a>
                            </li>
                            ';
                        } else {
                            echo '
                            <li class="nav-item sidelink">  
                                <a class="nav-link linkcol main-content-load ' . ($_SESSION['terminal_report_generated'] ? '' : 'disabled-link') . '" id="sidebar-home" href="home.php">Home</a>
                            </li>
                            <li class="nav-item sidelink">
                                <a class="nav-link linkcol main-content-load ' . ($_SESSION['terminal_report_generated'] ? '' : 'disabled-link') . '" href="sales.php">Sales</a>
                            </li>';
                        }

                        if ($_SESSION['terminal_report_generated'] == false) {
                            echo '<li class="nav-item">  
                                <a class="nav-link linkcol" href="terminal_report.php">Terminal Report</a>
                            </li>';
                            echo ("<script>
                                window.onload = function() {
                                    let text = 'Print a terminal report first!';
                                    confirm(text);
                                }
                                </script>");
                        }
                        ?>
                    </ul>

                    <div class="profile mt-auto">
                        <div class="containerr">
                            <div class="nav-link profile dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown">
                                <small><?php echo $_SESSION['cashierCode']; ?></small>
                            </div>
                            <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                <?php 
                                    if ($_SESSION['cashierCode'] == 'admin') {
                                        echo '<li><a class="dropdown-item" id="cashier" href="#" data-bs-toggle="modal" data-bs-target="#cashierModal">Cashiers</a></li>';
                                    }
                                ?>
                                <li><form method="post"><button class="dropdown-item" type="submit" name="logout">Logout</button></form></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="containerr main-content">
            <script>
                $(".main-content-load").click(function(event) {
                    event.preventDefault();
                    var id = $(this).attr("href");
                    $(".main-content").load(id);
                });
            </script>
        </div>

        <div class="modal fade" id="cashierModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerModalLabel">Cashiers Registered</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-table">
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </div>
</body>

</html>
