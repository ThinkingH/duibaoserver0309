<?php
/*
 * 用户收货地址的删除
 */

class HyXb144 extends HyXb{
	
	
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
	
		//收货地址的唯一标识编号
		$this->address_id = isset($input_data['address_id'])? $input_data['address_id']:'';
	}
	
	
	//用户的删除操作
	protected function controller_deleteuserinfo(){
		
		
		$deladdress_sql  = "delete from xb_user_address where id='".$this->address_id."'";
		$this->log_str .= HyItems::hy_trn2space($deladdress_sql)."\n";
		$deladdress_list = parent::__get('HyDb')->execute($deladdress_sql); 
		
		if($deladdress_list===true){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '地址删除成功';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '地址删除失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
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
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，地址删除失败！';
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
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
		//用户收货地址的删除入口
		$this->controller_deleteuserinfo();
	
		return true;
	}
}