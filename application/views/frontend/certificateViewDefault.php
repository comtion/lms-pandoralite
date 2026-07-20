<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
    header('Cache-Control: no-cache');
    header('Pragma: no-cache');
    header('Expires: 0');

    require_once './vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\IOFactory;

    $tmpfname = "./uploads/temp/" . $nameFileExcel;
    $spreadsheet = IOFactory::load($tmpfname);
    $worksheet = $spreadsheet->getActiveSheet();
    $lastRow = $worksheet->getHighestRow();

    $tempDir = APPPATH . 'cache/mpdf';
    if (!is_dir($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    $fontDirs = (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'];

    $pdf = new \Mpdf\Mpdf([
        'tempDir' => $tempDir,
        'mode' => 'utf-8',
        'format' => 'A4-L',
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_top' => 0,
        'margin_bottom' => 0,
        'fontDir' => array_merge($fontDirs, [
            ROOT_DIR . 'assets/tfpdf/font',
        ]),
        'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
            'cordia' => [
                'R' => 'cordia.ttf',
                'useOTL' => 0,
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

    for ($row = 1; $row <= $lastRow; $row++) {
        $cellA = (string)$worksheet->getCell('A' . $row)->getValue();
        $cellG = (string)$worksheet->getCell('G' . $row)->getValue();

        if ($cellA !== "Fullname" && $cellA !== "") {
            $pdf->AddPage();

            if (in_array($cellG, ['EN', 'JP'])) {
                $pdf->Image('./uploads/certificate/certificate_original.jpg', 0, 0, 297, 210);
                $issuedText = 'Issued on ';
                $currentFont = $cellG === 'EN' ? 'freesans' : 'notojpbold';
            } else {
                $pdf->Image('./uploads/certificate/certificate_original_th.jpg', 0, 0, 297, 210);
                $currentFont = 'cordia';
                $issuedText = 'ให้ไว้ ณ วันที่ ';
            }

            $layout = certificate_text_layout($cellG);
            $cname = (string)$worksheet->getCell('C' . $row)->getValue();
            $dateVal = (string)$worksheet->getCell('F' . $row)->getValue();

            if (in_array($cellG, ['EN', 'JP'])) {
                $pdf->SetY($layout['name_y']);
                $pdf->SetFont($currentFont, 'B', $layout['name_size']);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->WriteCell(297, $layout['name_height'], (string)$worksheet->getCell('A' . $row)->getValue(), 0, 1, 'C');

                $pdf->SetY($layout['company_y']);
                $pdf->SetFont($currentFont, 'B', $layout['company_size']);
                $pdf->WriteCell(297, $layout['company_height'], (string)$worksheet->getCell('B' . $row)->getValue(), 0, 1, 'C');

                $pdf->SetY($layout['course_y']);
                $course = htmlspecialchars($cname, ENT_QUOTES, 'UTF-8');
                $pdf->WriteHTML("<div style='font-family:$currentFont; font-weight:bold; font-size:{$layout['course_size']}pt; text-align:center; width:100%; line-height:1.1;'>$course</div>");

                $pdf->SetY($layout['issued_y']);
                $pdf->SetFont($currentFont, 'B', $layout['issued_size']);
                $pdf->WriteCell(297, $layout['issued_height'], $issuedText . $dateVal, 0, 1, 'C');
            } else {
                $pdf->SetY($layout['name_y']);
                $pdf->SetFont($currentFont, '', $layout['name_size']);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->WriteCell(297, $layout['name_height'], (string)$worksheet->getCell('A' . $row)->getValue(), 0, 1, 'C');

                $pdf->SetY($layout['company_y']);
                $pdf->SetFont($currentFont, '', $layout['company_size']);
                $pdf->WriteCell(297, $layout['company_height'], (string)$worksheet->getCell('B' . $row)->getValue(), 0, 1, 'C');

                $pdf->SetY($layout['course_y']);
                $pdf->WriteHTML("<div style='font-family:$currentFont; font-size:{$layout['course_size']}pt; text-align:center; width:100%;'>$cname</div>");

                $pdf->SetY($layout['issued_y']);
                $pdf->SetFont($currentFont, '', $layout['issued_size']);
                $pdf->WriteCell(297, $layout['issued_height'], $issuedText . $dateVal, 0, 1, 'C');
            }
        }
    }

    if (ob_get_contents()) {
        ob_end_clean();
    }
    $pdf->Output('certificate.pdf', 'D');
