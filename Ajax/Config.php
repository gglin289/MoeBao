<?php
  require_once('../Assets/Config.php');
  ini_set('display_errors','1');
  error_reporting(E_ALL);
  header('Content-Type: application/json; charset=utf-8');
  $Json = array();
  if (empty($_COOKIE['Token'])) {
    $Json['Status'] = "Error"; $Json['Info'] = "Token cannot be empty";
  } else {
    $TokenInfo = $Connection->query("SELECT * FROM `System_Token` WHERE `Access_Token` LIKE '{$_COOKIE["Token"]}'")->fetch(PDO::FETCH_ASSOC);
    if (empty($TokenInfo)) {
      $Json['Status'] = "Error"; $Json['Info'] = "Token has expired";
    } else {
      $UserInfo = $Connection->query("SELECT * FROM `System_Account` WHERE `User_ID` LIKE '{$TokenInfo['User_ID']}'")->fetch(PDO::FETCH_ASSOC);
      if (empty($UserInfo)) {
        $Json['Status'] = "Error"; $Json['Info'] = "User Info Error";
      }
    }
  }
  if ((!empty($Json['Status'])) && ($Json['Status'] == "Error")) {
      echo json_encode($Json);
      exit ;
  }