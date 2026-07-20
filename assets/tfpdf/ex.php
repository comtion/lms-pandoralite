<?php

// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/xampp/htdocs/tfpdf/font");

require('tfpdf.php');

$pdf = new tFPDF();
$pdf->AddPage();

// Add a Unicode font (uses UTF-8)
$pdf->AddFont('cid0jp','','cid0jp.ttf',true);

$pdf->SetFont('cid0jp','',14);
$pdf->Write(5,'ﾛｸﾞｲﾝ');

$pdf->SetFont('cid0jp','',14);
$pdf->Write(5,'Hello');

$pdf->Output();
?>
