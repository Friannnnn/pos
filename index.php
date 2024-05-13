<?php
session_start();
include("dbcon.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    if (!empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $codeLength = 8;
        $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $generatedCode = "";
        for ($i = 0; $i < $codeLength; $i++) {
            $generatedCode .= $charset[rand(0, strlen($charset) - 1)];
        }

        $sql = "INSERT INTO cashiers (first_name, last_name, email, password, generated_code) VALUES ('$firstName', '$lastName', '$email', '$password', '$generatedCode')";
        $conn->query($sql);
        
        $_SESSION['cashierName'] = $firstName . " " . $lastName;
        $_SESSION['cashierCode'] = $generatedCode;

        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Please fill all the fields for registration.');</script>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    if (!empty($_POST['cashierCode']) && !empty($_POST['password'])) {
        $_POST['cashierCode'] = filter_var($_POST['cashierCode'], FILTER_SANITIZE_SPECIAL_CHARS);
        
        $cashierCode = $_POST['cashierCode'];
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM cashiers WHERE generated_code = '$cashierCode' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $_SESSION['cashierName'] = $row['first_name'] . " " . $row['last_name'];
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
    #register {
      text-decoration: none;
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
    <span class="brand-name">OverDose Coffeebar</span>
  </div><br>
  <br>
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
    <hr>
    <p class="mb-0">Haven't registered yet? <a id="register" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register Now</a></p>
  </div>

  <!-- new registration add shift time -->


  <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registerModalLabel">Register</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="mb-3" method="post">
            <div class="mb-3 position-relative"> <br>
              <input type="text" class="w-30 form-control" id="firstName" name="firstName" required>
              <label class="form-label" for="firstName" >First Name</label>
            </div> <br>
            <div class="mb-3 position-relative">
              <input type="text" class="w-30 form-control" id="lastName" name="lastName" required>
              <label class="form-label" for="lastName">Last Name</label>
            </div> <br>
            <div class="mb-3 position-relative">
              <input type="email" class="form-control" id="email" name="email" required>
              <label class="form-label" for="email">Email</label>
            </div> <br>
            <div class="mb-3 position-relative">
              <input type="password" class="form-control" id="password" name="password" required>
              <label class="form-label" for="password">Password</label>
            </div> <br>
            <div class=" position-relative">
              <input type="time" id="start" name="shift" >
              <input type="time" id="start" name="shift" >
              <label for="shift" class="form-label">Shift (Start - End)</label> 
            </div> <br>
            <button type="submit" class="btn btn-primary w-100" name="register" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#cashierCodeModal">Register</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
