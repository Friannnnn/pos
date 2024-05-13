<?php
session_start();

if (!isset($_SESSION['cashierName']) || !isset($_SESSION['cashierCode'])) {
    header("Location: index.php");
    exit();
}
    /* 
    if ($_SESSION['cashierCode'] == 'admin') {
        echo "admin is here";
    }
    */
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
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
    <style>
       
        body {
            display: absolute;
            min-height: 100vh;
            flex-direction: column;
            background-color: #F5F4F2; 
            color: black; 
            transition: margin-left 0.3s ease; 
        }

        .container-fluid {
            position: relative;
            right:7px;
        }


        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 0;
            background-color: #EEECDA;
            padding-top: 1rem;
            transition: 0.3s ; 
        }  

        .nav-link {
            color: black;
            padding: 12px;
            border-radius:5px;
        }

        .nav-link:hover {
            color: black;
            border-radius: 5px;
            background-color: #d2a56c;
        }

        .nav-link:focus {
            color: black;
            border-radius: 5px;
            background-color: #d2a56c;
        }

        .main-content {
            margin-left: 0; 
            padding: 20px;
            flex: 1;
            color: #fff; 
        }

        .profile {
            padding: 10px;
            cursor: pointer;
            position: relative;
            top:420px;
        }

        .profile:hover {
            border-radu
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

        
    </style>
</head>

<body>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">

                <div class="sidebar">
                    <div class="row ">
                        <div class="col">
                            <div class="container-fluid ">
                                <span class="brand-icon"><i class="bi bi-shop"></i></span>
                                <span class="brand-name">OverDose</span>
                            </div>
                        </div>
                    </div>

                    <ul class="nav flex-column">
                        
                        <br> <br>
                        <li class="nav-item">  
                            
                            <a class="nav-link linkcol" href="#">Home</a>
                            
                            <!-- kada new session prompt ng terminal report -->
                            <!-- no terminal report no transac -->
                            
                            <!-- keep color palette -->

                            <!-- gutter for main content-->
                            <!-- one sidebar -->
                            
                            <!-- change dashboard depending if admin-->
                            <!-- -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link linkcol" href="#">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            
                            <div class="profile dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown">
                                <small ><?php echo $_SESSION['cashierCode']; ?></small>
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
        <div class="row">
            <div class="col-md-12"> 
                <a id="welcomeNote" > Hello, I'm <?php  echo $_SESSION['cashierName']; ?>!</a>
            </div>
                
        </div>
    </div>


  <div class="modal fade" id="cashierModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registerModalLabel">Cashiers Registered</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
                                    
</body>

</html>
