<?php
/*
 * 每月礼包数据的获取
 * 
 */
class HyXb207 extends HyXb{
	
	
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
	
	
	//进行操作
	protected function controller_getlibao(){
		
		
		//礼包数据的查询
		$config_sql = "select shuoming,picurl,guize,fangfa,title1,title2,title3,title4,shengming from xb_config where flag='2' ";
		$config_list = parent::__get('HyDb')->get_all($config_sql); 
		
		if(count($config_list)>0){
				
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '礼包信息获取成功';
			$echoarr['dataarr'] = $config_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo str_replace("\/", "/",  json_encode($echoarr));
			return true;
			
		}else{
				
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '礼包信息获取失败';
			$echoarr['dataarr']    = array();
			
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
		
		//操作类型的判断
		if(parent::__get('xb_thetype')!='207'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//每月礼包数据的获取
		$this->controller_getlibao();
	
		return true;
	
	
	}
}