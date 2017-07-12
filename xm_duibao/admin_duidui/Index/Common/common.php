<?php



//邮件发送封装函数
function hy_common_sendemail($mailto='', $subject='通知', $body='') {

	$data['mailto']  = $mailto;  //收件人
	$data['subject'] = '=?UTF-8?B?'.base64_encode($subject).'?='; //邮件标题
	$data['body']    = $body;    //邮件正文内容

	if($mailto==''||$body=='') {
		return '错误，参数不全';
	}else {
		import('ORG.HyEmail');
		$HyEmail = new HyEmail();

		if($HyEmail->send($data)) {
			return 'ok';
		}else {
			return 'err';
		}
	}


}





function hy_province_config() {
	
	$arr = array (
			'上海','云南','北京','吉林',
			'四川','天津','宁夏','安徽','山东',
			'山西','广东','广西','新疆','江苏',
			'江西','河北','河南','浙江','海南',
			'湖北','湖南','甘肃','福建','西藏',
			'贵州','辽宁','重庆','陕西','青海',
			'内蒙古','黑龙江',
	);
	
	return $arr;
	
}



function hy_caozuo_logwrite($logstr='',$ttype='unknown') {
	//ttype，操作模块标识名称
	$logstr = trim($logstr);
	if($logstr=='') {
		return false;
	}else {
		$logstr = base64_encode($logstr);
		$who = session(HYSESSQZ.'username');
		
		$sql_insertlog   = "insert into caozuo_log 
							(who,ttype,content,create_datetime) values(
							'".$who."','".$ttype."','".$logstr."','".date('Y-m-d H:i:s')."')";
		$Model = new Model();
		$Model->execute($sql_insertlog);
		
		return true;
		
	}
	
	
	
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
function loginjudge($ylock = '135') {

	//定义返回数据的数组
	$lockarr['grade']    = 'B';  //A代表alert+error  B代表exit  C代表成功
	$lockarr['errormsg'] = '';   //error信息
	$lockarr['alertmsg'] = '';   //alert信息
	$lockarr['exitmsg']  = '';   //exit信息
	
	
	//判断用户是否登录------start------
	$username  = session(HYSESSQZ.'username');
	$password  = session(HYSESSQZ.'password');
	$rootflag  = session(HYSESSQZ.'rootflag'); //用户权限标识字段1，3，5，7，9---root>9
	$lockflag  = session(HYSESSQZ.'lockflag');
	
	if($username!='' && $password!='') {
		//说明用户已经登陆了，session中保存有用户的信息
		
		if($username=='root') {
			//root用户不做权限判断，直接通过
			$lockarr['grade'] = 'C';
			
		}else {
			
			//首先判断该账号是否被禁用
			if($lockflag==-1) {
				//清空session，强制用户下线
				session(HYSESSQZ.'username',null);
				session(HYSESSQZ.'password',null);
				session(HYSESSQZ.'xingming',null); //用户姓名
				session(HYSESSQZ.'rootflag',null); //权限标识
				session(HYSESSQZ.'lockflag',null); //禁用标识
				session(HYSESSQZ.'resetflag',null); //强制用户重置密码标识
				session(HYSESSQZ.'lastLoginTime',null);
				session(HYSESSQZ.'lastLoginIp',null);
				
				//提示用户账号被禁用
				$lockarr['grade']    = 'A';
				$lockarr['alertmsg'] = "<script>alert('您的账号被禁用，请联系超级管理员解锁后再次进行登陆！');top.location.href='".__APP__."' </script>";
				$lockarr['errormsg'] = '您的账号被禁用，请联系超级管理员解锁后再次进行登陆！';
				
			}else {
				
				//start权限===============================================
				//判断用户是否有进入此页面的权限
				
				//------------------------------------------------------------------------------
				//判断用户的标识字段是否为大于0小于9的数字
				if(is_numeric($rootflag) && $rootflag>0 && $rootflag<10) {
					//权限标识判断通过
					
				}else {
					//提示权限标识不正确
					$lockarr['grade']    = 'A';
					$lockarr['alertmsg'] = "<script>alert('您的账号权限判断存在错误，系统将强制下线，请重新登录！');top.location.href='".__APP__."' </script>";
					$lockarr['errormsg'] = '您的账号权限判断存在错误，系统将强制下线，请重新登录！';
					
					//清空session,禁止非法标识用户登录系统
					session(HYSESSQZ.'username',null);
					session(HYSESSQZ.'password',null);
					session(HYSESSQZ.'xingming',null); //用户姓名
					session(HYSESSQZ.'rootflag',null); //权限标识
					session(HYSESSQZ.'lockflag',null); //禁用标识
					session(HYSESSQZ.'resetflag',null); //强制用户重置密码标识
					session(HYSESSQZ.'lastLoginTime',null);
					session(HYSESSQZ.'lastLoginIp',null);
					
				}
				
				
				//------------------------------------------------------------------------------
				//将权限字符串切割成数组
				$quanxianarr = str_split($ylock);
				
				if(in_array($rootflag,$quanxianarr)) {
					//通过页面权限判断
					$lockarr['grade'] = 'C';
					
				}else {
					//没有对应页面操作权限
					$lockarr['grade'] = 'B';
					$lockarr['exitmsg'] = '<h1><br/>&nbsp;&nbsp;&nbsp;权限错误，您没有进入该页面的权限&nbsp;&nbsp;&nbsp;'.date('Y-m-d H:i:s').'</h1>';
					
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


