<?php
    require_once('Config.php');

    if (!empty($_POST['API'])) {
        switch ($_POST['API']) {
            case "Add" :
                $stmt = $Connection
                ->prepare("INSERT INTO `Reserve_Record` (`System_ID`, `Pet_ID`, `Time`, `Time_End`, `Content`, `Remark`) VALUES (?, ?, ?, ?, ?, ?)")
                ->execute([
                    "R" . time(),
                    $_POST['PID'],
                    $_POST['Time'],
                    $_POST['Time_End'],
                    $_POST['Content'],
                    $_POST['Remark']
                ]);
                break;
            case "Edit" :
                $stmt = $Connection
                ->prepare("UPDATE `Reserve_Record` SET `Time`=?, `Time_End`=?, `Content`=?, `Remark`=? WHERE `System_ID` LIKE ?")
                ->execute([
                    $_POST['Time'],
                    $_POST['Time_End'],
                    $_POST['Content'],
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