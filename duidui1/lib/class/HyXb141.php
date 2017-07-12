<?php
/*
 * 用户收货地址的获取--操作类型141
 */
class HyXb141 extends HyXb{
	
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
	
	
	//用户地址的获取操作
	protected function controller_getuserinfo(){
		
		
		//用户id
		$userid = parent::__get('xb_userid');
		
		$getaddress_sql  = "select id,is_default,mobile,shouhuoren,province,city,address,zipcode from xb_user_address where userid='".$userid."'";
		$getaddress_list = parent::__get('HyDb')->get_all($getaddress_sql); 
		
		if(count($getaddress_list)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '收货地址获取成功';
			$echoarr['dataarr'] = $getaddress_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '收货地址获取失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
			
		}
		
	}
	
	
	//操作入口
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
		
		
		//判断是否为正常用户
		if(parent::__get('xb_usertype')!='1'){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户是非正常用户类型，不支持用户地址的列表获取';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//用户信息修改入口
		$this->controller_getuserinfo();
	
		return true;
	}
	
	
	
	
}