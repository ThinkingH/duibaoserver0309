<?php
/*
 * 发现的美食分类
 */
class HyXb802 extends HyXb{
	
	private $quantype; //主分类
	
	
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
	
		$this->quantype = isset($input_data['quantype'])? $input_data['quantype']:'';  //美食的主分类
	
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	
	}
	
	
	
	public function controller_findproductlist(){
		
		if($this->quantype=='all'){
			
			$maintypesql  = "select maintype,count(*) as num from z_tuanmainlist where flag='1' and shstatus='11' group by maintype ";
			$maintypelist = parent::__get('HyDb')->get_all($maintypesql);
			
		}else{
			$maintypesql  = "select childtype,count(*) as num from z_tuanmainlist  where maintype='".$this->quantype."' and flag='1' and shstatus='11' group by childtype ";
			$maintypelist = parent::__get('HyDb')->get_all($maintypesql);
		}
	
		if(count($maintypelist)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '分类获取成功';
			$echoarr['dataarr'] = $maintypelist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
	
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '分类获取失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
	}
	
	
	
	//操作入口--美食分类
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//美食
		if($this->quantype==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '美食主分类字段不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
	
		}
		
	
	
		//发现列表的获取入口
		$this->controller_findproductlist();
	
		return true;
	}
	
	
	
}