<?php
/*
 * 极光推送用户关联id
 */

class HyXb134 extends HyXb{
	
	private $xb_jiguangid;
	private $xb_tag1;
	private $xb_tag2;
	private $xb_tag3;
	
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
		$this->xb_jiguangid   = isset($input_data['jiguangid'])? $input_data['jiguangid']:'';  //极光id
		$this->xb_tag1   = isset($input_data['tag1'])? $input_data['tag1']:'';  //极光id
		$this->xb_tag2   = isset($input_data['tag2'])? $input_data['tag2']:'';  //极光id
		$this->xb_tag3   = isset($input_data['tag3'])? $input_data['tag3']:'';  //极光id
	
	}
	
	
	public function controller_jiguang(){
		
		//获取用户登录的类型
		$usertype = parent::__get('xb_usertype');
		
		if($usertype=='1'){
			$tablename = 'xb_user';
		}else if($usertype=='2'){
			$tablename='xb_temp_user';
		}
		
		if($this->xb_jiguangid!='' || $this->xb_tag1!='' || $this->xb_tag2!='' || $this->xb_tag3!='' ){
			
			$sql_update = "update $tablename set ";
			
			if($this->xb_jiguangid!='') {
				$sql_update .= " jiguangid='".$this->xb_jiguangid."', ";
			}
			
			if($this->xb_tag1!=''){
				
				$sql_update .= " tag1='".$this->xb_tag1."', ";
			}
			
			if($this->xb_tag2!=''){
				
				$sql_update .= " tag2='".$this->xb_tag2."', ";
			}
			
			if($this->xb_tag3!=''){
				
				$sql_update .= " tag3='".$this->xb_tag3."', ";
			}
			
			$sql_update = rtrim($sql_update,', ');
			
			$sql_update .= "where id='".parent::__get('xb_userid')."' ";
			
			$r = parent::__get('HyDb')->execute($sql_update);
			
			if($r===true){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '字段更新成功';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '字段更新失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
		}
		
	}
	
	
	
	//用户意见反馈操作入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
		
		$shuzu = array('1','2');
		
		if(!in_array(parent::__get('xb_usertype'),$shuzu)){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
		//进行意见反馈操作
		$this->controller_jiguang();
	
		return true;
	
	
	}
}