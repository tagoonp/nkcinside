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

if($stage == 'add_location'){
  if(
    (!isset($_POST['cid'])) ||
    (!isset($_POST['uid'])) ||
    (!isset($_POST['lat'])) ||
    (!isset($_POST['lng']))
  )
  {
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $cid = mysqli_real_escape_string($conn, $_POST['cid']);
  $lat = mysqli_real_escape_string($conn, $_POST['lat']);
  $lng = mysqli_real_escape_string($conn, $_POST['lng']);
  $prov = mysqli_real_escape_string($conn, $_POST['prov']);
  $rcid = $cid;
  $cid = base64_encode($cid);

  $strSQL = "INSERT INTO dj3x_geolocation (geo_lat, geo_lng, geo_cid, geo_udatetime, geo_uid, geo_record_province)
             VALUES ('$lat',' $lng', '$cid', '$sysdatetime', '$uid', '$prov')
            ";
  $query = mysqli_query($conn, $strSQL);
  if($query){
    $strSQL = "UPDATE dj3x_project2_status SET part_5_status = 'Y' WHERE CID = '$cid'";
    $resultUpdate = mysqli_query($conn, $strSQL);

    $strSQL = "INSERT INTO dj3x_response (act_cid, act_uid, act, act_datetime) VALUES ('$cid', '$uid', 'Add location', '$sysdatetime')";
    $resultInsertLog = mysqli_query($conn, $strSQL);
    echo "Y";
  }
  mysqli_close($conn);
  die();
}

if($stage == 'add_part3'){
  if(
    (!isset($_POST['cid'])) ||
    (!isset($_POST['uid'])) ||
    (!isset($_POST['part'])) ||
    (!isset($_POST['side'])) ||
    (!isset($_POST['score'])) ||
    (!isset($_POST['province'])) ||
    (!isset($_POST['detail']))
  )
  {
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $cid = mysqli_real_escape_string($conn, $_POST['cid']);
  $part = mysqli_real_escape_string($conn, $_POST['part']);
  $side = mysqli_real_escape_string($conn, $_POST['side']);
  $score = mysqli_real_escape_string($conn, $_POST['score']);
  $detail = mysqli_real_escape_string($conn, $_POST['detail']);
  $province = mysqli_real_escape_string($conn, $_POST['province']);
  $rcid = $cid;
  $cid = base64_encode($cid);

  $strSQL = "INSERT INTO dj3x_bodymap_illness (bm_position, bm_side, bm_scode, bm_symptom, bm_cid, bm_record_province, bm_record_year, bm_record_uid, bm_record_datetime)
             VALUES ('$part', '$side', '$score', '$detail', '$cid', '$province', '$sysdateyear', '$uid', '$sysdatetime')
            ";
  $resultInsert = mysqli_query($conn, $strSQL);
  if($resultInsert){
    echo "Y";

    $strSQL = "UPDATE dj3x_project2_status SET part_3_status = 'Y', r_year = '$sysdateyear' WHERE CID = '$cid'";
              mysqli_query($conn, $strSQL);

    $strSQL = "SELECT * FROM dj3x_response WHERE act_uid = '$uid' AND act_cid = '$cid' LIMIT 1";
    $resultCheck = mysqli_query($conn, $strSQL);
    if(($resultCheck) && (mysqli_num_rows($resultCheck) > 0)){

    }else{
      $strSQL = "INSERT INTO dj3x_response (act_cid, act_uid, act, act_datetime) VALUES ('$cid', '$uid', 'Add part 3', '$sysdatetime')";
      mysqli_query($conn, $strSQL);
    }
  }

  mysqli_close($conn);
  die();
}

if($stage == 'delete_bodyeffect'){
  if(
    (!isset($_POST['cid'])) ||
    (!isset($_POST['uid'])) ||
    (!isset($_POST['bid']))
  )
  {
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $cid = mysqli_real_escape_string($conn, $_POST['cid']);
  $bid = mysqli_real_escape_string($conn, $_POST['bid']);
  $rcid = $cid;
  $cid = base64_encode($cid);

  $strSQL = "DELETE FROM dj3x_bodymap_illness WHERE bm_id = '$bid'";
  $deleteBm = mysqli_query($conn, $strSQL);
  if($deleteBm){
    echo "Y";
  }
  mysqli_close($conn);
  die();
}

if($stage == 'get_bodyeffect'){
  if(
    (!isset($_POST['cid'])) ||
    (!isset($_POST['uid']))
  )
  {
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $cid = mysqli_real_escape_string($conn, $_POST['cid']);
  $rcid = $cid;
  $cid = base64_encode($cid);
  $strSQL = "SELECT * FROM dj3x_bodymap_illness a LEFT JOIN dj3x_body_position b ON a.bm_position = b.pos_id WHERE a.bm_cid = '$cid'";
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
  }else{
    $strSQL = "UPDATE dj3x_project2_status SET part_3_status = 'N' WHERE CID = '$cid'";
    mysqli_query($conn, $strSQL);
  }

  echo json_encode($return);
  mysqli_close($conn);
  die();
}

if($stage == 'get_gallery'){
  if(
    (!isset($_POST['cid'])) ||
    (!isset($_POST['uid']))
  )
  {
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $cid = mysqli_real_escape_string($conn, $_POST['cid']);
  $rcid = $cid;
  $cid = base64_encode($cid);

  $strSQL = "SELECT * FROM dj3x_labor_media WHERE media_cid = '$cid'";
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

if($stage == 'delete_gallery'){
  if(
    (!isset($_POST['cid'])) ||
    (!isset($_POST['img_id'])) ||
    (!isset($_POST['uid']))
  )
  {
      echo "string";
      mysqli_close($conn);
      die();
  }

  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $cid = mysqli_real_escape_string($conn, $_POST['cid']);
  $img_id = mysqli_real_escape_string($conn, $_POST['img_id']);
  $rcid = $cid;
  $cid = base64_encode($cid);

  $strSQL = "DELETE FROM dj3x_labor_media WHERE ID = '$img_id'";
  $query = mysqli_query($conn, $strSQL);
  if($query){
    echo "Y";

    $strSQL = "SELECT * FROM dj3x_labor_media WHERE media_cid = '$cid'";
    $query = mysqli_query($conn, $strSQL);
    if(($query) && (mysqli_num_rows($query) > 0)){

    }else{
      $strSQL = "UPDATE dj3x_project2_status SET part_6_status = 'N' WHERE CID = '$cid'";
      $resultUpdate = mysqli_query($conn, $strSQL);
    }
  }
  mysqli_close($conn);
  die();
}

if($stage == 'add_part1'){
  if(
    (!isset($_POST['cid'])) ||
    (!isset($_POST['uid'])) ||
    (!isset($_POST['province'])) ||
    (!isset($_POST['fname'])) ||
    (!isset($_POST['lname']))
  )
  {
      mysqli_close($conn);
      die();
  }

  $cid = mysqli_real_escape_string($conn, $_POST['cid']);
  $rcid = $cid;
  $cid = base64_encode($cid);
  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $province = mysqli_real_escape_string($conn, $_POST['province']);
  $fname = mysqli_real_escape_string($conn, $_POST['fname']);
  $lname = mysqli_real_escape_string($conn, $_POST['lname']);
  $dd = mysqli_real_escape_string($conn, $_POST['dd']);
  $mm = mysqli_real_escape_string($conn, $_POST['mm']);
  $yy = mysqli_real_escape_string($conn, $_POST['yy']);
  $dob = $yy . '-' . $mm . '-' . $dd;
  $age = mysqli_real_escape_string($conn, $_POST['age']);
  $sex = mysqli_real_escape_string($conn, $_POST['sex']);
  $preg = mysqli_real_escape_string($conn, $_POST['preg']);
  $preg_month = mysqli_real_escape_string($conn, $_POST['preg_month']);
  $feeding = mysqli_real_escape_string($conn, $_POST['feeding']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $family_num = mysqli_real_escape_string($conn, $_POST['family_num']);
  $family_num_m = mysqli_real_escape_string($conn, $_POST['family_num_m']);
  $family_num_f = mysqli_real_escape_string($conn, $_POST['family_num_f']);
  $rel = mysqli_real_escape_string($conn, $_POST['rel']);
  $rel_o = mysqli_real_escape_string($conn, $_POST['rel_o']);
  $income = mysqli_real_escape_string($conn, $_POST['income']);
  $edu = mysqli_real_escape_string($conn, $_POST['edu']);
  $privilege = mysqli_real_escape_string($conn, $_POST['privilege']);
  $privilege_o = mysqli_real_escape_string($conn, $_POST['privilege_o']);
  $weight = mysqli_real_escape_string($conn, $_POST['weight']);
  $height = mysqli_real_escape_string($conn, $_POST['height']);

  $strSQL = "SELECT * FROM dj3x_laborinfo WHERE lb_cid = '$cid' AND lb_record_use = '1' ORDER BY lb_id DESC LIMIT 1";
  $resultCheck = mysqli_query($conn, $strSQL);
  if(($resultCheck) && (mysqli_num_rows($resultCheck) > 0)){

    $data = mysqli_fetch_assoc($resultCheck);

    $strSQL = "UPDATE dj3x_laborinfo SET lb_record_use = '0' WHERE lb_cid = '$cid'";
    $resultUpdate = mysqli_query($conn, $strSQL);

    if($resultUpdate){

      $ill = $data['lb_med_illness'];
      $ill_o = $data['lb_med_illness_i'];
      $drug = $data['lb_drug'];
      $drug_o = $data['lb_drug_i'];
      $smoke = $data['lb_smoke'];
      $smoke_o = $data['lb_smoke_i'];
      $alcohol = $data['lb_alcohol'];
      $alcohol_o = $data['lb_alcohol_i'];
      $hand_wash = $data['lb_hand_wash'];
      $health_check = $data['lb_health_check'];
      $health_check_o = $data['lb_health_check_i'];
      $assesment = $data['lb_health_assesmet'];
      $assesment_o = $data['lb_health_assesmet_i'];
      $vaccination = $data['lb_vaccination'];
      $vaccination_o = $data['lb_vaccination_i'];
      $workplace_status = $data['lb_address_status'];
      $home_no = $data['lb_home_no'];
      $home_moo = $data['lb_home_moo'];
      $home_road = $data['lb_home_road'];
      $home_prov = $data['lb_home_prov'];
      $home_dist = $data['lb_home_dist'];
      $home_subdist = $data['lb_home_subdist'];
      $work_no = $data['lb_work_no'];
      $work_moo = $data['lb_work_moo'];
      $work_road = $data['lb_work_road'];
      $work_prov = $data['lb_work_prov'];
      $work_dist = $data['lb_work_dist'];
      $work_subdist = $data['lb_work_subdist'];

      $strSQLInsert = "INSERT INTO dj3x_laborinfo
                            (
                              lb_cid, lb_fname, lb_lname, lb_dob, lb_mob, lb_yob, lb_full_dob, lb_current_age, lb_gender, lb_female_preg,
                              lb_female_preg_i, lb_female_feeding, lb_mariage_status, lb_education, lb_person_number_in_family, lb_pnumberf_m, lb_pnumberf_f, lb_religion, lb_religion_o, lb_income_status, lb_health_privilede, lb_health_privilede_o,
                              lb_weight, lb_height, lb_med_illness, lb_med_illness_i, lb_drug, lb_drug_i, lb_smoke, lb_smoke_i, lb_alcohol, lb_alcohol_i, lb_hand_wash, lb_health_check,
                              lb_health_check_i, lb_health_assesmet, lb_health_assesmet_i, lb_vaccination, lb_vaccination_i, lb_address_status, lb_home_no, lb_home_moo, lb_home_road,
                              lb_home_prov, lb_home_dist, lb_home_subdist, lb_work_no, lb_work_moo, lb_work_road, lb_work_prov, lb_work_dist, lb_work_subdist, lb_record_province,
                              lb_record_year, lb_record_uid, lb_record_datetime
                            )
                            VALUES (
                            '$cid', '$fname', '$lname', '$dd', '$mm', '$yy', '$dob', '$age', '$sex', '$preg',
                            '$preg_month', '$feeding', '$status', '$edu', '$family_num', '$family_num_m', '$family_num_f', '$rel', '$rel_o', '$income', '$privilege', '$privilege_o',
                            '$weight', '$height', '$ill', '$ill_o', '$drug', '$drug_o', '$smoke', '$smoke_o', '$alcohol', '$alcohol_o',
                            '$hand_wash', '$health_check', '$health_check_o', '$assesment', '$assesment_o', '$vaccination', '$vaccination_o', '$workplace_status', '$home_no', '$home_moo',
                            '$home_road', '$home_prov', '$home_dist', '$home_subdist', '$work_no', '$work_moo', '$work_road', '$work_prov', '$work_dist', '$work_subdist', '$province',
                            '$sysdateyear', '$uid', '$sysdatetime'
                            )
                            ";
      $resultInsert = mysqli_query($conn, $strSQLInsert);
      if($resultInsert){
        $strSQL = "SELECT * FROM dj3x_laborvisibility WHERE allow_labor_cid = '$cid' AND allow_labor_province = '$province'";
        $resultCheck2 = mysqli_query($conn, $strSQL);
        if(($resultCheck2) && (mysqli_num_rows($resultCheck2) > 0)){
          $strSQL = "UPDATE dj3x_laborvisibility SET allow_labor_status = '1' WHERE allow_labor_cid = '$cid' AND allow_labor_province = '$province'";
          $resultVisibility = mysqli_query($conn, $strSQL);
        }else{
          $strSQL = "INSERT INTO dj3x_laborvisibility (allow_labor_cid, allow_labor_province, allow_labor_status, allow_labor_udatetime) VALUES ('$cid', '$province', '1', '$sysdatetime')";
          $resultVisibility = mysqli_query($conn, $strSQL);
        }
      }else{
        echo $strSQLInsert;
      }

      $strSQL = "SELECT * FROM dj3x_project2_status WHERE CID = '$cid' AND r_year = '$sysdateyear'";
      $resultCheck3 = mysqli_query($conn, $strSQL);
      if(($resultCheck3) && (mysqli_num_rows($resultCheck3) > 0)){

      }else{
        $strSQL = "SELECT * FROM dj3x_project2_status WHERE CID = '$cid' ORDER BY ID DESC LIMIT 1";
        $resultCheck4 = mysqli_query($conn, $strSQL);
        if(($resultCheck4) && (mysqli_num_rows($resultCheck4) > 0)){
          $dataStatus = mysqli_fetch_assoc($resultCheck4);
          $part_1_status = $dataStatus['part_1_status'];
          $part_1_2_status = $dataStatus['part_1_2_status'];
          $part_1_3_status = $dataStatus['part_1_3_status'];
          $part_2_status = $dataStatus['part_2_status'];
          $part_2_2_status = $dataStatus['part_2_2_status'];
          $part_3_status = $dataStatus['part_3_status'];
          $part_4_status = $dataStatus['part_4_status'];
          $part_5_status = $dataStatus['part_5_status'];
          $part_6_status = $dataStatus['part_6_status'];
          $strSQL = "INSERT INTO dj3x_project2_status
                    (
                      CID, part_1_status, part_1_2_status, part_1_3_status, part_2_status,
                      part_2_2_status, part_3_status, part_4_status, part_5_status, part_6_status, r_year,r_province, r_udatetime
                    )
                    VALUES
                    (
                      '$cid', '$part_1_status', '$part_1_2_status', '$part_1_3_status', '$part_2_status', '$part_2_2_status',
                      '$part_3_status', '$part_4_status', '$part_5_status', '$part_6_status', '$sysdateyear', '$province', '$sysdatetime'
                    )
                    ";
                    mysqli_query($conn, $strSQL);
        }
      }

      echo "Y";

      $strSQL = "INSERT INTO dj3x_response (act_cid, act_uid, act, act_datetime) VALUES ('$cid', '$uid', 'Add labor', '$sysdatetime')";
      $resultInsertLog = mysqli_query($conn, $strSQL);
    }else{

    }

  }else{
    $strSQL = "INSERT INTO dj3x_laborinfo
                (lb_cid, lb_fname, lb_lname, lb_dob, lb_mob, lb_yob, lb_full_dob, lb_current_age, lb_gender, lb_female_preg,
                lb_female_preg_i, lb_female_feeding, lb_mariage_status, lb_education, lb_person_number_in_family, lb_pnumberf_m, lb_pnumberf_f, lb_religion, lb_religion_o, lb_income_status, lb_health_privilede, lb_health_privilede_o,
                lb_weight, lb_height, lb_record_province, lb_record_year, lb_record_uid, lb_record_datetime)
              VALUES
                (
                '$cid', '$fname', '$lname', '$dd', '$mm', '$yy', '$dob', '$age', '$sex', '$preg',
                '$preg_month', '$feeding', '$status', '$edu', '$family_num', '$family_num_m', '$family_num_f', '$rel', '$rel_o', '$income', '$privilege', '$privilege_o',
                '$weight', '$height', '$province', '$sysdateyear', '$uid', '$sysdatetime'
                )
              ";
    $resultInsert = mysqli_query($conn, $strSQL);
    if($resultInsert){
      $strSQL = "SELECT * FROM dj3x_laborvisibility WHERE allow_labor_cid = '$cid' AND allow_labor_province = '$province'";
      $resultCheck2 = mysqli_query($conn, $strSQL);
      if(($resultCheck2) && (mysqli_num_rows($resultCheck2) > 0)){
        $strSQL = "UPDATE dj3x_laborvisibility SET allow_labor_status = '1' WHERE allow_labor_cid = '$cid' AND allow_labor_province = '$province'";
        $resultVisibility = mysqli_query($conn, $strSQL);
      }else{
        $strSQL = "INSERT INTO dj3x_laborvisibility (allow_labor_cid, allow_labor_province, allow_labor_status, allow_labor_udatetime) VALUES ('$cid', '$province', '1', '$sysdatetime')";
        $resultVisibility = mysqli_query($conn, $strSQL);
      }

      $strSQL = "INSERT INTO dj3x_project2_status
                 (CID, part_1_status, r_year, r_province, r_udatetime)
                 VALUES
                 ('$cid', 'Y', '$sysdateyear', '$province', '$sysdatetime')
                ";
                mysqli_query($conn, $strSQL);
      echo "Y";

      $strSQL = "INSERT INTO dj3x_response (act_cid, act_uid, act, act_datetime) VALUES ('$cid', '$uid', 'Add labor', '$sysdatetime')";
      $resultInsertLog = mysqli_query($conn, $strSQL);

    }
  }
  mysqli_close($conn);
  die();
}

if($stage == 'add_part1_3'){
  if(
    (!isset($_POST['cid'])) ||
    (!isset($_POST['uid'])) ||
    (!isset($_POST['province']))
  )
  {
      mysqli_close($conn);
      die();
  }

  $cid = mysqli_real_escape_string($conn, $_POST['cid']);
  $rcid = $cid;
  $cid = base64_encode($cid);
  $uid = mysqli_real_escape_string($conn, $_POST['uid']);
  $province = mysqli_real_escape_string($conn, $_POST['province']);

  $add_status = mysqli_real_escape_string($conn, $_POST['address_status']);
  $homeno = mysqli_real_escape_string($conn, $_POST['homeno']);
  $homemoo = mysqli_real_escape_string($conn, $_POST['homemoo']);
  $homeroad = mysqli_real_escape_string($conn, $_POST['homeroad']);
  $homeprov = mysqli_real_escape_string($conn, $_POST['homeprov']);
  $homedist = mysqli_real_escape_string($conn, $_POST['homedist']);
  $homesubdist = mysqli_real_escape_string($conn, $_POST['homesubdist']);

  $workno = $homeno;
  $workmoo = $homemoo;
  $workroad = $homeroad;
  $workprov = $homeprov;
  $workdist = $homedist;
  $worksubdist = $homesubdist;
  $workplace_status = $add_status;

  if($add_status != 1){
    $workno = mysqli_real_escape_string($conn, $_POST['workno']);
    $workmoo = mysqli_real_escape_string($conn, $_POST['workmoo']);
    $workroad = mysqli_real_escape_string($conn, $_POST['workroad']);
    $workprov = mysqli_real_escape_string($conn, $_POST['workprov']);
    $workdist = mysqli_real_escape_string($conn, $_POST['workdist']);
    $worksubdist = mysqli_real_escape_string($conn, $_POST['worksubdist']);
  }

  $strSQL = "SELECT * FROM dj3x_laborinfo WHERE lb_cid = '$cid' AND lb_record_use = '1' ORDER BY lb_id DESC LIMIT 1";
  $resultCheck = mysqli_query($conn, $strSQL);
  if(($resultCheck) && (mysqli_num_rows($resultCheck) > 0)){

    $data = mysqli_fetch_assoc($resultCheck);

    $strSQL = "UPDATE dj3x_laborinfo SET lb_record_use = '0' WHERE lb_cid = '$cid'";
    $resultUpdate = mysqli_query($conn, $strSQL);

    if($resultUpdate){

      $fname = $data['lb_fname'];
      $lname = $data['lb_lname'];
      $dd = $data['lb_dob'];
      $mm = $data['lb_mob'];
      $yy = $data['lb_yob'];
      $dob = $data['lb_full_dob'];
      $age = $data['lb_current_age'];
      $sex = $data['lb_gender'];
      $preg = $data['lb_female_preg'];
      $preg_month = $data['lb_female_preg_i'];
      $feeding = $data['lb_female_feeding'];
      $status = $data['lb_mariage_status'];
      $edu = $data['lb_education'];
      $family_num = $data['lb_person_number_in_family'];
      $family_num_m = $data['lb_pnumberf_m'];
      $family_num_f = $data['lb_pnumberf_f'];
      $rel = $data['lb_religion'];
      $rel_o = $data['lb_religion_o'];
      $income = $data['lb_income_status'];
      $privilege = $data['lb_health_privilede'];
      $privilege_o = $data['lb_health_privilede_o'];
      $weight = $data['lb_weight'];
      $height = $data['lb_height'];
      $ill = $data['lb_med_illness'];
      $ill_o = $data['lb_med_illness_i'];
      $drug = $data['lb_drug'];
      $drug_o = $data['lb_drug_i'];
      $smoke = $data['lb_smoke'];
      $smoke_o = $data['lb_smoke_i'];
      $alcohol = $data['lb_alcohol'];
      $alcohol_o = $data['lb_alcohol_i'];
      $hand_wash = $data['lb_hand_wash'];
      $health_check = $data['lb_health_check'];
      $health_check_o = $data['lb_health_check_i'];
      $assesment = $data['lb_health_assesmet'];
      $assesment_o = $data['lb_health_assesmet_i'];
      $vaccination = $data['lb_vaccination'];
      $vaccination_o = $data['lb_vaccination_i'];
      $workplace_status = $data['lb_address_status'];
      $home_no = $data['lb_home_no'];
      $home_moo = $data['lb_home_moo'];
      $home_road = $data['lb_home_road'];
      $home_prov = $data['lb_home_prov'];
      $home_dist = $data['lb_home_dist'];
      $home_subdist = $data['lb_home_subdist'];
      $work_no = $data['lb_work_no'];
      $work_moo = $data['lb_work_moo'];
      $work_road = $data['lb_work_road'];
      $work_prov = $data['lb_work_prov'];
      $work_dist = $data['lb_work_dist'];
      $work_subdist = $data['lb_work_subdist'];

      $strSQLInsert = "INSERT INTO dj3x_laborinfo
                            (
                              lb_cid, lb_fname, lb_lname, lb_dob, lb_mob, lb_yob, lb_full_dob, lb_current_age, lb_gender, lb_female_preg,
                              lb_female_preg_i, lb_female_feeding, lb_mariage_status, lb_education, lb_person_number_in_family, lb_pnumberf_m, lb_pnumberf_f, lb_religion, lb_religion_o, lb_income_status, lb_health_privilede, lb_health_privilede_o,
                              lb_weight, lb_height, lb_med_illness, lb_med_illness_i, lb_drug, lb_drug_i, lb_smoke, lb_smoke_i, lb_alcohol, lb_alcohol_i, lb_hand_wash, lb_health_check,
                              lb_health_check_i, lb_health_assesmet, lb_health_assesmet_i, lb_vaccination, lb_vaccination_i, lb_address_status, lb_home_no, lb_home_moo, lb_home_road,
                              lb_home_prov, lb_home_dist, lb_home_subdist, lb_work_no, lb_work_moo, lb_work_road, lb_work_prov, lb_work_dist, lb_work_subdist, lb_record_province,
                              lb_record_year, lb_record_uid, lb_record_datetime
                            )
                            VALUES (
                            '$cid', '$fname', '$lname', '$dd', '$mm', '$yy', '$dob', '$age', '$sex', '$preg',
                            '$preg_month', '$feeding', '$status', '$edu', '$family_num', '$family_num_m', '$family_num_f', '$rel', '$rel_o', '$income', '$privilege', '$privilege_o',
                            '$weight', '$height', '$ill', '$ill_o', '$drug', '$drug_o', '$smoke', '$smoke_o', '$alcohol', '$alcohol_o',
                            '$hand_wash', '$health_check', '$health_check_o', '$assesment', '$assesment_o', '$vaccination', '$vaccination_o', '$workplace_status', '$homeno', '$homemoo',
                            '$homeroad', '$homeprov', '$homedist', '$homesubdist', '$workno', '$workmoo', '$workroad', '$workprov', '$workdist', '$worksubdist', '$province',
                            '$sysdateyear', '$uid', '$sysdatetime'
                            )
                            ";
      $resultInsert = mysqli_query($conn, $strSQLInsert);
      if($resultInsert){
        $strSQL = "SELECT * FROM dj3x_laborvisibility WHERE allow_labor_cid = '$cid' AND allow_labor_province = '$province'";
        $resultCheck2 = mysqli_query($conn, $strSQL);
        if(($resultCheck2) && (mysqli_num_rows($resultCheck2) > 0)){
          $strSQL = "UPDATE dj3x_laborvisibility SET allow_labor_status = '1' WHERE allow_labor_cid = '$cid' AND allow_labor_province = '$province'";
          $resultVisibility = mysqli_query($conn, $strSQL);
        }else{
          $strSQL = "INSERT INTO dj3x_laborvisibility (allow_labor_cid, allow_labor_province, allow_labor_status, allow_labor_udatetime) VALUES ('$cid', '$province', '1', '$sysdatetime')";
          $resultVisibility = mysqli_query($conn, $strSQL);
        }
      }else{
        // echo $strSQLInsert;
      }

      $strSQL = "UPDATE dj3x_project2_status SET part_1_3_status = 'Y' WHERE CID = '$cid'";
      $resultCheck3 = mysqli_query($conn, $strSQL);

      echo "Y";

      $strSQL = "INSERT INTO dj3x_response (act_cid, act_uid, act, act_datetime) VALUES ('$cid', '$uid', 'Add home or work address', '$sysdatetime')";
      $resultInsertLog = mysqli_query($conn, $strSQL);

    }else{
      echo "N2";
    }

  }else{
    echo "N1";
  }

  mysqli_close($conn);
  die();


}
