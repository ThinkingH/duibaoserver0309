<?php
/*
 * 微信授权登录--获取access_token
 */
class HyXb116 extends HyXb{
	
	private $appid;
	private $appsecret;
	
	
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
	
		$this->appid = '';
		$this->appsecret='';
	
	}
	
	
	//微信绑定主操作
	public function controller_checkphone(){
		
		$redirectUrl = urlencode("你的回调页面的地址");
		$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.$redirectUrl.'
				&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
		
		header("Location:" . $url);
		
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