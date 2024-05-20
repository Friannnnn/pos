<?php
require('fpdf.php');

class PDF extends FPDF {
    // Page header
    function Header() {
        $this->SetFont('Arial','B',15);
        $this->Cell(0,10,'Terminal Report',0,1,'C');
        $this->Ln(10);
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'This is the content of the terminal report.',0,1);

// Output PDF directly to the browser
$pdf->Output('D', 'terminal_report.pdf');
?>
