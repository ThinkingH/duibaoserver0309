<?php
/*
 * 启动页图片url地址获取
 */

class HyXb101 extends HyXb{
	
	private $picurlpath;
	
	
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
		
		
		//图片存放位置
		//$this->picpath = PICPATH;
		$this->picurlpath = URLPATH;//http://xbapp.xinyouxingkong.com/duidui/picture/big.jpg
	
	}
	
	/* {"returncode":"success","returnmsg":"\u83b7\u53d6\u6210\u529f","dataarr":["http:\/\/127.0.0.1\/img\/qidongtu.jpg"]} */
	
	//进行操作
	protected function controller_geturl(){
		
		//获取图片的地址
		$picpath = $this->picurlpath.'big.jpg';
		
		//定义临时数组
		$temparr= array();
		
		$temparr[0]=$picpath;
		
		
		if($picpath!=''){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '启动页图片获取成功';
			$echoarr['dataarr'] = $temparr;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo str_replace("\/", "/",  json_encode($echoarr));
			return true;
			
		}else{
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '启动页图片获取失败';
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
		
		
		//进行启动页图片url地址获取
		$this->controller_geturl();
		
		return true;
		
		
	}
	
	
	
	
}