<?php
function checkTimeline($conn, $role, $stage){
    echo 0;
}

function displayRole($role){
  if($role == 'ec'){
    return "เลขา EC";
  }
}

function DateThai($strDate, $dateonly){
    if($strDate != NULL){
      $b = explode(' ', $strDate);
      if(sizeof($b) > 1){ // Input is date and time
        // echo "Date dant time input ";
        $b2 = explode('-', $b[0]);
        if($b2[0] > date('Y') + 50){
          $newbb = intval($b2[0]) - 543;
          $bnew = $newbb . "-" . $b2[1] . "-" . $b2[2]. " " . $b[1];
          $strDate = $bnew;
          // if((($b2[0] + 543) - (date('Y') + 543)) == 543){ // พ.ศ.
          //   // echo "IN BD";
          //   $newbb = intval($b2[0]) - 543;
          //   $bnew = $newbb . "-" . $b2[1] . "-" . $b2[2]. " " . $b[1];
          //   $strDate = $bnew;
          // }
        }
      }
      else // Input is date only
      {
        // echo "Date only ";
        $b2 = explode('-', $b[0]);
        if($b2[0] > date('Y') + 50){
          $newbb = intval($b2[0]) - 543;
          $bnew = $newbb . "-" . $b2[1] . "-" . $b2[2];
          $strDate = $bnew;
          // if((($b2[0] + 543) - (date('Y') + 543)) == 543){ // พ.ศ.
          //   // echo "IN BD";
          //   $newbb = intval($b2[0]) - 543;
          //   $bnew = $newbb . "-" . $b2[1] . "-" . $b2[2];
          //   $strDate = $bnew;
          // }else{
          //   // echo "Not in BD";
          // }
        }
      }
      $strYear = date("Y",strtotime($strDate)) + 543;
      // if(($strYear + 543) > (date('Y') + 543 + 100)){
      //   $strYear = date("Y",strtotime($strDate)) - 543;
      // }
  		$strMonth= date("n",strtotime($strDate));
  		$strDay= date("j",strtotime($strDate));
  		$strHour= date("H",strtotime($strDate));
  		$strMinute= date("i",strtotime($strDate));
  		$strSeconds= date("s",strtotime($strDate));
  		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  		$strMonthThai=$strMonthCut[$strMonth];
      if($dateonly){
        return "$strDay $strMonthThai $strYear";
      }else{
        return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
      }
    }else{
      return "NA";
    }
}

function DateThaiFull($strDate, $dateonly){
    if($strDate != NULL){
      $b = explode('-', $strDate);
      if(sizeof($b) > 1){
        if($b[0] > date('Y')){
          $bnew = ($b[0] - 543) . $b['1'];
          $strDate = $bnew;
        }
      }
      $strYear = date("Y",strtotime($strDate)) + 543;
  		$strMonth= date("n",strtotime($strDate));
  		$strDay= date("j",strtotime($strDate));
  		$strHour= date("H",strtotime($strDate));
  		$strMinute= date("i",strtotime($strDate));
  		$strSeconds= date("s",strtotime($strDate));
  		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
  		$strMonthThai=$strMonthCut[$strMonth];
      if($dateonly){
        return "$strDay $strMonthThai พ.ศ. $strYear";
      }else{
        return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
      }
    }else{
      return "NA";
    }
}

function DateDiff($start, $end, $unit){
  if($unit == 'day'){
    $date1 = strtotime($start." 00:00:00");
    $date2 = strtotime($end." 00:00:00");
    $diff = ($date2 - $date1)/60/60/24;
    return $diff;
  }
}

function DateDiff2($start, $end){
  $bd1 = explode("-", $start);
  $bd2 = explode("-", $end);
  $start = $bd1[2]."-".$bd1[1]."-".$bd1[0];
  $end = $bd2[2]."-".$bd2[1]."-".$bd2[0];
  $datetime1 = date_create($start);
  $datetime2 = date_create($end);

  // Calculates the difference between DateTime objects
  $interval = date_diff($datetime1, $datetime2);

  // Display the result
  echo $interval->format('Difference between two dates: %R%a days');
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
