<?php
/*
 * 版本的升级---ios
 * 
 */

class HyXb107 extends HyXb{
	
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
		
		
		//ios版本更新
		$versionios_sql = "select * from xb_versioninfo where systemtype='2' and flag='1' order by id desc ";
		$versionios_list = parent::__get('HyDb')->get_row($versionios_sql);
		
		
		/* $apkurl = str_replace("./","/",$version_list['apk_url']);
		
		$version_list['apk_url'] = $this->updatepath.$apkurl; */
		
		/* $data = array(
				'systemtype'  => '安卓',
				'versioncode' => '230',
				'uptype' => '2',
				'apk_url' => 'http://120.27.34.239:8009/duidui/apk/app-release.apk',
				'updescription' => '最新版本230',
				'upshuoming'    => '测试版本升级',
				'up_createtime' =>  '版本信息获取时间',
		);
 */
		
		/* $config = dirname(dirname(__FILE__)).'/config.txt';
		
		$str = serialize($data);
		
		
		file_put_contents($config,$str);
		
		$temparr = unserialize(file_get_contents($config)); */
		
		if(count($versionios_list)>0){
		
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '版本信息获取成功';
			$echoarr['dataarr'] = $versionios_list;
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