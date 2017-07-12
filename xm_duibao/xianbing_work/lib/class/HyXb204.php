<?php
/*
 * 饭票类型获取
 */

class HyXb204 extends HyXb{
	
	
	
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
	
	
	//饭票类型的获取
	public function controller_collectiontypelist(){
		
		$youhuiquanconfsql  = "select * from xb_kind where flag='1' and biaoshi='1'";
		$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql); 
		
		if(count($youhuiquanconflist)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '优惠券类型获取成功';
			$echoarr['dataarr'] = $youhuiquanconflist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '优惠券类型获取失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	
	
	//操作入口--饭票类型的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='204'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		
	
		//饭票类型的获取
		$this->controller_collectiontypelist();
	
		return true;
	}
	
	
	
	
}