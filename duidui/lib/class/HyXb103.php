<?php
/*
 * 关于馅饼的内容和图片，以及任务页免责声明获取--103
 * 
 */
class HyXb103 extends HyXb{
	
	private $picurlpath;
	private $content;    //关于馅饼流量内容描述
	//private $taskmianze;//任务页的免责声明"
	private $qq;          //增加的qq组
	private $version;     //版本号
	
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
	
	
		/*  //图片存放位置
		$this->picurlpath    = URLPATH;
		$this->content    = '馅饼流量内容描述';
		//$this->taskmianze = '任务页的免责声明';
		$this->qq         = '000000000';
		$this->version    = '1.0'; */
	
	}
	
	
	//进行操作
	protected function controller_getcontent(){
		
		
		$config_sql = "select qq,version,content from xb_config";
		$config_list = parent::__get('HyDb')->get_all($config_sql);
		
		/*  //定义临时数组
		$temparr= array(
				array(
					'content'    => $this->content,
					//'taskmianze' => $this->taskmianze,
					'qq'         => $this->qq,
					'version'    => $this->version,
				),
		); */
		
		
		if(count($config_list)>0){
				
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '馅饼的内容和图片获取成功';
			$echoarr['dataarr'] = $config_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo str_replace("\/", "/",  json_encode($echoarr));
			return true;
				
		}else{
				
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '馅饼的内容和图片获取失败';
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
	
	
		//关于馅饼的内容和图片，以及任务页免责声明获取
		$this->controller_getcontent();
	
		return true;
	
	
	}
}