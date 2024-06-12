<?php
session_start();
include("dbcon.php");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    if (!empty($_POST['cashierCode']) && !empty($_POST['password'])) {
        $_POST['cashierCode'] = filter_var($_POST['cashierCode'], FILTER_SANITIZE_SPECIAL_CHARS);
        
        $cashierCode = $_POST['cashierCode'];
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM cashiers WHERE generated_code = '$cashierCode' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $_SESSION['cashierName'] = $row['cashierName'] ;
            $_SESSION['cashierCode'] = $cashierCode;

            header("Location: dashboard.php");
             exit();
        } else {
            echo "<script>alert('Cashier code or password is incorrect. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Please enter both cashier code and password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> POS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
  <style> 
    body {
      background-image: url("./assets/beans.svg");
      background-size: cover;
      background-repeat: no-repeat;
      font-family: 'Inter', sans-serif;
      height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
    }
    .brand {
      display: flex;
      align-items: center;
      padding: 20px;
    }
    .brand-icon {
      font-size: 2rem;
      color: #CD853F;
    }
    .brand-name {
      font-size: 1.5rem;
      margin-left: 5px;
      color: #CD853F;
    }
    .login-container {
      border-radius: 8px;
      padding: 30px;
      max-width: 400px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      align-self: center;
    }
    .btn-primary {
      background-color: #CD853F;
      border-color: #CD853F;
    }
    .btn-primary:hover {
      background-color: #8B4513;
      border-color: #8B4513;
    }
    .btn-primary:focus {
      outline: none;
    }
    .form-control {
      background: none;
      border: none;
      border-bottom: 1px solid #000;
      color: #000;
      position: relative;
      transition: background-color 0.3s;
    }
    .form-control:focus {
      border-color: #CD853F;
      background-color: transparent;
    }
    .form-control:not(:placeholder-shown) ~ .form-label {
      transform: translateY(-20px) scale(0.8);
      color: #CD853F;
    }
    
    #start {
      background: none;
      border: none;
      border-bottom: 1px solid #000;
      color: #000;
      position: relative;
      transition: background-color 0.3s;
    }

    #start:focus {
      border-color: #CD853F;
      background-color: transparent;
    }
    #start:not(:placeholder-shown) ~ .form-label {
      transform: translateY(-20px) scale(0.8);
      color: #CD853F;
    }

    
    .form-label {
      position: absolute;
      left: 0;
      bottom: 7px;
      transition: all 0.3s;
    }
    .modal-content {
      background-color: #fff;
      color: #000;
    }
    .modal-header {
      border-bottom: 1px solid #000;
    }
    .modal-footer {
      border-top: 1px solid #000;
    }
    .modal-header .btn-close {
      color: #000 !important;
    }

    a {
      color: #CD853F;
    }

  </style>
</head>
<body>

  <div class="brand">
    <span class="brand-icon"><i class="bi bi-shop"></i></span>
    <span class="brand-name">Blend 'n Sip Coffeebar</span>
  </div><br>
  

  <div class="login-container">
    <h1 class="text-center mb-4">Welcome!</h1>  <br>
    <form class="mb-3" method="post">
      <div class="mb-3 position-relative">
        <input type="text" class="form-control" id="cashierCode" name="cashierCode" required>
        <label class="form-label" for="cashierCode">Cashier Code</label>
      </div> <br>
      <div class="mb-3 position-relative">
        <input type="password" class="form-control" id="password" name="password" required>
        <label class="form-label" for="password">Password</label>
      </div>
      <button type="submit"  class="btn btn-primary w-100" name="login">Login</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
