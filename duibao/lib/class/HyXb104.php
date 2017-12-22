<?php
/*
 * 版本的升级
 * 
 */

class HyXb104 extends HyXb{
	
	private $updatepath;
	
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
		
		//apk上传的后台地址
		$this->updatepath = URLUPDATE;
	}
	
	
	public function controller_shengji(){
		
		$systemtype = '1';//代表安卓安装包
		
		//数据的查询
		$version_sql = "select * from xb_versioninfo where systemtype='ANDROID' and flag='1'  ";
		$version_list = parent::__get('HyDb')->get_row($version_sql);
		
		if(count($version_list)>0){
		
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '版本信息获取成功';
			$echoarr['dataarr'] = $version_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
				
		}else{
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '版本信息获取失败';
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
	
		
		$this->controller_shengji();
	
		return true;
	
	}
	
	
}