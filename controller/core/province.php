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

if($stage == 'get'){ // Create new sub-domain
  $strSQL = "SELECT * FROM repos_changwat WHERE 1 ORDER BY Name ";
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
