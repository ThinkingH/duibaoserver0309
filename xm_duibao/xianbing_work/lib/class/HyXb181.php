<?php
/*
 * 用户兑换秘钥的获取
 */
class HyXb181 extends HyXb{
	
	private $count;
	private $page;
	
	
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
	
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	
	}
	
	
	//获取兑换的主要操作
	public function controller_getduihuan(){
		
		if($this->page==''||$this->page=='0'){
		
			$this->page=1;
		}
		
		if($this->count==''){
			
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		//兑换数据的获取
		$duihuansql = "select id,type,thekey,name,content,create_datetime,over_datetime from xb_user_key where userid='".parent::__get('xb_userid')."' 
						order by id desc limit $firstpage,$pagesize";
		$duihuanlist = parent::__get('HyDb')->get_all($duihuansql);
		
		if(count($duihuanlist)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '用户兑换密钥获取成功';
			$echoarr['dataarr'] = $duihuanlist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户兑换密钥获取失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	
	//操作入口--秘钥兑换的入口
	public function controller_init(){
		
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		
		if($r===false){
		
			return false;
		}
		
		//操作类型的判断
		if(parent::__get('xb_thetype')!='181'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//判断是否为正常用户
		if(parent::__get('xb_usertype')!='1'){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户登录类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//判断每页的条数，数值介于1到20之间
		if($this->count<0 || $this->count>20){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '每页展示的条数超过20条';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		
		}
		
		
		//兑换获取的入口
		$this->controller_getduihuan();
		
		return true;
	}
}