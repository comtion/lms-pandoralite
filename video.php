<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('./steamming.php');
require_once('./signature.php');
$vs = new VideoSignature();
if(isset($_REQUEST['s']) && $filepath = $vs->getFilepath($_REQUEST['s'])){
    $stream = new VideoStream($filepath);
    $stream->start();
} else {
    header("HTTP/1.0 403 Forbidden");
    echo "This URL has expired.";
}
?>