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

  $targetPath = '../upload/gallery/';  //4
  $filename = date('U').'-'.$_FILES['file']['name'];
  $targetFile =  $targetPath.$filename;  //5
  if(move_uploaded_file($tempFile, $targetFile)){
    echo "Y";

    $cid = base64_encode(mysqli_real_escape_string($conn, $_POST['cid']));
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);

    $fullUrl = 'https://fxplor.com/iw_web/upload/gallery/'.$filename;

    $strSQL = "INSERT INTO dj3x_labor_media (file_name, media_cid, media_datetime) VALUES ('$fullUrl', '$cid', '$sysdatetime') ";
    $query = mysqli_query($conn, $strSQL);

    if($query){
      echo "Y";

      $strSQL = "UPDATE dj3x_project2_status SET part_6_status = 'Y' WHERE CID = '$cid'";
      $resultUpdate = mysqli_query($conn, $strSQL);

      $strSQL = "INSERT INTO dj3x_response (act_cid, act_uid, act, act_datetime) VALUES ('$cid', '$uid', 'Add photo', '$sysdatetime')";
      $resultInsertLog = mysqli_query($conn, $strSQL);

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
