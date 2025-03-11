<?php
    require_once('Config.php');

    if (!empty($_POST['API'])) {
        switch ($_POST['API']) {
            case "Add" :
                $stmt = $Connection
                ->prepare("INSERT INTO `Shopee_Record` (`Order_Number`, `Order_Date`, `Buyer_Account`, `Goods`, `Amount_Income`, `Amount_Cost`, `Amount_Shipping`, `Amount_Actual`, `Other_Expenses`, `Order_Status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
                ->execute([
                    $_POST['Order_Number'],
                    $_POST['Order_Date'],
                    $_POST['Buyer_Account'],
                    $_POST['Goods'],
                    $_POST['Amount_Income'],
                    $_POST['Amount_Cost'],
                    $_POST['Amount_Shipping'],
                    ($_POST['Amount_Income']-$_POST['Amount_Cost']),
                    $_POST['Other_Expenses'],
                    $_POST['Order_Status']
                ]);
                break;
            case "Edit" :
                $stmt = $Connection
                ->prepare("UPDATE `Shopee_Record` SET `Order_Date`=?, `Buyer_Account`=?, `Goods`=?, `Amount_Income`=?, `Amount_Cost`=?, `Amount_Shipping`=?, `Amount_Actual`=?, `Other_Expenses`=?, `Order_Status`=? WHERE `Order_Number` LIKE ?")
                ->execute([
                    $_POST['Order_Date'],
                    $_POST['Buyer_Account'],
                    $_POST['Goods'],
                    $_POST['Amount_Income'],
                    $_POST['Amount_Cost'],
                    $_POST['Amount_Shipping'],
                    $_POST['Amount_Actual'],
                    $_POST['Other_Expenses'],
                    $_POST['Order_Status'],
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