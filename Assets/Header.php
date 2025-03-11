<?php 
	ini_set('display_errors','1');
	error_reporting(E_ALL);
	require_once('Config.php');
	if (empty($_COOKIE["Token"])) {
		header("Location: Login"); exit;
	} else {
		$TokenInfo = $Connection->query("SELECT * FROM `System_Token` WHERE `Access_Token` LIKE '{$_COOKIE["Token"]}'")->fetch(PDO::FETCH_ASSOC);
		if (empty($TokenInfo)) {
			header("Location: Login"); exit;
		} else {
			$UserInfo = $Connection->query("SELECT * FROM `System_Account` WHERE `User_ID` LIKE '{$TokenInfo["User_ID"]}'")->fetch(PDO::FETCH_ASSOC);
			if (empty($UserInfo)) {
				header("Location: Ajax/Logout?UID=" . $TokenInfo["User_ID"]); exit;
			} else {
				if ($UserInfo['User_Permission'] < 0) {
					header("Location: Login?Error=Permission"); exit;
				} else {
					if ($Page_Permission > $UserInfo['User_Permission']) {
						header("Location: ../?Error=Permission"); exit;
					}
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="zh-tw">
	<head>
		<meta charset="utf-8">
		<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
        <meta name="mobile-web-app-capable" content="yes" />
		<title><? echo $Page_Name . " | 萌寶寵物澡堂"; ?></title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://kit.fontawesome.com/501cae341d.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
		<script src="Assets/js/System.js"></script>
		<script src="Assets/js/clipboard.min.js"></script>
		<style>
			body {font-family: 'Noto Sans TC',sans-serif;font-weight: 500;padding-top: 70px;}
			.card {box-shadow: 1px 2px 3px rgba(204, 197, 185, 0.5);}
			.alert {box-shadow: 1px 2px 3px rgba(204, 197, 185, 0.5);}
		</style>
	</head>
    
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
			<div class="container">
				<a class="navbar-brand" href=".">管理系統</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="Dash_Month"> <i class="fa-solid fa-gauge-simple fa-fw"></i> 報表資料 </a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="Reserve"> <i class="fa-solid fa-calendar"></i> 預約紀錄 </a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="Reserve_Dash"> <i class="fa-solid fa-calendar"></i> 預約日曆 </a>
						</li>
						<?php if ($UserInfo['User_Permission'] >= 9) { ?><li class="nav-item">
							<a class="nav-link" href="System_Account"> <i class="fa-solid fa-users-gear fa-fw"></i> 登入管理 </a>
						</li><?php } ?>
					</ul>
					<ul class="navbar-nav d-flex">
						<li class="nav-item">
							<a class="nav-link" href="javascript:;" onclick="Logout();" title="<?php echo $UserInfo['User_ID']; ?>"> <?php echo $UserInfo['User_Name']; ?> </a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container<?php if ( !empty($container) ) { echo "-fluid" ; } ; ?>">
