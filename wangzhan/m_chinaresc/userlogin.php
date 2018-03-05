<?php
//session_start();
header("Content-type: text/html; charset=utf-8");
require_once('./interface/HyDb.php');

$phone    = $_POST['MobilePhone'];
$password = md5($_POST['Password']);


$HyDb = new HyDb();



	$sqlselect = "select * from users where phone='".$phone."' and password='".$password."' ";
	//echo $sqlselect;
	$listselect = $HyDb->get_all($sqlselect);
	
	 if(count($listselect)>0){
		
		
		echo '<script>alert("登录成功！");window.location.href="index.php";</script>';
		
	}else{
		echo '<script>alert("登录成功失败！");window.location.href="login.php";</script>';
	}








?>