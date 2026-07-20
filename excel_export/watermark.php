<?php 
function PlaceWatermark($file, $text, $xxx, $yyy, $op, $outdir) {
    require_once('fpdf.php');
    require_once('fpdi.php');
    $name = uniqid();
    $font_size = 5;
    $ts=explode("\n",$text);
    $width=0;
    foreach ($ts as $k=>$string) {
        $width=max($width,strlen($string));
    }
    $width  = imagefontwidth($font_size)*$width;
    $height = imagefontheight($font_size)*count($ts);
    $el=imagefontheight($font_size);
    $em=imagefontwidth($font_size);
    $img = imagecreatetruecolor($width,$height);
    // Background color
    $bg = imagecolorallocate($img, 255, 255, 255);
    imagefilledrectangle($img, 0, 0,$width ,$height , $bg);
    // Font color
    $color = imagecolorallocate($img, 0, 0, 0);
    foreach ($ts as $k=>$string) {
        $len = strlen($string);
        $ypos = 0;
        for($i=0;$i<$len;$i++){
            $xpos = $i * $em;
            $ypos = $k * $el;
            imagechar($img, $font_size, $xpos, $ypos, $string, $color);
            $string = substr($string, 1);      
        }
    }
    imagecolortransparent($img, $bg);
    $blank = imagecreatetruecolor($width, $height);
    $tbg = imagecolorallocate($blank, 255, 255, 255);
    imagefilledrectangle($blank, 0, 0,$width ,$height , $tbg);
    imagecolortransparent($blank, $tbg);
    if ( ($op < 0) OR ($op >100) ){
        $op = 100;
    }
    imagecopymerge($blank, $img, 0, 0, 0, 0, $width, $height, $op);
    imagepng($blank,$name.".png");
    // Created Watermark Image
    $pdf = new FPDI();
    if (file_exists("./".$file)){
        $pagecount = $pdf->setSourceFile($file);
    } else {
        return FALSE;
    }
    $tpl = $pdf->importPage(1);
    $pdf->addPage();
    $pdf->useTemplate($tpl, 1, 1, 0, 0, TRUE);
    //Put the watermark
    $pdf->Image($name.'.png', $xxx, $yyy, 0, 0, 'png');
    if ($outdir === TRUE){
        return $pdf->Output();
    } else {
        return $pdf;
    }
}

PlaceWatermark("filename.pdf", "This is a lazy, but still simple test\n This should stand on a new line!", 30, 120, 100,TRUE);

?>