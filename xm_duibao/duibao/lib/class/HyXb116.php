<?php
/*
 * 微信授权登录--获取access_token
 */
class HyXb116 extends HyXb{
	
	private $appid;
	private $appsecret;
	private $code;
	
	
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
		
		$this->appid = APPID; //微信的appid
		$this->appsecret = SECRET; //微信的SECRET
		$this->code = isset($input_data['code'])? $input_data['code']:'';
	
	}
	
	/* https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code */
	
	//微信绑定主操作
	public function controller_checkphone(){
		
		//请求地址
		$url   = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$this->code.'&grant_type=authorization_code';
		$data  = HyItems::vget( $url, 10000 );
		
		if($data['httpcode']=='200' && $data['content']['access_token']!='' ){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '获取成功';
			$echoarr['dataarr'] = $data;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '获取失败';
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
		
		//数据的插入
		$this->controller_checkphone();
	
		return true;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}