<?php 

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

require_once('./interface/HyDb.php');

$phone    = $_POST['phone'];
$content  = $_POST['fabucontent'];
$name     = $_POST['UserTrueName'];
$company  = $_POST['CompanyName'];
$submit   = $_POST['btnregister'];

if($submit!=''){
	
	if($phone==''){
		echo "<script>alert('手机号为空！'); history.go(-1);</script>";
		exit('手机号为空！');
	}
	
	 if($content==''){
		echo "<script>alert('需求信息不能为空！'); history.go(-1);</script>";
		exit('需求信息不能为空！');
	} 
	
	if($company==''){
		echo "<script>alert('公司名称不能为空！'); history.go(-1);</script>";
		exit('公司名称不能为空！');
	}
	
	if($name==''){
		echo "<script>alert('联系人不能为空！'); history.go(-1);</script>";
		exit('联系人不能为空不能为空！');
	}
	
	$HyDb = new HyDb();
	
	$sql = "insert into zt_dingdan(userphone,company,phone,comment) values('".$phone."','".$company."','".$phone."','".$content."')";
	//echo $sql;
	$list = $HyDb->execute($sql);
	
	 if($list){
		
		echo "<script>alert('信息发布成功！');window.location.href='index.php';</script>";
		
		}else{
			echo "<script>alert('信息发布失败！'); history.go(-1);</script>";
			exit('信息发布失败！');
		} 
		
	
}



?>