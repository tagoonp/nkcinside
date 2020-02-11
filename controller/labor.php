<?php
include "../config.inc.php";
include "../connect.inc.php";
include "../function.inc.php";

$return = array();

if(!isset($_GET['stage'])){
  mysqli_close($conn);
  die();
}

$stage = mysqli_real_escape_string($conn, $_GET['stage']);

if($stage == 'get_complete'){
  if(
      (!isset($_POST['uid'])) ||
      (!isset($_POST['cid']))
  ){
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $cid = base64_encode(mysqli_real_escape_string($conn, $_POST['cid']));
  $strSQL = "SELECT * FROM dj3x_project2_status WHERE CID = '$cid' ORDER BY ID DESC LIMIT 1";
  $query = mysqli_query($conn, $strSQL);
  if(($query) && (mysqli_num_rows($query) > 0)){
    while($row = mysqli_fetch_array($query)){
      $b = array();
      foreach ($row as $key => $value) {
        if(!is_int($key)){
          if($key == 'part_1_3_status'){
            $strSQL = "SELECT * FROM dj3x_laborinfo
                       WHERE lb_cid = '$cid' AND (lb_home_prov = '' OR lb_home_prov IS NULL)
                       AND lb_id IN (SELECT MAX(lb_id) lb_id FROM dj3x_laborinfo WHERE lb_cid = '$cid')";
            $resultCheck = mysqli_query($conn, $strSQL);
            if(($resultCheck) && (mysqli_num_rows($resultCheck) > 0)){
              $strSQL = "UPDATE dj3x_project2_status SET part_1_3_status = 'N' WHERE CID = '$cid'";
              $resultUpdate = mysqli_query($conn, $strSQL);
              $b[$key] = 'N';
            }else{
              $b[$key] = $value;
            }


          }else{
            $b[$key] = $value;
          }

        }
      }
      $return[] = $b;
    }
  }
  echo json_encode($return);
  mysqli_close($conn);
  die();
}

if($stage == 'search_in_response_all'){
  if(
      (!isset($_POST['uid'])) ||
      (!isset($_POST['province']))
  ){
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $province = mysqli_real_escape_string($conn, $_POST['province']);

  $strSQL = "SELECT * FROM dj3x_laborvisibility a INNER JOIN dj3x_laborinfo b ON a.allow_labor_cid = b.lb_cid
             WHERE
             (b.lb_record_uid = '$uid' OR a.allow_labor_cid IN (SELECT act_cid FROM dj3x_response WHERE act_uid = '$uid'))
             AND a.allow_labor_province = '$province'
             AND a.allow_labor_status = '1'
             AND b.lb_record_use = '1'
             GROUP BY b.lb_cid";
  $resultCheck = mysqli_query($conn, $strSQL);
  if(($resultCheck) && (mysqli_num_rows($resultCheck) > 0)){
    while($row = mysqli_fetch_array($resultCheck)){
      $b = array();
      foreach ($row as $key => $value) {
        if(!is_int($key)){
          if($key == 'lb_cid'){
            $b['r_cid'] = base64_decode($value);
          }
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

if($stage == 'search_in_response'){
  if(
      (!isset($_POST['uid'])) ||
      (!isset($_POST['province'])) ||
      (!isset($_POST['keyword']))
  ){
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $province = mysqli_real_escape_string($conn, $_POST['province']);
  $key = mysqli_real_escape_string($conn, $_POST['keyword']);
  $encode_key = base64_encode($key);

  $strSQL = "SELECT * FROM dj3x_laborvisibility a INNER JOIN dj3x_laborinfo b ON a.allow_labor_cid = b.lb_cid
             WHERE
             (a.allow_labor_cid = '$encode_key' OR b.lb_fname LIKE '$key%' OR b.lb_lname LIKE '$key%')
             AND (b.lb_record_uid = '$uid' OR a.allow_labor_cid IN (SELECT act_cid FROM dj3x_response WHERE act_uid = '$uid'))
             AND a.allow_labor_province = '$province' AND a.allow_labor_status = '1' AND b.lb_record_use = '1'
             GROUP BY b.lb_cid";
  $resultCheck = mysqli_query($conn, $strSQL);
  if(($resultCheck) && (mysqli_num_rows($resultCheck) > 0)){
    while($row = mysqli_fetch_array($resultCheck)){
      $b = array();
      foreach ($row as $key => $value) {
        if(!is_int($key)){
          if($key == 'lb_cid'){
            $b['r_cid'] = base64_decode($value);
          }
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

if($stage == 'search_in_province'){

  if(
      (!isset($_POST['uid'])) ||
      (!isset($_POST['province'])) ||
      (!isset($_POST['cid']))
  ){
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $province = mysqli_real_escape_string($conn, $_POST['province']);
  $cid = base64_encode(mysqli_real_escape_string($conn, $_POST['cid']));

  $strSQL = "SELECT * FROM dj3x_laborvisibility WHERE allow_labor_cid = '$cid' AND allow_labor_province = '$province' AND allow_labor_status = '1'";
  $resultCheck = mysqli_query($conn, $strSQL);
  if(($resultCheck) && (mysqli_num_rows($resultCheck) > 0)){
    echo "Y";
  }

  mysqli_close($conn);
  die();
}

if($stage == 'delete_respnse_labor'){
  if(
      (!isset($_POST['uid'])) ||
      (!isset($_POST['cid'])) ||
      (!isset($_POST['province']))
  ){
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $cid = mysqli_real_escape_string($conn, $_POST['cid']);
  $province = mysqli_real_escape_string($conn, $_POST['province']);

  $strSQL = "DELETE FROM dj3x_response WHERE act_cid = '$cid' AND act_uid = '$uid'";
  $query1 = mysqli_query($conn, $strSQL);

  $strSQL = "DELETE FROM dj3x_laborinfo WHERE lb_cid = '$cid' AND lb_record_uid = '$uid' AND lb_record_province = '$province'";
  $query1 = mysqli_query($conn, $strSQL);

  $strSQL = "UPDATE dj3x_laborinfo SET lb_record_use = '1'
             WHERE lb_id IN (SELECT MAX(lb_id) lb_id FROM dj3x_laborinfo WHERE lb_cid = '$cid') ";
            mysqli_query($conn, $strSQL);

  echo "Y";

  mysqli_close($conn);
  die();

}

if($stage == 'get_info'){
  if(
      (!isset($_POST['uid'])) ||
      (!isset($_POST['cid']))
  ){
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $cid = base64_encode(mysqli_real_escape_string($conn, $_POST['cid']));

  $strSQL = "SELECT * FROM dj3x_laborinfo WHERE lb_cid = '$cid' AND lb_record_use = '1' ORDER BY lb_id DESC LIMIT 1";
  $query = mysqli_query($conn, $strSQL);
  if(($query) && (mysqli_num_rows($query) > 0)){
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
