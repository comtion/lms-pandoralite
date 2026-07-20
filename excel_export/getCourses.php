<?php

$letters=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
include("conn.php");
mysqli_query($conndb,"SET NAMES 'utf8'");

  /*$xml = new DomDocument('1.0');
  $xml->formatOutput = true;

  $category = $xml->createElement("category");
  $xml->appendChild($category);

  $sql_cos = "select * from lms_cos where status = '1'";
  $query_cos = mysqli_query($conndb,$sql_cos);
  $num_cos = mysqli_num_rows($query_cos);
  if($num_cos>0){
    while($fetch_cos = mysqli_fetch_array($query_cos)){
        $code = $xml->createElement("code","00");
        $category->appendChild($code);
        $retText = $xml->createElement("retText","data success");
        $category->appendChild($retText);

        $record = $xml->createElement("record");
        $category->appendChild($record);
        $date_start = "";
        $date_end = "";
        $sql_detail = "select * from lms_cos_detail where cos_id = '".$fetch_cos['id']."' and status = '1'";
        $query_detail = mysqli_query($conndb,$sql_detail);
        $num_detail = mysqli_num_rows($query_detail);
        if($num_detail>0){
          $fetch_detail = mysqli_fetch_array($query_detail);
          $date_start = date('Y-m-d',strtotime($fetch_detail['date_start']));
          $date_end = $fetch_detail['date_end'];
        }
        $cat_id = $xml->createElement("cat_id",$fetch_cos['id']);
        $record->appendChild($cat_id);
        $cat_name_th = $xml->createElement("cat_name_th",$fetch_cos['cname_th']);
        $record->appendChild($cat_name_th);
        $cat_name_en = $xml->createElement("cat_name_en",$fetch_cos['cname_en']);
        $record->appendChild($cat_name_en);
        $desc_th = $xml->createElement("desc_th",$fetch_cos['cdesc_th']);
        $record->appendChild($desc_th);
        $desc_en = $xml->createElement("desc_en",$fetch_cos['cdesc_en']);
        $record->appendChild($desc_en);
        $image = $xml->createElement("image",$fetch_cos['pic']);
        $record->appendChild($image);
        $date = $xml->createElement("date",$date_start);
        $record->appendChild($date);
    }
  }else{
        $code = $xml->createElement("code","01");
        $category->appendChild($code);
        $retText = $xml->createElement("retText","Information not found");
        $category->appendChild($retText);
  }
  

  echo "<xmp>".$xml->saveXML()."</xmp>";*/

  $xml = new XMLWriter();

  $xml->openURI("php://output");
  $xml->startDocument();
  $xml->setIndent(true);

  $xml->startElement('category');

  $sql_cos = "select * from lms_cos where status = '1'";
  $query_cos = mysqli_query($conndb,$sql_cos);
  $num_cos = mysqli_num_rows($query_cos);
  if($num_cos>0){
    while($fetch_cos = mysqli_fetch_array($query_cos)){
				$xml->startElement("code");
				$xml->writeRaw('00');
				$xml->endElement();
				$xml->startElement("retText");
				$xml->writeRaw('data success');
				$xml->endElement();

        $date_start = "";
        $date_end = "";
        $sql_detail = "select * from lms_cos_detail where cos_id = '".$fetch_cos['id']."' and status = '1'";
        $query_detail = mysqli_query($conndb,$sql_detail);
        $num_detail = mysqli_num_rows($query_detail);
        if($num_detail>0){
          $fetch_detail = mysqli_fetch_array($query_detail);
          $date_start = date('Y-m-d',strtotime($fetch_detail['date_start']));
          $date_end = $fetch_detail['date_end'];
        }

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
				$xml->writeElement("date", $date_start);
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
