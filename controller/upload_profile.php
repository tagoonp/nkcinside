<?php
include "../config.inc.php";
include "../connect.inc.php";
include "../function.inc.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$return = array();

if (!empty($_FILES)) {
  $org = $_FILES['file']['name'];
  $tempFile = $_FILES['file']['tmp_name'];

  $array = explode('.', $_FILES['file']['name']);
  $extension = end($array);

  $targetPath = '../upload/profile/';  //4
  $filename = date('U').'-'.$_FILES['file']['name'];
  $targetFile =  $targetPath.$filename;  //5
  if(move_uploaded_file($tempFile, $targetFile)){
    echo "Y";

    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $fullUrl = 'https://fxplor.com/iw_web/upload/profile/'.$filename;

    $strSQL = "UPDATE dj3x_account SET profile_img_url = '$fullUrl' WHERE uid = '$uid' AND delete_status = 'N'";
    $query = mysqli_query($conn, $strSQL);

    if($query){
      echo "Y";
    }
  }else{
    echo "N2";
  }
}else{
  echo "N1";
  // $aid = mysqli_real_escape_string($conn, $_POST['aid']);
  // echo $aid;
}

mysqli_close($conn);
die();

?>
