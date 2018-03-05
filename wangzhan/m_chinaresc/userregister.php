<?php

//开启session
session_start();

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

require_once('./interface/HyDb.php');

$phone     = $_POST['MobilePhone'];
$code      = $_POST['ValidationCode'];
$Password = $_POST['Password'];
$name     = $_POST['UserTrueName'];
$company  = $_POST['CompanyName'];
$submit   = $_POST['btnregister'];


if($submit!=''){
		
		if($phone==''){
			echo "<script>alert('请输入手机号！'); history.go(-1);</script>";
			exit('手机号为空！');
		}else if(strlen($phone)!=11 || !is_numeric($phone)){
			echo "<script>alert('请输入正确的手机号！'); history.go(-1);</script>";
			exit('手机号错误！');
		}
		
		if($Password==''){
			echo "<script>alert('请输入密码！'); history.go(-1);</script>";
			exit('密码为空！');
		}else if(strlen($Password)<6){
			echo "<script>alert('密码长度要6位以上！'); history.go(-1);</script>";
			exit('密码长度要6位以上！');
		}
		
		
		if($code==''){
			echo "<script>alert('请输入短信验证码！'); history.go(-1);</script>";
			exit('请输入短信验证码！');
		}
		
		
		$HyDb = new HyDb();
		//判断该手机是否注册过
		$sql_phone = "select phone from users where phone='".$phone."' "; 
		$list_phone = $HyDb->get_all($sql_phone);
		
		if(count($list_phone)>0){
			echo "<script>alert('该手机已经注册过，请直接登录！'); history.go(-1);</script>";
			exit('该手机已经注册过，请直接登录！');
		}
		
		
		//判断输入的验证码是否正确
		if($_SESSION['code']!= $code){
			
			echo "<script>alert('短信验证码输入错误！'); history.go(-1);</script>";
			exit('短信验证码输入错误！');
		}
			
		//数据入库操作
		$date= date('y-m-d h:i:s',time());
		
		
		//注册的信息存入数据库
		$sqlphonedata = "insert into users (phone,password,company,lianxiren,createtime) values ('".$phone."','".md5($Password)."','".$company."','".$name."','".$date."')";
		$listsql      = $HyDb->execute($sqlphonedata);
		
		if($listsql){
		
			echo "<script>alert('用户注册成功！');window.location.href='login.php';</script>";
		
		}else{
			echo "<script>alert('用户注册失败！'); history.go(-1);</script>";
			exit('用户注册失败！');
		}
		
	}