<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	header('Expires: 0');
	// REAL PATH
	require_once ("./application/libraries/FPDF/Classes/PHPExcel.php");

	// XAMPP PATH
	// define('FPDF_FONTPATH','./application/libraries/FPDF/font/');
	// require('./application/libraries/FPDF/fpdf.php');
	// require_once ('./application/libraries/FPDF/Classes/PHPExcel.php');

	$tmpfname = "./uploads/temp/certificate_excel.xlsx";
	$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
	$excelObj = $excelReader->load($tmpfname);
	$worksheet = $excelObj->getSheet(0);
	$lastRow = $worksheet->getHighestRow();

	/*$pdf = new FPDF('L','mm','A4');

    $pdf->AddFont('FREEDB','','FREEDB.php');
	$pdf->AddFont('helvetica-med','','DB Helvethaica X Med v3.2.php');
	$pdf->AddFont('helvetica-li','','DB Helvethaica X Li.php');
	$pdf->SetTopMargin(85);

	for ($row = 1; $row <= $lastRow; $row++) {

		if($worksheet->getCell('A'.$row)->getValue()!="Fullname"){
			$pdf->AddPage();
			$pdf->Image('./uploads/temp/certificate_img.jpg',0,0,297.01,209.97); //mm 

			$pdf->SetFont('FREEDB','',35);
			$pdf->SetTextColor(0,0,0);	
			$pdf->Cell(0,10,iconv('UTF-8','cp874',$worksheet->getCell('A'.$row)->getValue()),0,1,'C');
			$pdf->SetFont('FREEDB','',27);
			$pdf->SetTextColor(0,0,0);	
			$pdf->Cell(0,15,iconv('UTF-8','cp874',$worksheet->getCell('B'.$row)->getValue()),0,1,'C');
			$pdf->Cell(0,5,iconv('UTF-8','cp874',''),0,1,'C'); //space

			$pdf->SetFont('FREEDB','',27);
			$pdf->SetTextColor(0,0,0);	
			$pdf->Cell(0,5,iconv('UTF-8','cp874',''),0,1,'C'); //space
			$pdf->Cell(0,5,iconv('UTF-8','cp874',''),0,1,'C'); //space
			$pdf->Cell(0,5,iconv('UTF-8','cp874',''),0,1,'C'); //space
			$pdf->Cell(0,3,iconv('UTF-8','cp874',$worksheet->getCell('C'.$row)->getValue()),0,1,'C');
			$pdf->Cell(0,15,iconv('UTF-8','cp874',$worksheet->getCell('D'.$row)->getValue()),0,1,'C');
			$pdf->Cell(0,3,iconv('UTF-8','cp874',$worksheet->getCell('E'.$row)->getValue()),0,1,'C');
			$pdf->Cell(0,3,iconv('UTF-8','cp874',''),0,1,'C'); //space

			$pdf->SetFont('FREEDB','',25);
			$pdf->SetTextColor(0,0,0);		
			$pdf->Cell(0,13,iconv('UTF-8','cp874','Issued on '.$worksheet->getCell('F'.$row)->getValue()),0,1,'C');
		}

	}

	$pdf->Output('certificate.pdf' , 'D');*/
	//redirect(base_url().'certificate/certificateall') ;

	require_once ('./application/libraries/FPDF/Classes/PHPExcel.php');
	$tmpfname = "./uploads/temp/".$nameFileExcel;
	$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
	$excelObj = $excelReader->load($tmpfname);
	$worksheet = $excelObj->getSheet(0);
	$lastRow = $worksheet->getHighestRow();
	//$pdf = new TCPDF();
	$arr = array();
	$numrow = 1;
	for ($row = 1; $row <= $lastRow; $row++) {
		if($worksheet->getCell('A'.$row)->getValue()!="Fullname"){
					array_push($arr, $worksheet->getCell('G'.$row)->getValue());
					$numrow++;
		}
	}
	require_once  './vendor/autoload.php';

	$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
	$fontData = $defaultFontConfig['fontdata'];
	$fontDirs = (new Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'];
	$pdf = new \Mpdf\Mpdf([
		'margin_left' => 40,
		'margin_right' => 40,
		'orientation' => 'L',
		'fontDir' => array_merge($fontDirs, [
			ROOT_DIR.'assets/tfpdf/font',
		]),
		'fontdata' => $fontData + [ // lowercase letters only in font key
			'cordia' => [
				'R' => 'cordia.ttf',
			],
			'freesans' => [
				'R' => 'FreeSans.ttf',
				'B' => 'FreeSansBold.ttf',
			],
			'notojpbold' => [
				'R' => 'NotoSansJP-Bold.ttf',
				'B' => 'NotoSansJP-Bold.ttf',
				'useOTL' => 0xFF,
			]
		],
		'default_font' => 'cordia'
	]);
	/*if((in_array('JP', $arr)||in_array('EN', $arr))&&!in_array('TH', $arr)){
		require_once ('assets/TCPDF-master/tcpdf.php');
		$pdf = new TCPDF();
	}else{
		define('FPDF_FONTPATH','./application/libraries/FPDF/font/');
		require('./application/libraries/FPDF/fpdf.php');
		$pdf = new FPDF('L','mm','A4');
	}*/
	for ($row = 1; $row <= $lastRow; $row++) {
		if($worksheet->getCell('A'.$row)->getValue()!="Fullname"&&$worksheet->getCell('A'.$row)->getValue()!=""){
			if(in_array($worksheet->getCell('G'.$row)->getValue(), array('EN', 'JP'))){
                /*$pdf->SetTopMargin(80);
                $pdf->AddPage('L','A4');
                $pdf->SetAutoPageBreak(false, 0);
				$pdf->Image('./uploads/temp/certificate_img.jpg',0,0,297.01,209.97); //mm 

    			$pdf->SetFont('cid0jp','',30);
                $pdf->SetTextColor(0,0,0);  
                $pdf->Cell(0,0,$worksheet->getCell('A'.$row)->getValue(),0,1,'C');
                $pdf->SetFont('cid0jp','',22);
                $pdf->SetTextColor(0,0,0);  
                $pdf->Cell(0,0,$worksheet->getCell('B'.$row)->getValue(),0,1,'C');
                $pdf->Cell(0,0,'',0,1,'C'); //space

                $pdf->SetFont('cid0jp','',25);
                $pdf->SetTextColor(0,0,0);   
                $pdf->Cell(0,0,'',0,1,'C'); //space
				$pdf->Cell(0,3,$worksheet->getCell('C'.$row)->getValue(),0,1,'C');
				$pdf->Cell(0,15,$worksheet->getCell('D'.$row)->getValue(),0,1,'C');
				$pdf->Cell(0,3,$worksheet->getCell('E'.$row)->getValue(),0,1,'C');
                $pdf->Cell(0,0,'',0,1,'C');

                $pdf->SetFont('cid0jp','',25);
                //$pdf->SetTextColor(237,54,61);    
                $pdf->SetTextColor(0,0,0);  
				$pdf->Cell(0,13,'Issued on '.$worksheet->getCell('F'.$row)->getValue(),0,1,'C');*/
                $pdf->text_input_as_HTML = true;
                $pdf->SetTopMargin(80);
				$pdf->SetLeftMargin(20); // ตั้งระยะขอบซ้ายเป็น 20 มม.
				$pdf->SetRightMargin(20); // ตั้งระยะขอบซ้ายเป็น 20 มม.
                $pdf->AddPage('L','mm','A4');
                $pdf->SetAutoPageBreak(false, 0);
				$pdf->Image('./uploads/temp/'.$nameFileImage, 0, 0, 297, 210, 'jpg', '', true, false, true); //mm
				//$pdf->SetWatermarkImage('./uploads/certificate/certificate_original.jpg', 1.0);   
				//$pdf->showWatermarkImage = true;
                $currentFont = $worksheet->getCell('G'.$row)->getValue() === 'EN' ? 'freesans' : 'notojpbold';
                $layout = certificate_text_layout($worksheet->getCell('G'.$row)->getValue());
                $pdf->SetY($layout['name_y']);
                $pdf->SetFont($currentFont, 'B', $layout['name_size']);
                $pdf->SetTextColor(0,0,0);
                $pdf->WriteCell(0, $layout['name_height'], $worksheet->getCell('A'.$row)->getValue(), 0, 1, 'C');

                $pdf->SetY($layout['company_y']);
                $pdf->SetFont($currentFont, 'B', $layout['company_size']);
                $pdf->WriteCell(0, $layout['company_height'], $worksheet->getCell('B'.$row)->getValue(), 0, 1, 'C');

                $pdf->SetY($layout['course_y']);
                $course = htmlspecialchars((string)$worksheet->getCell('C'.$row)->getValue(), ENT_QUOTES, 'UTF-8');
                $pdf->WriteHTML("<div style='font-family:$currentFont; font-weight:bold; font-size:{$layout['course_size']}pt; text-align:center; width:100%; line-height:1.1;'>$course</div>");

                $pdf->SetY($layout['issued_y']);
                $pdf->SetFont($currentFont, 'B', $layout['issued_size']);
                $pdf->WriteCell(0, $layout['issued_height'], 'Issued on '.$worksheet->getCell('F'.$row)->getValue(), 0, 1, 'C');
			}else{/*
				$pdf->SetTopMargin(85);
				$pdf->AddPage();
				$pdf->Image('./uploads/temp/certificate_img.jpg',0,0,297.01,209.97); //mm 

    			$pdf->AddFont('cordia','','cordia.php');
				$pdf->SetFont('cordia','',35);
				$pdf->SetTextColor(0,0,0);	
				$pdf->Cell(0,10,iconv('UTF-8','cp874',$worksheet->getCell('A'.$row)->getValue()),0,1,'C');
				$pdf->SetFont('cordia','',27);
				$pdf->SetTextColor(0,0,0);	
				$pdf->Cell(0,15,iconv('UTF-8','cp874',$worksheet->getCell('B'.$row)->getValue()),0,1,'C');
				$pdf->Cell(0,5,iconv('UTF-8','cp874',''),0,1,'C'); //space

				$pdf->SetFont('cordia','',27);
				$pdf->SetTextColor(0,0,0);	
				$pdf->Cell(0,5,iconv('UTF-8','cp874',''),0,1,'C'); //space
				$pdf->Cell(0,5,iconv('UTF-8','cp874',''),0,1,'C'); //space
				$pdf->Cell(0,5,iconv('UTF-8','cp874',''),0,1,'C'); //space
				$pdf->Cell(0,3,iconv('UTF-8','cp874',$worksheet->getCell('C'.$row)->getValue()),0,1,'C');
				$pdf->Cell(0,15,iconv('UTF-8','cp874',$worksheet->getCell('D'.$row)->getValue()),0,1,'C');
				$pdf->Cell(0,3,iconv('UTF-8','cp874',$worksheet->getCell('E'.$row)->getValue()),0,1,'C');
				$pdf->Cell(0,3,iconv('UTF-8','cp874',''),0,1,'C'); //space

				$pdf->SetFont('cordia','',25);
				$pdf->SetTextColor(0,0,0);		
				$pdf->Cell(0,13,iconv('UTF-8','cp874','Issued on '.$worksheet->getCell('F'.$row)->getValue()),0,1,'C');*/
                $pdf->text_input_as_HTML = true;
                $pdf->SetTopMargin(80);
                $pdf->AddPage('L','mm','A4');
                //$pdf->SetDefaultBodyCSS('background-image', 'uploads/certificate/certificate_original_th.jpg');
                //$pdf->SetDefaultBodyCSS('background', "url('./uploads/certificate/certificate_original_th.jpg')");
				//$pdf->SetDefaultBodyCSS('background-image-resize', 6);
				$pdf->Image('./uploads/temp/'.$nameFileImage, 0, 0, 297, 210, 'jpg', '', true, false, true); //mm
				$pdf->SetDisplayMode('fullpage');
				//$pdf->SetWatermarkImage('./uploads/certificate/certificate_original.jpg', 1.0);   
				//$pdf->showWatermarkImage = true;
                
                $pdf->SetFont('cordia','',30);
                $pdf->SetTextColor(0,0,0); 
                $pdf->WriteCell(0, 10, $worksheet->getCell('A'.$row)->getValue(), 0, 1, 'C');
                $pdf->WriteCell(0, 20, $worksheet->getCell('B'.$row)->getValue(), 0, 1, 'C');
                // $pdf->WriteCell(0,13,$cname,0,1,'C');

                $pdf->SetFont('cordia','',25);
                $pdf->SetTextColor(0,0,0);
                $pdf->WriteCell(0, ($row > 2 ? 15 : 5),'',0,1,'C'); //space
                $pdf->WriteHTML("<p  style='white-space: nowrap; font-size:35px;text-align: center;padding-bottom: -50px;'>".$worksheet->getCell('C'.$row)->getValue()."</p>");
                $pdf->SetFont('cordia','',25);
                $pdf->SetTextColor(0,0,0);
				$pdf->WriteCell(0,13,$worksheet->getCell('D'.$row)->getValue(),0,1,'C');
				$pdf->WriteCell(0,13,$worksheet->getCell('E'.$row)->getValue(),0,1,'C');
                $pdf->WriteCell(0,13, 'ให้ไว้ ณ วันที่ '.$worksheet->getCell('F'.$row)->getValue(),0,1,'C');
			}
		}

	}
	$pdf->Output('certificate.pdf' , 'D');
?>
