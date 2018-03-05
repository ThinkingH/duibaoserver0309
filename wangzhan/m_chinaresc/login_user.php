<?php 

session_start();
header("Content-type: text/html; charset=utf-8");
require_once('./interface/HyDb.php');

$phone    = $_POST['phone'];
$password = $_POST['password'];


$HyDb = new HyDb();



$sqlselect = "select * from users where phone='".$phone."' and password='".$password."' ";
$listselect = $HyDb->get_all($sqlselect);

if(count($listselect)>0){

	echo 'success';

}else{
	echo 'error';
}




?>
