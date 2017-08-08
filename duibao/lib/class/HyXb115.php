<?php
/*
 * 微信登录
 */
class HyXb115 extends HyXb{
	
	private $phone;
	private $userid;
	private $userkey;
	
	
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
	
	
	
		//接收用户手机号
		$this->phone = isset($input_data['phone']) ? $input_data['phone']:'';  //
	
		//接收用户的userid
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';  //
	
		//接收临时用户的key
		$this->userkey = isset($input_data['userkey']) ? $input_data['userkey']:'';  //
	}
	
	
	//微信绑定主操作
	public function controller_checkphone(){
		
		//判断该用户是否绑定手机号
		$openid_sql  = "select id,phone,openid from xb_user where id='".$this->userid."'";
		$openid_list = parent::__get('HyDb')->get_row($openid_sql);
		
		if($openid_list['phone']!=''){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '微信手机号已绑定';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
			
		}else{//手机号进行绑定
			
			//判手机号是否注册
			$phone_sql  = "select id,keyong_jifen,nickname,touxiang from xb_user where phone='".$this->phone."'";
			$phone_list = parent::__get('HyDb')->get_row($phone_sql); 
			
			if($phone_list['id']>0){//该手机号注册过，数据整合
				
				$jifen = $phone_list['keyong_jifen'];
				$nickname = $phone_list['nickname'];
				$touxiang = $phone_list['touxiang'];
				
				//以前的手机号账户关闭
				$updatephone_sql  = "update xb_user set is_lock='9',remark='手机与微信进行绑定，该手机号账户关闭' where phone='".$this->phone."' ";
				$updatephone_list = parent::__get('HyDb')->execute($updatephone_sql);
				
				$updatesql = "update xb_user set phone='".$this->phone."',keyong_jifen=keyong_jifen+'".$jifen."' where id='".$this->userid."' ";
				$updatelist = parent::__get('HyDb')->execute($updatesql);
				
				
				
			}else{//该手机号未注册
				
				//微信和手机号进行关联绑定
				$updatesql  = "update xb_user set phone='".$this->phone."' where id='".$this->userid."' ";
				$updatelist = parent::__get('HyDb')->execute($updatesql);
			}
			
			
			if($updatelist){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '微信手机号绑定成功';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '微信手机号绑定失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			
			
		}
		
		
		
		
		
		
		
		
		
		/* //根据用户id获取用户的openid
		$selectopenidsql  = "select id,phone,openid from xb_user where id='".$this->userid."' and phone='".$this->phone."'";
		$selectopenidlist = parent::__get('HyDb')->get_row($selectopenidsql); 
		
		if($selectopenidlist['id']>0){//该用户存在
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '微信手机号已绑定';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
			
		}else{//进行微信登录
			
			//微信和手机号进行关联绑定
			$updatesql  = "update xb_user set phone='".$this->phone."' where id='".$this->userid."' ";
			$updatelist = parent::__get('HyDb')->execute($updatesql);
			
			
			
			if($updatelist){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '微信手机号绑定成功';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '微信手机号绑定失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		} */
		
	}
	
	
	
	
	
	//操作入口
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
		
	
		//用户类型的判断
		if(parent::__get('xb_usertype')==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户类型不能为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		//判断phone是否为空
		if($this->phone==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '手机号不能为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	
		//数据的插入
		$this->controller_checkphone();
	
		return true;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}