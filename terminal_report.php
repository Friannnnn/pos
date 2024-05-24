<?php
    session_start();

    if (isset($_POST['done'])) {
        $_SESSION['terminal_report_generated'] = true;
        header("Location: dashboard.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .btn-primary:disabled {
            background-color: #6c757d;
            border-color: #6c757d;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#doneBtn').prop('disabled', true);
            
            $('#printBtn').click(function() {
                $('#doneBtn').prop('disabled', false);
            });
        });
    </script>
</head>
<body>

    <div class="container">
        <h1 class="mb-4">Terminal Report</h1>
        <form action="terminal_report.php" method="post">
            <input id="doneBtn" class="btn btn-primary" type="submit" name="done" value="Done">
            <a id="printBtn" class="btn btn-primary ms-2" target="_blank" href="./generate_pdf.php">Print</a>
        </form>
        <hr>
        
    </div>

</body>
</html>
