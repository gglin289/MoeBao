<?php require_once('../Assets/Config.php');
    $Json = array();
    if ( empty ( $_GET['code'] ) ) {
        $Json['Status'] = "warning" ; $Json['Message'] = "驗證程序尚未準備完成" ;
    } elseif ($_GET['code'] == "Pin") {
        $sql = "SELECT `User_ID` FROM `System_Account` WHERE `Login_Pin` LIKE :Pin";
        $stmt = $Connection -> prepare($sql) ;
        $stmt -> bindValue(':Pin',$_POST['Pin']) ;
        $stmt -> execute() ;
        // print_r($stmt -> errorInfo()) ;
        $Account = $stmt -> fetch() ;
        if ($Account) {
            $_Token = "Pin." . md5(date('Y/m/d H:i:s')) ;
            $Connection
            ->prepare("INSERT INTO `System_Token` (`Access_Token`, `Token_Time`, `User_ID`) VALUES (?, ?, ?)")
            ->execute([
                $_Token,
                date('Y/m/d H:i:s'),
                $Account['User_ID']
            ]);
            $Connection
            ->prepare("UPDATE `System_Account` SET `Last_Time`=? WHERE `User_ID` LIKE ?")
            ->execute([
                date('Y/m/d H:i:s'),
                $Account['User_ID']
            ]);
            setcookie("Token",$_Token,time()+2592000,"/");
            $Json['Status']  = 'success' ; $Json['Message'] = "登入成功" ;
            header("Location: ../"); exit;
        } else {
            $Json['Status'] = "warning" ; $Json['Message'] = "PIN ERROR" ;
        }
    } else {
        $query = "";
        $query .= "grant_type=" . urlencode("authorization_code") . "&";
        $query .= "code=" . urlencode($_GET['code']) . "&";
        $query .= "redirect_uri=" . urlencode("https://wms.orglife.com.tw/Api/CallBack") . "&";
        $query .= "client_id=" . urlencode("1655167546") . "&";
        $query .= "client_secret=" . urlencode("682f9668c69c9751b5551e5532f43212");

        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: " . strlen($query),
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.line.me/oauth2/v2.1/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $query
        ));
        $response = json_decode(curl_exec($curl), true);
        $err = curl_error($curl);
        curl_close($curl);
        
        // 如果通過驗證就執行登入程序
        if ( $err ) {
            $Json['Status']  = 'warning' ; $Json['Message'] = "server_error"  ;
        } else {
            if (empty($response['access_token'])) {
                $Json = $response ;
            } else {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.line.me/v2/profile",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer " . $response['access_token']
                    )
                ));
                $Res = json_decode(curl_exec($curl), true);
                $Err = curl_error($curl);
                curl_close($curl);

                $Connection
                ->prepare("INSERT INTO `System_Token` (`Access_Token`, `Token_Type`, `Refresh_Token`, `Expires_In`, `Scope`, `Id_Token`, `Token_Time`, `User_ID`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
                ->execute([
                    $response['access_token'],
                    $response['token_type'],
                    $response['refresh_token'],
                    $response['expires_in'],
                    $response['scope'],
                    $response['id_token'],
                    date('Y/m/d H:i:s'),
                    $Res['userId']
                ]);
                $Connection
                ->prepare("UPDATE `System_Account` SET `User_Name`=?, `Picture_Url`=?, `Last_Time`=? WHERE `User_ID` LIKE ?")
                ->execute([
                    $Res['displayName'],
                    $Res['pictureUrl'],
                    date('Y/m/d H:i:s'),
                    $Res['userId']
                ]);
                setcookie("Token",$response["access_token"],time()+$response['expires_in'],"/");
                // if ($Res['userId'] == "Uc8ae82a3131e5ba6f158aad4a658b7bb") {
                //     setcookie("Token",$response["access_token"],time()+$response['expires_in'],"/");
                // } else {
                //     setcookie("Token",$response["access_token"],time()+7200,"/");
                // }
                $Json['Status']  = 'success' ; $Json['Message'] = "登入成功" ;
                header("Location: ../"); exit;
            }
        }
    }
    echo json_encode($Json);