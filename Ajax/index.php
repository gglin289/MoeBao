<?php
    require_once('Config.php');
    $Json = array('Data'=>array()) ;
    if (!empty($_GET['S'])) {
        $Json['Data'] = $Connection->query("SELECT * FROM `Pet_Information` 
        WHERE `Customer_Name` LIKE '%{$_GET['S']}%' 
        OR `Customer_Phone_1` LIKE '%{$_GET['S']}%' 
        OR `Customer_Phone_2` LIKE '%{$_GET['S']}%' 
        OR `Customer_Phone_3` LIKE '%{$_GET['S']}%' 
        OR `Pet_Name` LIKE '%{$_GET['S']}%' 
        ORDER BY `System_ID` ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
    echo json_encode($Json);