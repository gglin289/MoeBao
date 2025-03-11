<?php 
    require_once('../Assets/Config.php');
    header('Content-Type: application/json; charset=utf-8');
    if (!empty($_COOKIE["Token"])) {
        $Connection
        ->prepare("DELETE FROM `System_Token` WHERE `Access_Token` LIKE ?")
        ->execute([$_COOKIE["Token"]]);
        setcookie("Token","",time()-1000,"/");
        if (empty($_GET['UID'])) {
            $Json['Status']  = 'success' ; $Json['Message'] = "登出成功" ;
        } else {
            $Json['UserID'] = $_GET['UID'] ;
        }
        
        echo json_encode($Json);
    } else {
        header("Location: ../"); exit;
    }