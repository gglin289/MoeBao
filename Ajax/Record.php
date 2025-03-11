<?php
    require_once('Config.php');

    if (!empty($_POST['API'])) {
        switch ($_POST['API']) {
            case "Add" :
                if(empty($_POST['Discount'])){$_POST['Discount']="";}
                if(empty($_POST['Time'])){$_POST['Time']=date('Y-m-d H:i:s');}
                if (preg_match("/加班費|租金|水電|利息|分期付款|進貨款項|其他/", $_POST['Type'])) {
                    if ($_POST['Amount'] > 0) {
                        $_POST['Amount'] = (0 - $_POST['Amount']) ;
                    }
                }
                $stmt = $Connection
                ->prepare("INSERT INTO `Service_Record` (`System_ID`, `Pet_ID`, `Time`, `Type`, `Content`, `Discount`, `Amount`, `Remark`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
                ->execute([
                    "R" . time(),
                    $_POST['PID'],
                    $_POST['Time'],
                    $_POST['Type'],
                    $_POST['Content'],
                    $_POST['Discount'],
                    $_POST['Amount'],
                    $_POST['Remark']
                ]);
                break;
            case "Edit" :
                $stmt = $Connection
                ->prepare("UPDATE `Service_Record` SET `Time`=?, `Type`=?, `Content`=?, `Discount`=?, `Amount`=?, `Remark`=? WHERE `System_ID` LIKE ?")
                ->execute([
                    $_POST['Time'],
                    $_POST['Type'],
                    $_POST['Content'],
                    $_POST['Discount'],
                    $_POST['Amount'],
                    $_POST['Remark'],
                    $_POST['ID']
                ]);
                break;
        }
        if (!$stmt) {
            $Json['ErrorCode'] = $Connection->errorCode();
        } else {
            $Json['Status'] = "Ok" ;
        }
    }

    echo json_encode($Json);