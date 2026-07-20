<?php
include('config.php');

if( isset($_POST['useri']) ){
  $data = array();
  $data['userp'] = $defaultPass = hash('sha256', 'Init1234');
  $db->where('useri', $_POST['useri'] );
  $result = $db->get('lms_usp');
  if( sizeof( $result ) > 0 ){
    $db->where('useri', $_POST['useri'] );
    $db->update('lms_usp',$data);
    echo 'Update complete ';
  }else{
    echo 'Username not found !';
  }
}
?>
<html>
  <body>
    <form action="reset.php" method="post">
      <input type="text" name="useri" />
      <input type="submit" value="Reset" />
    </post>
  </body>
</html>
