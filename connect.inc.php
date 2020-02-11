<?php
$host = DB_HOST;
$user = DB_USER;
$password = DB_PASSWORD;
$dbname = DB_NAME;
$db_prefix = TB_PREFIX;

$conn = mysqli_connect($host, $user, $password, $dbname);
if(!$conn){
  // echo $host."<br>";
  // echo $user."<br>";
  // echo $password."<br>";
  // echo $dbname."<br>";
  echo "Can not connect database";
  die();
}

$conn->set_charset("utf8");

?>
