<?php 
  $xml = new XMLWriter();

  $xml->openURI("php://output");
  $xml->startDocument();
  $xml->setIndent(true);

  $xml->startElement('category');

 /* $sql_cos = "select * from lms_cos where status = '1'";
  $query_cos = mysqli_query($conndb,$sql_cos);
  $num_cos = mysqli_num_rows($query_cos);*/
  if(countArray($course)>0){
      $xml->startElement("code");
      $xml->writeRaw('00');
      $xml->endElement();
      $xml->startElement("retText");
      $xml->writeRaw('data success');
      $xml->endElement();
    foreach ($course as $key => $fetch_cos) {
        $pic_path = "";
        if($fetch_cos['pic']!=""){
          $pic_path = "uploads/course/".$fetch_cos['pic'];
        }
      $xml->startElement("record");
      $xml->writeElement("cat_id", $fetch_cos['id']);
      $xml->writeElement("cat_name_th", $fetch_cos['cname_th']);
      $xml->writeElement("cat_name_en", $fetch_cos['cname_en']);
      $xml->writeElement("desc_th", $fetch_cos['cdesc_th']);
      $xml->writeElement("desc_en", $fetch_cos['cdesc_en']);
      $xml->writeElement("image", $fetch_cos['pic']);
      $xml->writeElement("date", $fetch_cos['date_start']);
      $xml->endElement();
    }
  }else{

      $xml->startElement("code");
      $xml->writeRaw('01');
      $xml->startElement("retText");
      $xml->writeRaw('Information not found');

      $xml->endElement();
  }
  $xml->endElement();

  header('Content-type: text/xml');
  $xml->flush();
?>