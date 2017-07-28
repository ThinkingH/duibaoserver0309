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
		
		
		$this->type   = isset($input_data['type'])? $input_data['type']:'';  
	}
	
	
	public function controller_shengji(){
		
		$systemtype = '1';//代表安卓安装包
		
		if($this->type=='' || $this->type=='1'){
			
			//数据的查询
			$version_sql = "select * from xb_versioninfo where systemtype='1' and flag='1' order by id desc ";
			$version_list = parent::__get('HyDb')->get_row($version_sql);
			
		}else if($this->type=='2'){
			
			$version_sql = "select * from xb_versioninfo where systemtype='2' and flag='1' order by id desc ";
			$version_list = parent::__get('HyDb')->get_row($version_sql);
		}
		
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
		
		if(count($version_list)>0){
		
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '版本信息获取成功';
			$echoarr['dataarr'] = $version_list;
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