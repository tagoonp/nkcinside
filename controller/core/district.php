<?php
include "../../config.inc.php";
include "../../connect.inc.php";
include "../../function.inc.php";

$return = array();

if(!isset($_GET['stage'])){
  mysqli_close($conn);
  die();
}

$stage = mysqli_real_escape_string($conn, $_GET['stage']);


if($stage == 'get_all'){
  $strSQL = "SELECT * FROM repos_ampur WHERE 1 ORDER BY Name ";
  $query = mysqli_query($conn, $strSQL);
  if($query){
    while($row = mysqli_fetch_array($query)){
      $b = [];
      foreach ($row as $key => $value) {
        if(!is_int($key)){
          $b[$key] = $value;
        }
      }
      $return[] = $b;
    }
  }

  echo json_encode($return);
  mysqli_close($conn);
  die();
}

if($stage == 'get'){ // Create new sub-domain
  $prov = mysqli_real_escape_string($conn, $_POST['prov']);
  $strSQL = "SELECT * FROM repos_ampur WHERE Changwat = '$prov' ORDER BY Name ";
  $query = mysqli_query($conn, $strSQL);
  if($query){
    while($row = mysqli_fetch_array($query)){
      $b = array();
      foreach ($row as $key => $value) {
        if(!is_int($key)){
          $b[$key] = $value;
        }
      }
      $return[] = $b;
    }
  }

  echo json_encode($return);
  mysqli_close($conn);
  die();
}

?>
