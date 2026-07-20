<?php 

  require_once('./signature.php');
  $vs = new VideoSignature();

  $_GET['filename'];
  $type = isset($_GET['type'])&&$_GET['type']=="qrcode"?"file_forqrcode":"media";
  $token =$vs->getSignedURL("./uploads/".$type."/".$_GET['filename']);

  echo $token;
?>