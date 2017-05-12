<?php
/*
 * 用户意见反馈的提交--操作类型121
 */

class HyXb121 extends HyXb{
	
	private $xb_yijian;
	
	
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
	
		//意见
		$this->xb_yijian   = isset($input_data['yijian'])? $input_data['yijian']:'';  //用户提交的意见
	
	}
	
	
	//操作入口
	protected function controller_gesuggest(){
		
		
		//数据库入库
		$yijian_sql  = "insert into xb_yijian(type,userid,content,create_datetime) values 
				      ('".parent::__get('xb_usertype')."','".parent::__get('xb_userid')."','".$this->xb_yijian."','".parent::__get('create_datetime')."')";
		$yijian_list = parent::__get('HyDb')->execute($yijian_sql);
		
		
		if($yijian_list===true){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '意见修改成功';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '意见修改失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		} 
		
	}
	
	
	//用户意见反馈操作入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
	
		//判断yijian提交的参数是否为空
		if($this->xb_yijian==''){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户反馈的意见不能为空';
			$echoarr['dataarr']    = array();
			echo json_encode($echoarr);
			return false;
			
		}
		
		//进行意见反馈操作
		$this->controller_gesuggest();
	
		return true;
	
	
	}
	
	
}