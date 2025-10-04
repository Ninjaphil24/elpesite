<?php
require_once 'connection.php';
require_once 'tcpdf/tcpdf.php'; // make sure tcpdf/ is in your project

$eid = (int) $_POST['eid'];

// Get entry and comments
$result = $con->query("SELECT title FROM entry WHERE eid = {$eid}");
if (! $result) {
    die("SQL Error (entry): " . $con->error);
}
$entry    = $result->fetch_assoc();
$comments = $con->query("
    SELECT usertable.firstName, usertable.lastName, comments.comment, comments.commentCreatedOn
    FROM comments
    JOIN usertable ON comments.userID = usertable.id
    WHERE comments.entryID = {$eid}
    ORDER BY comments.commentCreatedOn
");
if (! $comments) {
    die("SQL Error (comments): " . $con->error);
}

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

// Header boilerplate
$pdf->Cell(0, 10, 'ΕΛΠΕ - ΓΡΑΜΜΑΤΕΙΑΚΗ ΚΑΤΑΧΩΡΗΣΗ', 0, 1, 'C');
$pdf->Ln(5);

// Entry title
$pdf->MultiCell(0, 10, "ΘΕΜΑ: " . $entry['title'], 0, 'L', false);
$pdf->Ln(10);

// Comments section
$pdf->SetFont('dejavusans', '', 11);
while ($c = $comments->fetch_assoc()) {
    $pdf->MultiCell(0, 8, "{$c['firstName']} {$c['lastName']} ({$c['commentCreatedOn']}):", 0, 'L');
    $pdf->MultiCell(0, 8, $c['comment'], 0, 'L');
    $pdf->Ln(4);
}

// Footer
$pdf->SetY(-30);
$pdf->Cell(0, 10, 'Τέλος εγγράφου – ΕΛΠΕ', 0, 0, 'C');

$pdf->Output("grammateiaki_{$eid}.pdf", 'I');
