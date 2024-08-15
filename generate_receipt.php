<?php
require('fpdf/fpdf.php');
// Include database connection
include 'dbcon.php';

// Retrieve ordered items and payment method from POST
$orderedItems = json_decode($_POST['orderedItems'], true);
$paymentMethod = $_POST['paymentMethod'];

// Calculate subtotal
$subtotal = 0;
foreach ($orderedItems as $item) {
    $subtotal += $item['price'];
}

// Insert into transactions table
$date = date('Y-m-d H:i:s'); // Current date and time
$query = "INSERT INTO transactions (ordered_items, total_amount, payment_method, transaction_date) 
          VALUES ('" . mysqli_real_escape_string($conn, json_encode($orderedItems)) . "', $subtotal, '$paymentMethod', '$date')";
mysqli_query($conn, $query);

// Retrieve the last inserted transaction ID
$transactionId = mysqli_insert_id($conn);


class ThermalReceipt extends FPDF {
    function Header() {
        // Company name
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Blend n Sip CoffeeBar', 0, 1, 'C');
        
        // Receipt title
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, 'Receipt', 0, 1, 'C');
        
        // Line break
        $this->Ln(5);
    }

    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Retrieve ordered items and payment method from POST
$orderedItems = json_decode($_POST['orderedItems'], true);
$paymentMethod = $_POST['paymentMethod'];

// Calculate the total
$subtotal = 0;
foreach ($orderedItems as $item) {
    $subtotal += $item['price'];
}

// Create PDF
$pdf = new ThermalReceipt('P', 'mm', array(80, 200));  // 80mm wide, length can vary
$pdf->AddPage();

// Adding receipt items
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, '  Item                Price', 0, 1);
foreach ($orderedItems as $item) {
    $pdf->Cell(0, 10, sprintf('%-15s P%5.2f', $item['name'], $item['price']), 0, 1);
}

// Subtotal, Total
$pdf->Cell(0, 10, '', 0, 1);  
$pdf->Cell(0, 10, sprintf('Total:             P%.2f', $subtotal), 0, 1, 'R');

// Payment Method
$pdf->Cell(0, 10, '', 0, 1);  
$pdf->Cell(0, 10, 'Payment Method: ' . $paymentMethod, 0, 1, 'L');

// Output PDF as inline (I) to the browser with filename 'receipt.pdf'
$pdf->Output('I', 'receipt.pdf');
?>
