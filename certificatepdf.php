<?php
session_start();
require_once 'connection.php';
require_once 'tcpdf/tcpdf.php';

// Fetch logged-in user info
if (! empty($_COOKIE["email"])) {
    $email    = $_COOKIE["email"];
    $password = $_COOKIE["password"];
} else {
    $email    = $_SESSION["email"];
    $password = $_SESSION["password"];
}

if ($email != false && $password != false) {
    $sql     = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if ($run_Sql) {
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $firstName  = $fetch_info['firstName'];
        $lastName   = $fetch_info['lastName'];
    } else {
        die("User query failed: " . $con->error);
    }
} else {
    die("Not logged in.");
}

// Dates
$today  = date('d/m/Y');
$expiry = date('d/m/Y', strtotime('+3 months'));

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 14);

// Header / Title
$pdf->Cell(0, 10, 'Ένωση Λυρικών Πρωταγωνιστών Ελλάδος', 0, 1, 'C');
$pdf->Ln(10);

// Issue date
$pdf->SetFont('dejavusans', '', 11);
$pdf->Cell(0, 10, "Ημερομηνία έκδοσης: {$today}", 0, 1, 'R');
$pdf->Ln(10);

// Main certificate text
$pdf->SetFont('dejavusans', '', 12);
$text = "Έκδοση πιστοποιητικού για {$firstName} {$lastName} "
    . "για τη χρήση για εποχικό επίδομα. "
    . "Ισχύ για 3 μήνες, μέχρι την {$expiry}.";
$pdf->MultiCell(0, 10, $text, 0, 'L', false);
$pdf->Ln(20);

// Signature section
$pdf->SetFont('dejavusans', '', 12);
$pdf->MultiCell(0, 10, "Υπογραφή,", 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(0, 10, "Λυδία Αγγελοπούλου, Πρόεδρος ΕΛΠΕ.", 0, 'L');

// Footer
$pdf->SetY(-20);
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0, 10, 'ΕΛΠΕ – Ένωση Λυρικών Πρωταγωνιστών Ελλάδος', 0, 0, 'C');

// Output PDF inline
$filename = "pistopoiitiko_{$firstName}_{$lastName}.pdf";
$pdf->Output($filename, 'I');
