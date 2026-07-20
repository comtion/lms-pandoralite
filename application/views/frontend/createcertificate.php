<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	header('Expires: 0');

	// REAL PATH
	define('FPDF_FONTPATH',REAL_PATH.'./application/libraries/FPDF/font/');
	require(REAL_PATH.'./application/libraries/FPDF/fpdf.php');
	require_once (REAL_PATH.'./application/libraries/FPDF/Classes/PHPExcel.php');

	// XAMPP PATH
	// define('FPDF_FONTPATH','./application/libraries/FPDF/font/');
	// require('./application/libraries/FPDF/fpdf.php');
	// require_once ('./application/libraries/FPDF/Classes/PHPExcel.php');

$Fullname = isset($_REQUEST['Fullname']) ? $_REQUEST['Fullname'] : '';
$org2 = isset($_REQUEST['org2']) ? $_REQUEST['org2'] : '';
$cname = isset($_REQUEST['cname']) ? $_REQUEST['cname'] : '';
$finish_time = isset($_REQUEST['finish_time']) ? $_REQUEST['finish_time'] : '';
$badges_img = isset($_REQUEST['badges_img']) ? $_REQUEST['badges_img'] : '';

	$pdf = new FPDF('L','mm','A4');

	$pdf->AddFont('helvetica-med-ext','','DB Helvethaica X Med Ext v3.2.php');
	$pdf->AddFont('helvetica-med','','DB Helvethaica X Med v3.2.php');
	$pdf->AddFont('helvetica-li','','DB Helvethaica X Li.php');
	$pdf->SetTopMargin(65);

		$pdf->AddPage();
		$pdf->Image('./uploads/'.$badges_img,0,0,297.01,209.97); //mm 

		$pdf->SetFont('helvetica-med','',35);
		$pdf->SetTextColor(0,0,0);	
		$pdf->Cell(0,10,iconv('UTF-8','cp874',$Fullname),0,1,'C');
		$pdf->Cell(0,15,iconv('UTF-8','cp874',$org2),0,1,'C');
		$pdf->Cell(0,5,iconv('UTF-8','cp874',''),0,1,'C'); //space

		$pdf->SetFont('helvetica-med-ext','',27);
		$pdf->SetTextColor(88,88,90);		
		$pdf->Cell(0,3,iconv('UTF-8','cp874',$cname),0,1,'C');

		$pdf->SetFont('helvetica-li','',25);
		$pdf->SetTextColor(237,54,61);		
		$pdf->Cell(0,13,iconv('UTF-8','cp874','ให้ไว้ ณ วันที่ '.$finish_time),0,1,'C');


	$pdf->Output('certificate.pdf' , 'D');

?>