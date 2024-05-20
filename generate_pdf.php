<?php
require('fpdf.php');

// Create PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'This is the content of the terminal report.',0,1);

// Output PDF directly to the browser
$pdf->Output('D', 'terminal_report.pdf');
?>
