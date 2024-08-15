<?php
session_start();    
require('fpdf/fpdf.php');
include 'dbcon.php';

class TerminalReport extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Blend n Sip CoffeeBar', 0, 1, 'C');
        
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, 'Terminal Report', 0, 1, 'C');
        
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$closingCashier = isset($_SESSION['closing_cashier']) && $_SESSION['closing_cashier'];

// Initialize PDF
$pdf = new TerminalReport('P', 'mm', array(80, 200));  
$pdf->AddPage();



$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, 'Transactions:', 0, 1);

$queryTransactions = "SELECT * FROM transactions ORDER BY transaction_date DESC";
$resultTransactions = mysqli_query($conn, $queryTransactions);

if (mysqli_num_rows($resultTransactions) > 0) {
    while ($row = mysqli_fetch_assoc($resultTransactions)) {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 10, 'Transaction ID: ' . $row['id'], 0, 1);
        $pdf->Cell(0, 10, 'Total Amount: P' . $row['total_amount'], 0, 1);
        $pdf->Cell(0, 10, 'Payment Method: ' . $row['payment_method'], 0, 1);
        $pdf->Cell(0, 10, 'Transaction Date: ' . $row['transaction_date'], 0, 1);
        $pdf->Ln(5); // Line break
    }
} else {
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'No transactions found.', 0, 1);
}

$pdf->Ln(10); // Line break

// Display wastages
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, 'Wastages:', 0, 1);

$queryWastages = "SELECT * FROM wastages ORDER BY stocked_date DESC";
$resultWastages = mysqli_query($conn, $queryWastages);

if (mysqli_num_rows($resultWastages) > 0) {
    while ($row = mysqli_fetch_assoc($resultWastages)) {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 10, 'Material Name: ' . $row['material_name'], 0, 1);
        $pdf->Cell(0, 10, 'Quantity: ' . $row['quantity'], 0, 1);
        $pdf->Cell(0, 10, 'Stocked Date: ' . $row['stocked_date'], 0, 1);
        $pdf->Cell(0, 10, 'Expiry Date: ' . $row['expiry_date'], 0, 1);
        $pdf->Ln(5); // Line break
    }
} else {
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'No wastages found.', 0, 1);
}

// Output PDF as inline (I) to the browser with filename 'terminal_report.pdf'
$pdf->Output('I', 'terminal_report.pdf');

// Clear transactions if closing cashier
if ($closingCashier) {
    $queryDeleteTransactions = "DELETE FROM transactions";
    mysqli_query($conn, $queryDeleteTransactions);
}

mysqli_close($conn);
?>
