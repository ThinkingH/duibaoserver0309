<?php
/*
 * 搜索历史记录的关键字提示
 */

class HyXb603 extends HyXb{
	
	private $proname;
	
	
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
		
		//搜索关键字提示
		$this->proname = isset($input_data['proname']) ? $input_data['proname']:'';
	
	}
	
	
	public function controller_searchkey(){
		
		$sqldata  = "select * from search_config where name like '%".$this->proname."%' order by id desc limit 5";
		$listdata = parent::__get('HyDb')->get_all($sqldata);
		
		if(count($listdata)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '提示词列表获取成功！';
			$echoarr['dataarr']    = $listdata;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
				
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '提示词列表为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			
			echo json_encode($echoarr);
			return true;
		}
		
		
	}
	
	
	
	
	//操作入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
	
		if($this->proname==''){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '提示关键词不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
			
		}
	
		//首页类型的获取
		$this->controller_searchkey();
	
		return true;
	
	}
	
	
}