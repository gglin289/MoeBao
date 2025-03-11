<?php
    require_once('Config.php');

    if (!empty($_POST['API'])) {
        switch ($_POST['API']) {
            case "Add" :
                $Json['ID'] = "P" . time() ;
                $stmt = $Connection
                ->prepare("INSERT INTO `Pet_Information` (`System_ID`, `Customer_Name`, `Customer_Phone_1`, `Customer_Phone_2`, `Customer_Phone_3`, `Customer_Address`, `Pet_Name`, `Pet_Sex`, `Pet_Breed`, `Pet_Size`, `Pet_Age`, `Pet_Remark`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
                ->execute([
                    $Json['ID'],
                    $_POST['Customer_Name'],
                    $_POST['Customer_Phone_1'],
                    $_POST['Customer_Phone_2'],
                    $_POST['Customer_Phone_3'],
                    $_POST['Customer_Address'],
                    $_POST['Pet_Name'],
                    $_POST['Pet_Sex'],
                    $_POST['Pet_Breed'],
                    $_POST['Pet_Size'],
                    $_POST['Pet_Age'],
                    $_POST['Pet_Remark']
                ]);
                break;
            case "Edit" :
                $stmt = $Connection
                ->prepare("UPDATE `Pet_Information` SET `Customer_Name`=?, `Customer_Phone_1`=?, `Customer_Phone_2`=?, `Customer_Phone_3`=?, `Customer_Address`=?, `Pet_Name`=?, `Pet_Sex`=?, `Pet_Breed`=?, `Pet_Size`=?, `Pet_Age`=?, `Pet_Amount`=?, `Pet_Remark`=? WHERE `System_ID` LIKE ?")
                ->execute([
                    $_POST['Customer_Name'],
                    $_POST['Customer_Phone_1'],
                    $_POST['Customer_Phone_2'],
                    $_POST['Customer_Phone_3'],
                    $_POST['Customer_Address'],
                    $_POST['Pet_Name'],
                    $_POST['Pet_Sex'],
                    $_POST['Pet_Breed'],
                    $_POST['Pet_Size'],
                    $_POST['Pet_Age'],
                    $_POST['Pet_Amount'],
                    $_POST['Pet_Remark'],
                    $_POST['ID']
                ]);
                // 同組資料更新
                $Connection
                ->prepare("UPDATE `Pet_Information` SET `Customer_Phone_2`=?, `Customer_Phone_3`=?, `Customer_Address`=? WHERE `Customer_Name` LIKE ? AND `Customer_Phone_1` LIKE ?")
                ->execute([
                    $_POST['Customer_Phone_2'],
                    $_POST['Customer_Phone_3'],
                    $_POST['Customer_Address'],
                    $_POST['Customer_Name'],
                    $_POST['Customer_Phone_1']
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