<?php
/*
 * 用户收货地址的添加
 */

class HyXb142 extends HyXb{
	
	private $mobile;
	private $shouhuoren; //收货人
	private $province;
	private $city;
	private $address;
	private $zipcode;   //邮编
	private $is_default; //默认地址
	
	
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
	
		$this->mobile     = isset($input_data['mobile'])? $input_data['mobile']:'';
		$this->shouhuoren = isset($input_data['shouhuoren'])?$input_data['shouhuoren']:'';
		$this->province   = isset($input_data['province'])?$input_data['province']:'';
		$this->city       = isset($input_data['city'])?$input_data['city']:'';
		$this->address    = isset($input_data['address'])?$input_data['address']:'';
		$this->zipcode    = isset($input_data['zipcode'])?$input_data['zipcode']:'';
		$this->is_default = isset($input_data['is_default'])? $input_data['is_default']:'9';
		if($this->is_default=='') {
			$this->is_default = 9;
		}
	}
	
	
	protected function controller_edituseraddress(){
		
		
		$address_sql = "insert into xb_user_address (userid,mobile,shouhuoren,province,city,address,zipcode,is_default) 
						values ('".parent::__get('xb_userid')."','".$this->mobile."','".$this->shouhuoren."','".$this->province."'
						,'".$this->city."','".$this->address."','".$this->zipcode."','".$this->is_default."')";
		$address_list = parent::__get('HyDb')->execute($address_sql);
		
		if($address_list===true){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '地址添加成功';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '地址添加失败';
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
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		//手机号是否为空
		if($this->mobile==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '手机号不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//手机号格式是否正确
		if(!is_numeric($this->mobile)||strlen($this->mobile)!='11'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '手机号的格式不正确';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//收货人
		if($this->shouhuoren==''){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '收货人不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//省份
		if($this->province==''){
				
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '省份不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//城市
		if($this->city==''){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '城市不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//详细地址
		if($this->address==''){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '详细地址不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		/* //默认地址的设置
		if($this->is_default==''){
			$this->is_default = '9';
		} */
		
		//用户信息修改入口
		$this->controller_edituseraddress();
	
		return true;
	
	}
}