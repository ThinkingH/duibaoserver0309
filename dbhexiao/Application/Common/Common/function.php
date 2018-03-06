<?php
/*
 * 封装公共函数
 *  */

function show($status,$message,$data=array()){
	
	$result = array(
			'code'  => $status,
			'message' => $message,
			'data'    => $data,
	);
	exit(json_encode($result));
	
}

//用户session的判断
function sessionUser(){
	$sitelist = session('adminUser');
	$siteid = $sitelist['id'];
	
	if(!$siteid){
		show('0','请进行登录');
	}
	
}
