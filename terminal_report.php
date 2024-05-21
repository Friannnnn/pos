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
    </style>
</head>
<body>

    <div class="container">
        <h1>Terminal Report</h1>
        <!-- Print button -->
        <form action="terminal_report.php" method="post">
            <a id="printBtn" class="btn btn-primary" href="generate_pdf.php" >Print</a>
            <input class="btn btn-primary" type="submit" name="done" value="Done">
        </form>
        <hr>
        <!-- Blank section for content -->
        <div id="terminal-report-content">
            <!-- Leave this section blank for now -->
        </div>
    </div>

</body>
</html>
