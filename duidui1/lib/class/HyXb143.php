<?php
/*
 * 用户收货地址的修改
 */
class HyXb143 extends HyXb{
	
	
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
		
		//获取地址修改的参数
		$this->mobile     = isset($input_data['mobile'])? $input_data['mobile']:'';
		$this->shouhuoren = isset($input_data['shouhuoren'])?$input_data['shouhuoren']:'';
		$this->province   = isset($input_data['province'])?$input_data['province']:'';
		$this->city       = isset($input_data['city'])?$input_data['city']:'';
		$this->address    = isset($input_data['address'])?$input_data['address']:'';
		$this->zipcode    = isset($input_data['zipcode'])?$input_data['zipcode']:'';
		$this->is_default = isset($input_data['is_default'])? $input_data['is_default']:'9';  //
		$this->address_id = isset($input_data['address_id'])?$input_data['address_id']:'';
	
	}
	
	
	//用户地址修改的主操作
	protected function controller_edituserinfo(){
		
		
		if($this->mobile!=''|| $this->shouhuoren!=''|| $this->province!='' || $this->city!='' || $this->address!=''||$this->zipcode!=''||$this->is_default!='') {
				
			$sql_update = "update xb_user_address set ";
				
			if($this->mobile!='') {
				$sql_update .= " mobile='".$this->mobile."', ";
			}
			if($this->shouhuoren!='') {
				$sql_update .= " shouhuoren='".$this->shouhuoren."', ";
			}
			if($this->province!='') {
				$sql_update .= " province='".$this->province."', ";
			}
			if($this->city!='') {
				$sql_update .= " city='".$this->city."', ";
			}
			
			if($this->address!='') {
				$sql_update .= " address='".$this->address."', ";
			}
			
			if($this->zipcode!='') {
				$sql_update .= " zipcode='".$this->zipcode."', ";
			}
			
			if($this->is_default!='') {
				$sql_update .= " is_default='".$this->is_default."', ";
			}
			
			$sql_update = rtrim($sql_update,', ');
				
			$sql_update .= "where id='".$this->address_id."'";
				
			$r = parent::__get('HyDb')->execute($sql_update);
			
			if($r===true){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '地址修改成功';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '地址修改失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
				
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '参数传递为空，数据修改失败';
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
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，地址修改失败！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//判断地址编号是否为空
		if($this->address_id==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '收货地址的唯一标识编号不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
		//用户信息修改入口
		$this->controller_edituserinfo();
	
		return true;
	}
	
	
	
	
}