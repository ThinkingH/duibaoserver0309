<?php
/*
 * 历史搜索记录的获取---显示该用户最近6条的搜索记录
 */

class HyXb602 extends HyXb{
	
	private $type;
	
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
		
		//搜索记录的操作类型 1-历史记录的获取，获取最近6条的历史记录  2-历史记录的删除
		$this->type = isset($input_data['type']) ? $input_data['type']:'';  
		
	}
	
	
	public function controller_search_list(){
		
		if($this->type=='1'){
			//查询用户最近6的历史记录
			$historysql  = "select id,name,create_date from search_history where userid='".$this->userid."' order by create_date desc limit 5 ";
			$historylist = parent::__get('HyDb')->get_all($historysql);
			
			if(count($historylist)>0){
					
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '历史列表获取成功！';
				$echoarr['dataarr']    = $historylist;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
					
				echo json_encode($echoarr);
				return true;
					
			}else{
					
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '历史列表获取为空！';
				$echoarr['dataarr']    = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
			
				echo json_encode($echoarr);
				return true;
			}
		}else if($this->type=='2'){//历史记录的删除
			
			$historysql  = "delete from search_history where userid='".$this->userid."' ";
			$historylist = parent::__get('HyDb')->execute($historysql);
			
			if($historylist){
					
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '历史记录删除成功！';
				$echoarr['dataarr']    = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
					
				echo json_encode($echoarr);
				return true;
					
			}else{
					
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '历史记录删除失败';
				$echoarr['dataarr']    = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
					
				echo json_encode($echoarr);
				return false;
			}
			
		}
		
		
	}
	
	
	//历史搜索的获取操作入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
	
		//首页类型的获取
		$this->controller_search_list();
	
		return true;
	
	}
	
	
}