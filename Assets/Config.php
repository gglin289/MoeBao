<?php 
	ini_set('display_errors','1');
	error_reporting(E_ALL);
	if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
		$location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $location);
		exit;
	}
	# 連結資料庫
    try {
        $Connection = new PDO('mysql:host=localhost;dbname=egnettw1_MoeBao;charset=utf8', 'egnettw1_MoeBao', 'RLUjkjYN5rdz');
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
?>