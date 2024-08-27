<?php
require('fpdf/fpdf.php');
include 'dbcon.php';
session_start();    
$current_cashier = $_SESSION["cashierName"];
$orderedItems = json_decode($_POST['orderedItems'], true);
$paymentMethod = $_POST['paymentMethod'];

$subtotal = 0;
foreach ($orderedItems as $item) {
    $subtotal += $item['price'];
}

$date = date('Y-m-d H:i:s'); 
$query = "INSERT INTO transactions (ordered_items, total_amount, payment_method, transaction_date) 
          VALUES ('" . mysqli_real_escape_string($conn, json_encode($orderedItems)) . "', $subtotal, '$paymentMethod', '$date')";
mysqli_query($conn, $query);

$admin_query = "INSERT INTO admin_transactions (ordered_items, total_amount, payment_method, transaction_date, cashier_name) 
                VALUES ('" . mysqli_real_escape_string($conn, json_encode($orderedItems)) . "', $subtotal, '$paymentMethod', '$date', '$current_cashier' )";
mysqli_query($conn, $admin_query);

$transactionId = mysqli_insert_id($conn);

class ThermalReceipt extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Blend n Sip CoffeeBar', 0, 1, 'C');
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, 'Receipt', 0, 1, 'C');
        
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new ThermalReceipt('P', 'mm', array(80, 200));  
$pdf->AddPage();

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, '  Item                Price', 0, 1);
foreach ($orderedItems as $item) {
    $pdf->Cell(0, 10, sprintf('%-15s P%5.2f', $item['name'], $item['price']), 0, 1);
}

$pdf->Cell(0, 10, '', 0, 1);  
$pdf->Cell(0, 10, sprintf('Total:             P%.2f', $subtotal), 0, 1, 'R');

$pdf->Cell(0, 10, '', 0, 1);  
$pdf->Cell(0, 10, 'Payment Method: ' . $paymentMethod, 0, 1, 'L');

$pdf->Output('I', 'receipt.pdf');
?>
