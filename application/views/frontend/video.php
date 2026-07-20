<?php
// session_start();
// if (($_SERVER['REQUEST_METHOD'] === "GET")&& (isset($_GET['show_the_video']))&& (isset($_GET['filename']))) {
//     $token = $_GET['show_the_video'];
//     // if (!$_SESSION[$token]) {
//     //     echo "<h1>I'm Sorry </h1>";
//     //     session_destroy();
//     // } else {
//         $ctype = 'video/mp4';
  
//         header('Content-Type: ' . $ctype);
//         $file_path_name = "./uploads/media/".$_GET['filename'];
//         $size = filesize($file_path_name);
//         header("Content-Length: ".$size);
//         $handle = fopen($file_path_name, "rb");
//         $contents = fread($handle, filesize($file_path_name));
//         fclose($handle);
//         echo $contents;
//         // $_SESSION[$token] = 0;
//         session_destroy();
//     // }

// } else {
//     echo "<h1>I'm Sorry</h1>";
//     session_destroy();
// }

define('CHUNK_SIZE', 1024*1024); // Size (in bytes) of tiles chunk

// Read a file and display its content chunk by chunk
function readfile_chunked($filename, $retbytes = TRUE) {
    $buffer = '';
    $cnt    = 0;
    $handle = fopen($filename, 'rb');

    if ($handle === false) {
        return false;
    }

    while (!feof($handle)) {
        $buffer = fread($handle, CHUNK_SIZE);
        echo $buffer;
        ob_flush();
        flush();

        if ($retbytes) {
            $cnt += strlen($buffer);
        }
    }

    $status = fclose($handle);

    if ($retbytes && $status) {
        return $cnt; // return num. bytes delivered like readfile() does.
    }

    return $status;
}

// Here goes your code for checking that the user is logged in
// ...
// ...

// if ($logged_in) {
    $filename = "./uploads/media/".$_GET['filename'];
    $mimetype = 'mime/type';
    header('Content-Type: '.$mimetype );
    readfile_chunked($filename);

// } else {
    // echo 'Tabatha says you haven\'t paid.';
// }
?>