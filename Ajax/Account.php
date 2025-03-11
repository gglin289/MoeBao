<?php require_once('Config.php');
if (empty($_POST["Type"])) {
	$Json['Status'] = "Error";$Json['Info'] = "重要參數錯誤或缺少。";
} else {
	switch ($_POST['Type']) {
		case 'Add':
			if (empty($_POST['UID'])) {
				$Json['Status'] = "Error" ; $Json['Msg'] = "重要參數不可為空";
			} else {
				$stmt = $Connection
				->prepare("INSERT INTO `System_Account` (`User_ID`,`User_Permission`,`User_Name`) VALUES (?, ?, ?)")
				->execute([
                    $_POST['UID'],
                    "1",
                    $_POST['NickName']
				]);
				if (!$stmt) {
					$Json['Status'] = "Error" ; $Json['ErrorCode'] = $Connection->errorCode();
				} else {
					$Json['Status'] = "Ok" ;
				}
			}
			break;
		case 'Edit':
			if (empty($_POST['UID'])) {
				$Json['Status'] = "Error" ; $Json['Msg'] = "重要參數不可為空";
			} else {
				$Json['Status'] = "Ok" ; $Json['Msg'] = "" ;
				if(!empty($_POST['Permission'])){
					$Connection->prepare("UPDATE `System_Account` SET `User_Permission`=? WHERE `User_ID` LIKE ?")
					->execute([
						$_POST['Permission'],
						$_POST['UID']
					]);
					$Json['Msg'] .= "『權限』";
				}
				if(!empty($_POST['Pin'])){
					$Connection->prepare("UPDATE `System_Account` SET `Login_Pin`=? WHERE `User_ID` LIKE ?")
					->execute([
						$_POST['Pin'],
						$_POST['UID']
					]);
					$Json['Msg'] .= "『ＰＩＮ』";
				}
				
			}
			break;
		case 'Cancel':
			if (empty($_POST['UID'])) {
				$Json['Status'] = "Error" ; $Json['Msg'] = "重要參數不可為空";
			} elseif ($_POST['UID'] == "Share") {
				$Json['Status'] = "Error" ; $Json['Msg'] = "系統保留帳號，不可停用。";
			} else {
				$Connection
				->prepare("DELETE FROM `System_Token` WHERE `User_ID` LIKE ?")
				->execute([
					$_POST['UID']
				]);
				$Connection->prepare("UPDATE `System_Account` SET `User_Permission`=(0-User_Permission) WHERE `User_ID` LIKE ?")
				->execute([
					$_POST['UID']
				]);
				$Json['Status'] = "Ok" ; $Json['Msg'] = "使用者已停用";
			}
			break;
		default:
			$Json['Status'] = "Error" ; $Json['Msg'] = "錯誤的狀態資訊。";
	}
}
echo json_encode($Json);