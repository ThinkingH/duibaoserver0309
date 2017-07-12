<?php
/*
 * 极光推送用户关联id
 */

class HyXb134 extends HyXb{
	
	private $xb_jiguangid;
	
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
	
		//意见
		$this->xb_jiguangid   = isset($input_data['jiguangid'])? $input_data['jiguangid']:'';  //极光id
	
	}
	
	
	public function controller_jiguang(){
		
		//获取用户登录的类型
		$usertype = parent::__get('xb_usertype');
		
		$jiguangid = trim($this->xb_jiguangid);
		
		if($jiguangid!=''){
			
			if($usertype=='1'){
				
				//更新用户表插入该字段
				$tuisong_sql = "update xb_user set jiguangid ='".$jiguangid."' where id='".parent::__get('xb_userid')."'";
				$tuisong_list = parent::__get('HyDb')->execute($tuisong_sql);
					
					
					
			}else if($usertype=='2'){
					
				//更新用户表插入该字段
				$tuisong_sql = "update xb_temp_user set jiguangid ='".$jiguangid."' where id='".parent::__get('xb_userid')."'";
				$tuisong_list = parent::__get('HyDb')->execute($tuisong_sql);
				
			}else if($usertype=='3'){
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '用户类型错误';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}
			
		}else{
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '极光id为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	}
	
	
	
	//用户意见反馈操作入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
	
	
		//进行意见反馈操作
		$this->controller_jiguang();
	
		return true;
	
	
	}
}