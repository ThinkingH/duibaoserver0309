<?php

//判断用户的登录权限
function quanxiancheck($start,$end){

	//用户审核权限的判断
	$checkflag = session(HYSESSQZ.'checkflag'); //账单审核权限判断字符串

	if(substr($checkflag,$start,$end)!='Y'){
		return false;
	}else{
		return true;
	}
}


//type类型数组config函数
function hy_type_config() {
	$arr = array(
			'105',
			'106',
			'107',
			'300',
			'410',
			'604',
			'606',
	);
	
	return $arr;
	
}



//密码强度判断函数
function yu_passwdStrength($str) {
	
	$score = 0;
	if(preg_match("/[0-9]+/",$str)) {
		$score ++;
	}
	if(preg_match("/[0-9]{3,}/",$str)) {
		$score ++;
	}
	if(preg_match("/[a-z]+/",$str)) {
		$score ++;
	}
	if(preg_match("/[a-z]{3,}/",$str)) {
		$score ++;
	}
	if(preg_match("/[A-Z]+/",$str)) {
		$score ++;
	}
	if(preg_match("/[A-Z]{3,}/",$str)) {
		$score ++;
	}
	if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)|.|,|:|;]+/",$str)) {
		$score += 2;
	}
	if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)|.|,|:|;]{3,}/",$str)) {
		$score ++ ;
	}
	if(strlen($str) >= 10) {
		$score ++;
	}
	
	return $score;
	
}


//登陆判断封装模块
function loginjudge($ylock = 'nolock') {

	//定义返回数据的数组
	$lockarr['grade']    = 'B';  //A代表alert+error  B代表exit  C代表成功
	$lockarr['errormsg'] = '';   //error信息
	$lockarr['alertmsg'] = '';   //alert信息
	$lockarr['exitmsg']  = '';   //exit信息
	
	
	//判断用户是否登录------start------
	$username  = session('username');
	$password  = session('password');
	$rootflag  = session('rootflag');
	$lockflag  = session('lockflag');
	
	
	if($username!='' && $password!='') {
		//说明用户已经登陆了，session中保存有用户的信息
		
		//判断用户是否被禁用
		if($rootflag==9) {
			//超级用户不会被禁止，无条件通过
			$lockarr['grade'] = 'C';
			
		}else {
			//对于非超级用户需要判断其是否被禁用
			if($lockflag==-1) {
				//清空session，强制用户下线
				session(null);
				//提示用户账号被禁用
				$lockarr['grade']    = 'A';
				$lockarr['alertmsg'] = "<script>alert('您的账号被禁用，请联系超级管理员解锁后再次进行登陆！');top.location.href='".__APP__."' </script>";
				$lockarr['errormsg'] = '您的账号被禁用，请联系超级管理员解锁后再次进行登陆！';
				
			}else {
				
				//start权限===============================================
				//判断用户是否有进入此页面的权限
					
				if($ylock=='alllock') {
					//禁止除超级管理员用户外的所有用户
					if($rootflag!=9) {
						$lockarr['grade'] = 'B';
						$lockarr['exitmsg'] = '<h1><br/>&nbsp;&nbsp;&nbsp;&nbsp;您没有进入此页面的权限，此页面需要超级权限&nbsp;&nbsp;&nbsp;'.mt_rand(111,999).'</h1>';
					}else {
						$lockarr['grade'] = 'C';
					}
				}else if($ylock=='semilock') {
					//禁止所有普通用户
					if(!($rootflag==9 || $rootflag==5 || $rootflag==7 || $rootflag==1)) {
						$lockarr['grade'] = 'B';
						$lockarr['exitmsg'] = '<h1><br/>&nbsp;&nbsp;&nbsp;&nbsp;您没有进入此页面的权限，此页面需要高级或高级以上权限&nbsp;&nbsp;&nbsp;'.mt_rand(111,999).'</h1>';
					}else {
						$lockarr['grade'] = 'C';
					}
				}else if($ylock=='nolock') {
					//禁止所有普通用户
					if(!($rootflag==9 || $rootflag==5 || $rootflag==3 || $rootflag==7 || $rootflag==1)) {
						$lockarr['grade'] = 'B';
						$lockarr['exitmsg'] = '<h1><br/>&nbsp;&nbsp;&nbsp;&nbsp;您没有进入此页面的权限，此页面需要普通或普通以上权限&nbsp;&nbsp;&nbsp;'.mt_rand(111,999).'</h1>';
						//清空session,禁止非法标识用户登录系统
						session(null);
					}else {
						$lockarr['grade'] = 'C';
					}
				}else if($ylock=='nonlock'){
					
					if(!( $rootflag==5 || $rootflag==7)) {
						$lockarr['grade'] = 'B';
						$lockarr['exitmsg'] = '<h1><br/>&nbsp;&nbsp;&nbsp;&nbsp;您没有进入此页面的权限，此页面需要普通或普通以上权限&nbsp;&nbsp;&nbsp;'.mt_rand(111,999).'</h1>';
						//清空session,禁止非法标识用户登录系统
						session(null);
					}else {
						$lockarr['grade'] = 'C';
					}
				}else{
					$lockarr['grade'] = 'B';
					$lockarr['exitmsg'] = '系统错误，请联系管理员处理';
					//权限标识错误，非法操作，强制退出
					//清空当前session，确保该用户能够正常进行再次登录
					//session(null);
				}
				//end权限===============================================
				
				
			}
			
		}
		
		
	}else {
		//用户非法进入页面
		$lockarr['grade']    = 'A';
		$lockarr['alertmsg'] = "<script>alert('您尚未登陆，请登录后再进入此页面！');top.location.href='".__APP__."' </script>";
		$lockarr['errormsg'] = '您尚未登陆不能进入此页面!';
		
	}
	
	
	//返回判断执行结果数组
	return $lockarr;
	
	

}


