<?php
/*
 订单记录列表的删除
 */

class HyXb531 extends HyXb{
	
	private $taskid;
	
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
		
		//接收用户的userid
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:''; 
		$this->taskid = isset($input_data['taskid']) ? $input_data['taskid']:'';
		$this->kindtype = isset($input_data['kindtype']) ? $input_data['kindtype']:''; //为空 默认删除 1-确认收货
	
	}
	
	
	
	public function controller_getproducttypelist(){
		
		
		if($this->kindtype==''){
			$shopproductsql = "update shop_userbuy set status='6' where id='".$this->taskid."' ";
			//$shopproductsql  = "delete from shop_userbuy where id='".$this->taskid."'";
			$shopproductlist = parent::__get('HyDb')->execute($shopproductsql);
			
		}else if($this->kindtype=='1'){
			
			$shopproductsql = "update shop_userbuy set status='5', fh_shouhuotime='".date('Y-m-d H:i:s')."' where id='".$this->taskid."' ";
			$shopproductlist = parent::__get('HyDb')->execute($shopproductsql);
		}
		
		if(count($shopproductlist)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '操作成功';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
	}
	
	
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
		
		if($this->taskid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '订单id不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	
		$this->controller_getproducttypelist();
	
		return true;
	}
	
}