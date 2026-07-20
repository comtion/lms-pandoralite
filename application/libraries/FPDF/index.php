<?php
	define('FPDF_FONTPATH','./font/');

	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	header('Expires: 0');

	require('fpdf.php');
	require_once "Classes/PHPExcel.php";

	$tmpfname = "cert.xls";
	$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
	$excelObj = $excelReader->load($tmpfname);
	$worksheet = $excelObj->getSheet(0);
	$lastRow = $worksheet->getHighestRow();

	$pdf = new FPDF('L','mm','A4');

	$pdf->AddFont('helvetica-med-ext','','DB Helvethaica X Med Ext v3.2.php');
	$pdf->AddFont('helvetica-med','','DB Helvethaica X Med v3.2.php');
	$pdf->AddFont('helvetica-li','','DB Helvethaica X Li.php');
	$pdf->SetTopMargin(65);

	for ($row = 1; $row <= $lastRow; $row++) {

		$pdf->AddPage();
		$pdf->Image('images/default.png',0,0,297.01,209.97); //mm 

		$pdf->SetFont('helvetica-med','',35);
		$pdf->SetTextColor(0,0,0);	
		$pdf->Cell(0,5,iconv('UTF-8','cp874',$worksheet->getCell('A'.$row)->getValue()),0,1,'C');
		$pdf->Cell(0,22,iconv('UTF-8','cp874',$worksheet->getCell('B'.$row)->getValue()),0,1,'C');
		$pdf->Cell(0,5,iconv('UTF-8','cp874',''),0,1,'C'); //space

		$pdf->SetFont('helvetica-med-ext','',27);
		$pdf->SetTextColor(88,88,90);		
		$pdf->Cell(0,5,iconv('UTF-8','cp874',$worksheet->getCell('C'.$row)->getValue()),0,1,'C');
		$pdf->Cell(0,15,iconv('UTF-8','cp874',$worksheet->getCell('D'.$row)->getValue()),0,1,'C');
		$pdf->Cell(0,3,iconv('UTF-8','cp874',''),0,1,'C'); //space

		$pdf->SetFont('helvetica-li','',25);
		$pdf->SetTextColor(237,54,61);		
		$pdf->Cell(0,5,iconv('UTF-8','cp874',$worksheet->getCell('E'.$row)->getValue()),0,1,'C');

	}

	$pdf->Output();

	?>
<!-- <?php 

		require_once "Classes/PHPExcel.php";
		$tmpfname = "cert.xls";
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$worksheet = $excelObj->getSheet(0);
		$lastRow = $worksheet->getHighestRow();
		
		echo "<table>";
		for ($row = 1; $row <= $lastRow; $row++) {
			 echo "<tr><td>";
			 echo $worksheet->getCell('A'.$row)->getValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('B'.$row)->getValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('C'.$row)->getValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('D'.$row)->getValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('E'.$row)->getValue();
			 echo "</td><tr>";
		}
		echo "</table>";


		 ?> -->