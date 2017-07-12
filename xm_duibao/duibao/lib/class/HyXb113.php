<?php
/*
 * 匿名用户登录
 */

class HyXb113 extends HyXb{
	
	
	//数据的初始化
	function __construct($input_data){
		
		//数据初始化
		parent::__construct($input_data);
		
		//日志数据开始写入
		$tmp_logstr   = "\n".'BEGINXB--------------------BEGIN--------------------BEGIN'."\n".
						date('Y-m-d H:i:s').'    request_uri:    '.$_SERVER["REQUEST_URI"]."\n".
						HyItems::hy_array2string($input_data)."\n";
		parent::hy_log_str_add($tmp_logstr);
		unset($tmp_logstr);
		
	}
	
	
	//用户匿名登录操作
	protected function controller_hidelogin(){
		
		//时间戳+随机数生成的临时id
		$tempuserid = parent::func_create_randid();
		
		//用户名+time()+随机数生成的userkey
		$tempuserkey = parent::func_create_randkey();
		
		$temparr= array(
				array(
						'userid'  => $tempuserid, 
						'userkey' => $tempuserkey,
				),
				
		);
				
		//临时用户数据插入到用户临时表中xb_temp_user
		$tempuser_sql = "insert into xb_temp_user(id,tokenkey,create_datetime) values ('".$tempuserid."','".$tempuserkey."','".parent::__get('create_datetime')."')";
		$tempuser_list = parent::__get('HyDb')->execute($tempuser_sql);
		
		
		
		if($tempuser_list===true){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '匿名登录成功';
			$echoarr['dataarr'] = $temparr;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '匿名登录失败';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	}
	
	
	
	//匿名登录的操作入口
	public function controller_init(){
		
		
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
		
		
		//进行匿名登录操作
		$this->controller_hidelogin();
		
		return true;
		
	}
	
	
}