<?php
/*
 * 短信验证码登录--验证码下发
 */
class HyXb111 extends HyXb{
	
	
	private $phone;
	
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
	
	
		//接受验证码的手机号
		$this->phone = isset($input_data['phone']) ? $input_data['phone']:'';  //
	
	}
	
	
	//短信下发验证码--操作
	protected function controller_sendcode(){
		
		//生成6位短信验证码
		$vcode = parent::func_create_tempvcode();
		
		//获取验证码的短信内容
		$message = parent::func_create_vcode_message($type='1',$vcode);
		
		//发送验证码
		if($vcode!=''){
			
			$r = parent::func_send_sms($type='1',$this->phone,$vcode,$message);
			
			if($r===true){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '验证码发送成功';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '验证码发送失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}
		
	}
	
	
	
	//操作入口--短信下发验证码
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
		
		
		//判断手机号是否为空
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
	
		//推送信息的删除入口
		$this->controller_sendcode();
	
		return true;
	}
}