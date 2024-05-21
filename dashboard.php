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

    if (!isset($_SESSION['terminal_report_generated']) ) {
        if ($_SESSION['cashierCode'] == 'admin') {
            $_SESSION['terminal_report_generated'] = true;
            echo '<script> console.log("admin")</script>';
            
        } else {
            echo '<script> console.log("cashier")</script>';
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
            margin-left: 210px; /* Adjusted to fit sidebar */
            padding: 20px;
            flex: 1;
            color: #000;
            max-width: 800px; /* Center the content */
            margin: 0 auto; /* Center the content */
        }

        .profile {
            padding: 10px;
            cursor: pointer;
            position: absolute;
            bottom: 20px;
        }

        .profile:hover {
            background-color: #D2AC67;
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

        #welcomeNote {
            position: absolute;
            color: black;
            top: 20px;
            right: 50px;
        }

        .linkcol {
            position: relative;
            top: 150px;
        }

        .category-btn {
            color: black;
            border-radius:5px;
            border: none;
            background: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
        }

        .category-btn:hover, .category-btn:focus {
            color: black;
            background-color: #d2a56c;
            text-decoration: none;
            border-radius:5px;
        }

        .disabled-link {
            pointer-events: none;
            color: grey;
        }

        .btn-outline-success:hover {
            color: white;
            background-color:#d2a56c;
            border-color:#d2a56c;
        }
        .btn-outline-success {
            color: #d2a56c;
            border-color:#d2a56c;
        }

        .search-bar {
            width: 300px; /* Adjust width as needed */
            margin-right: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="sidebar">
                    <div class="container-fluid">
                        <span class="brand-icon"><i class="bi bi-shop"></i></span>
                        <span class="brand-name">Blend n' Sip</span>
                    </div>

                    <ul class="nav flex-column">
                        <br> <br>
                        <?php
                            if ($_SESSION['cashierCode'] == 'admin') {
                                echo '
                                <li class="nav-item">  
                                    <a class="nav-link linkcol" href="#">Cashiers</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link linkcol" href="#">Dashboard</a>
                                </li>';
                            } else {
                                echo '
                                <li class="nav-item">  
                                    <a class="nav-link linkcol main-content-load" href="#" '.($_SESSION['terminal_report_generated']? '' : 'class="disabled-link"').'>Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link linkcol main-content-load" href="#" '.($_SESSION['terminal_report_generated'] ? '' : 'class="disabled-link"').'>Sales</a>
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
                            <li class="nav-item dropdown">
                                <div class="nav-link profile dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown">
                                    <small><?php echo $_SESSION['cashierCode']; ?></small>
                                </div>
                                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                    <?php 
                                        if ($_SESSION['cashierCode'] == 'admin') {
                                            echo '<a class="dropdown-item" id="cashier" href="#" data-bs-toggle="modal" data-bs-target="#cashierModal">Cashiers</a>';
                                        }
                                    ?>
                                    <li><form method="post"><button class="dropdown-item" type="submit" name="logout">Logout</button></form></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="container-fluid main-content">
            <?php
                if ($_SESSION['cashierCode'] != 'admin' && $_SESSION['terminal_report_generated'] == true) {
                    echo '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between mb-3">
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
                                    <button class="btn btn-outline-success" type="submit">Search</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row main-content-container">
                        <!-- Product Section (Blank) -->
                    </div>';
                }
            ?>
        </div>
                    
                <div class="modal fade" id="cashierModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="registerModalLabel">Cashiers Registered</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-table">
                                <!-- 
                                <table border="1">
                                    <tr>
                                        <th>Code</th>
                                        <th>Cashier Name</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </table>
                                -->
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $('.main-content-load').click(function(e) {
                            e.preventDefault();
                            var url = $(this).attr('href');
                            $('.main-content-container').load(url);
                        });
                    });
                </script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
                </body>
                </html>
    
